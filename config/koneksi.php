<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_cafe";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../includes/helpers.php';
?>