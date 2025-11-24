<?php
session_start();

// Impede acesso sem login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

require("conexao.php");

// =========================
// CONSULTAS AO BANCO
// =========================

// Total de livros
$sqlLivros = $mysqli->query("SELECT COUNT(*) AS total FROM livros");
$totalLivros = $sqlLivros->fetch_assoc()['total'];

// Total de clientes
$sqlUsuarios = $mysqli->query("SELECT COUNT(*) AS total FROM clientes");
$totalUsuarios = $sqlUsuarios->fetch_assoc()['total'];

// Total de empréstimos
$sqlEmprestimos = $mysqli->query("SELECT COUNT(*) AS total FROM emprestimos");
$totalEmprestimos = $sqlEmprestimos->fetch_assoc()['total'];

// Últimos empréstimos
$sqlUltimos = $mysqli->query("
    SELECT e.id_emprestimo, l.titulo, c.nome, e.data_emprestimo 
    FROM emprestimos e
    JOIN livros l ON l.id_livro = e.id_livro
    JOIN clientes c ON c.id_clientes = e.id_clientes
    ORDER BY e.data_emprestimo DESC
    LIMIT 10
");

// =========================
// GERAR O CSV
// =========================

// Cabeçalho para download
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=relatorio_booklover.csv");

// Abre a saída
$output = fopen("php://output", "w");

// Escreve o resumo
fputcsv($output, ["Resumo"]);
fputcsv($output, ["Total de livros", $totalLivros]);
fputcsv($output, ["Total de clientes", $totalUsuarios]);
fputcsv($output, ["Total de emprestimos", $totalEmprestimos]);
fputcsv($output, []);

// Título da tabela de empréstimos
fputcsv($output, ["Últimos Empréstimos"]);
fputcsv($output, ["ID", "Livro", "Cliente", "Data"]);

// Linhas dos empréstimos
while ($l = $sqlUltimos->fetch_assoc()) {
    fputcsv($output, [
        $l['id_emprestimo'],
        $l['titulo'],
        $l['nome'],
        date('d/m/Y', strtotime($l['data_emprestimo']))
    ]);
}

fclose($output);
exit;
