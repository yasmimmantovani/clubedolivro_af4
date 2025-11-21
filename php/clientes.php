<?php 
session_start();
include('conexao.php');

// Cadastrar ou editar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    if (!empty($_POST['id_clientes'])) {
        $id = $_POST['id_clientes'];
        $mysqli->query("update clientes set
                        nome='$nome',
                        email='$email',
                        endereco='$endereco'
                        where id_clientes=$id");
    } else {
        $mysqli->query("insert into clientes(nome, email, endereco)
                        values('$nome', '$email', '$endereco')");
    }
    header("Location: clientes.php");
    exit;
}

// Excluir
if(isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $mysqli->query("delete from clientes where id_clientes=$id");
    header("Location: clientes.php");
    exit;
}

// Buscar para editar
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $mysqli->query("select * from clientes where id_clientes=$id limit 1");
    $edit = $res->fetch_assoc();
}

// Listar com pesquisa
$busca = $_GET['q'] ?? "";
$sql = "select * from clientes where 1";

if ($busca !== "") {
    $sql .= " and nome like '%$busca%'";
}

$sql .= " order by nome asc";
$dados = $mysqli->query($sql);
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Clientes - BookLover</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/modal.css">
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
                <a href="dashboard.php">Visão geral</a>
                <a href="livros.php">Gerenciar livros</a>
                <a href="clientes.php" class="active">Gerenciar clientes</a>
                <a href="emprestimos.php">Empréstimos</a>
            </nav>
        </aside>

        <main class="main-content">
            <h2>Gerenciar clientes</h2>

            <form method="POST" class="form-card">
                <input type="hidden" name="id_clientes" class="input" value="<?= $edit['id_clientes'] ?? '' ?>">

                <label>Nome:</label>
                <input type="text" name="nome" class="input" required value="<?= $edit['nome'] ?? '' ?>">

                <label>E-mail:</label>
                <input type="email" name="email" class="input" required value="<?= $edit['email'] ?? '' ?>">

                <label>Endereço:</label>
                <input type="text" name="endereco" class="input" required value="<?= $edit['endereco'] ?? '' ?>">

                <button type="submit" class="btn-submit"><?= $edit ? "Salvar Alterações" : "Cadastrar" ?></button>
            </form>

            <form method="GET" class="search-box">
                <input type="text" name="q" placeholder="Pesquisar clientes..." value="<?= $busca ?>">
                <button class="btn-submit">Buscar</button>
            </form>

            <table border="1" width="100%" cellpadding="8">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>

                <?php while($l = $dados->fetch_assoc()): ?>
                    <tr>
                        <td><?= $l['id_clientes'] ?></td>
                        <td><?= $l['nome'] ?></td>
                        <td><?= $l['email'] ?></td>
                        <td><?= $l['endereco'] ?></td>
                        <td class="table-actions">
                            <a class="action-btn" href="clientes.php?edit=<?= $l['id_clientes'] ?>">Editar</a>
                            <a class="action-btn" href="clientes.php?del=<?= $l['id_clientes'] ?>" onclick="return confirmarExclusao(<?= $l['id_clientes'] ?>);">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>

    <!-- Modal de confirmação -->
    <div id="confirmModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir este registro?</p>

            <div class="modal-actions">
                <button id="cancelBtn" class="btn-secondary">Cancelar</button>
                <a id="confirmDelete" class="btn-danger">Excluir</a>
            </div>
        </div>
    </div>
        

    <!-- Tema -->
    <script src="../js/theme.js"></script>
    <script src="../js/dashboard.js"></script>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
