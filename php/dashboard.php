<?php 
session_start();
include('conexao.php');

if (empty($_SESSION['id']) || empty($_SESSION['nivel']) || !in_array($_SESSION['nivel'], ['admin', 'funcionario'])) {
    header('Location: login.php');
    exit;
}

$counts = [
    'livros' => 0,
    'clientes' => 0,
];

$res = $mysqli->query("select count(*) as c from livros");
if ($res) {
    $row = $res->fetch_assoc(); $counts['livros'] = (int)$row['c'];
}

$res = $mysqli->query("select count(*) as c from clientes");
if($res) {
    $row = $res->fetch_assoc(); $counts['clientes'] = (int)$row['c'];
}

$ultimos_livros = [];
$res = $mysqli->query("select id_livro, titulo, autor, ano from livros order by data_cadastro desc limit 5");
if ($res) {
    while($r = $res->fetch_assoc()) $ultimos_livros[] = $r;
}

$ultimos_usuarios = [];
$res = $mysqli->query("select id_clientes, nome, email, data_cadastro from clientes order by data_cadastro desc limit 5");
if ($res) {
    while($r = $res->fetch_assoc()) $ultimos_usuarios[] = $r;
}

// Total de empréstimos
$qtdEmprestimos = $mysqli->query("SELECT COUNT(*) AS total FROM emprestimos")->fetch_assoc()['total'];

// Gêneros mais lidos
$queryGenero = $mysqli->query("
    SELECT l.genero, COUNT(e.id_emprestimo) AS total
    FROM emprestimos e
    JOIN livros l ON e.id_livro = l.id_livro
    GROUP BY l.genero
");

$generos = [];
$qtdPorGenero = [];

while($g = $queryGenero->fetch_assoc()) {
    $generos[] = $g['genero'];
    $qtdPorGenero[] = $g['total'];
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard - BookLover</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="shortcut icon" href="../img/pngegg.png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <button class="sidebar-toggle">
            <ion-icon name="menu-outline"></ion-icon>
        </button>
        
        <h1>BookLover</h1>

        <div class="header-right">
            <span class="user">Olá, <?= htmlspecialchars($_SESSION['nome']) ?> (<?= htmlspecialchars($_SESSION['nivel']) ?>)</span>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <nav>
                <a href="dashboard.php" class="active">Visão geral</a>
                <a href="livros.php">Gerenciar livros</a>
                <a href="clientes.php">Gerenciar clientes</a>
                <a href="emprestimos.php">Empréstimos</a>
            </nav>
        </aside>

        <main>
            <section class="cards">
                <div class="card">
                    <div class="card-tittle">Livros Cadastrados</div>
                    <div class="card-value" id="cnt-livros"><?= $counts['livros'] ?></div>
                </div>

                <div class="card">
                    <div class="card-tittle">Clientes</div>
                    <div class="card-value" id="cnt-clientes"><?= $counts['clientes'] ?></div>
                </div>

                <div class="card">
                    <div class="card-tittle">Ações Rápidas</div>
                    <div class="card-actions">
                        <a class="btn" href="livros.php">+ Cadastrar Livro</a>
                        <a class="btn" href="clientes.php">+ Cadastrar cliente</a>
                        <a class="btn" href="gerar_relatorio.php?tipo=livros" target="_blank">Gerar PDF</a>
                        <a class="btn" href="gerar_csv.php?tipo=livros">Exportar CSV</a>
                    </div>
                </div>
            </section>

            <section class="charts">
                <div class="chart-card">
                    <h3>Visão geral</h3>
                    <div class="chart-container">
                        <canvas id="overviewChart" height="120"></canvas>
                    </div>
                </div>
            </section>

            <section class="lists">
                <div class="list-card">
                    <h3>Últimos livros</h3>
                    <?php if (empty($ultimos_livros)): ?>
                        <p class="muted">Nenhum livro cadastrado ainda.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach($ultimos_livros as $l): ?>
                                <li><strong><?= htmlspecialchars($l['titulo']) ?></strong> — <?= htmlspecialchars($l['autor']) ?> (<?= htmlspecialchars($l['ano']) ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <div class="list-card">
                    <h3>Últimos clientes</h3>
                    <?php if(empty($ultimos_usuarios)): ?>
                        <p class="muted">Nenhum cliente cadastrado ainda.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach($ultimos_usuarios as $u): ?>
                                <li><?= htmlspecialchars($u['nome']) ?> — <?= htmlspecialchars($u['email']) ?> — <?= htmlspecialchars($u['data_cadastro']) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>


    <script>
        const qtdEmprestimos = <?= $qtdEmprestimos ?>;

        const generos = <?= json_encode($generos) ?>;
        const qtdGenero = <?= json_encode($qtdPorGenero) ?>;
    </script>
    <!-- Tema -->
     <script src="../js/theme.js"></script>
     <script src="../js/dashboard.js"></script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>