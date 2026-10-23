<?php
require_once '../config/koneksi.php';
$menu_id = (int)$_POST['menu_id'];
$jumlah  = (int)$_POST['jumlah'];
if ($jumlah < 1) $jumlah = 1;

$q = mysqli_query($koneksi, "SELECT stok FROM menu WHERE id = $menu_id");
$m = mysqli_fetch_assoc($q);
if ($m && $jumlah > $m['stok']) $jumlah = $m['stok'];

if (isset($_SESSION['keranjang'][$menu_id])) {
    $_SESSION['keranjang'][$menu_id] = $jumlah;
}
header("Location: ../pelanggan/keranjang.php");
?>