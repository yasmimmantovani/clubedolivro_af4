<?php
include('conexao.php');
header('Content-Type: application/json');

$livros = 0;
$usuarios = 0;

$res = $mysqli->query("SELECT COUNT(*) AS c FROM livros");
if ($res) { $row = $res->fetch_assoc(); $livros = (int)$row['c']; }

$res = $mysqli->query("SELECT COUNT(*) AS c FROM usuarios_adm");
if ($res) { $row = $res->fetch_assoc(); $usuarios = (int)$row['c']; }

echo json_encode(['livros' => $livros, 'usuarios' => $usuarios]);
?>