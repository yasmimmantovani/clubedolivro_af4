<?php
require_once __DIR__ . '/vendor/fpdf/fpdf.php';
include('conexao.php');

// tipo=livros ou tipo=usuarios
$tipo = $_GET['tipo'] ?? 'livros';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10, 'Relatorio - ' . strtoupper($tipo),0,1,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial','',12);

if ($tipo === 'livros') {
    $res = $mysqli->query("SELECT id_livro, titulo, autor, ano, quantidade FROM livros ORDER BY titulo ASC");
    if ($res && $res->num_rows) {
        while($r = $res->fetch_assoc()) {
            $pdf->Cell(0,7, sprintf("%s — %s (%s) — qtd: %d", $r['titulo'], $r['autor'] ?: '—', $r['ano'] ?: '-', (int)$r['quantidade']),0,1);
        }
    } else {
        $pdf->Cell(0,7, 'Nenhum livro encontrado.',0,1);
    }
} else {
    $res = $mysqli->query("SELECT id_cliente, nome, email FROM clientes ORDER BY nome ASC");
    if ($res && $res->num_rows) {
        while($r = $res->fetch_assoc()) {
            $pdf->Cell(0,7, sprintf("%i — %s — %s", $r['id_cliente'], $r['nome'], $r['email']),0,1);
        }
    } else {
        $pdf->Cell(0,7, 'Nenhum usuario encontrado.',0,1);
    }
}

// Define nome do arquivo no download
$filename = "relatorio_{$tipo}_" . date('Ymd_His') . ".pdf";
$pdf->Output('D', $filename);
