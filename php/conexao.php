<?php
require __DIR__ . "/env.php";

loadEnv(__DIR__ . "/../.env");

$mysqli = new mysqli (
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($mysqli->connect_errno) {
    die("Erro ao conectar ao banco:" . $mysqli->connect_error);
}
// opcional: define charset
$mysqli->set_charset("utf8mb4");
