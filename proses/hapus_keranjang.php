<?php
require_once '../config/koneksi.php';
$id = (int)$_GET['id'];
unset($_SESSION['keranjang'][$id]);
header("Location: ../pelanggan/keranjang.php");
?>