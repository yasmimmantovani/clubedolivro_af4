<?php
session_start();

// Impede acesso sem login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

require("../libs/fpdf.php");
require("conexao.php"); // sua conexão PDO ou mysqli

// =========================
// CONSULTAS AO BANCO
// =========================

// Total de livros
$sqlLivros = $mysqli->query("SELECT COUNT(*) AS total FROM livros");
$totalLivros = $sqlLivros->fetch_assoc()['total'];

// Total de usuários
$sqlUsuarios = $mysqli->query("SELECT COUNT(*) AS total FROM clientes");
$totalUsuarios = $sqlUsuarios->fetch_assoc()['total'];

// Total de empréstimos
$sqlEmprestimos = $mysqli->query("SELECT COUNT(*) AS total FROM emprestimos");
$totalEmprestimos = $sqlEmprestimos->fetch_assoc()['total'];

// Últimos empréstimos (exemplo para listar)
$sqlUltimos = $mysqli->query("
    SELECT e.id_emprestimo, l.titulo, c.nome, e.data_emprestimo 
    FROM emprestimos e
    JOIN livros l ON l.id_livro = e.id_livro
    JOIN clientes c ON c.id_clientes = e.id_clientes
    ORDER BY e.data_emprestimo DESC
    LIMIT 10
");

// =========================
// GERAÇÃO DO PDF
// =========================

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, 'Relatorio Geral - BookLover', 0, 1, 'C');
$pdf->Ln(5);

// =========================
// RESUMO
// =========================
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Resumo', 0, 1);
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 6, "Total de livros cadastrados: $totalLivros", 0, 1);
$pdf->Cell(0, 6, "Total de clientes cadastrados: $totalUsuarios", 0, 1);
$pdf->Cell(0, 6, "Total de emprestimos: $totalEmprestimos", 0, 1);
$pdf->Ln(5);

// =========================
// LISTA DE EMPRESTIMOS RECENTES
// =========================
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Ultimos Emprestimos', 0, 1);
$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(10, 8, 'ID', 1);
$pdf->Cell(70, 8, 'Livro', 1);
$pdf->Cell(50, 8, 'Cliente', 1);
$pdf->Cell(40, 8, 'Data', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 11);

while ($linha = $sqlUltimos->fetch_assoc()) {
    $pdf->Cell(10, 8, $linha['id_emprestimo'], 1);
    $pdf->Cell(70, 8, utf8_decode($linha['titulo']), 1);
    $pdf->Cell(50, 8, utf8_decode($linha['nome']), 1);
    $pdf->Cell(40, 8, date('d/m/Y', strtotime($linha['data_emprestimo'])), 1);
    $pdf->Ln();
}

// =========================
// RODAPÉ
// =========================
$pdf->Ln(8);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 6, 'Gerado em: ' . date('d/m/Y H:i'), 0, 1, 'R');

$pdf->Output();
