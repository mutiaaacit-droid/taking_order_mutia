<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit;
}

$menu_id = (int)$_POST['menu_id'];
$jumlah  = (int)$_POST['jumlah'];
if ($jumlah < 1) $jumlah = 1;

$q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $menu_id AND status = 'tersedia'");
$menu = mysqli_fetch_assoc($q);
if (!$menu) {
    $_SESSION['pesan'] = "Menu tidak ditemukan.";
    header("Location: ../pelanggan/menu.php");
    exit;
}

if ($jumlah > $menu['stok']) $jumlah = $menu['stok'];
if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];

if (isset($_SESSION['keranjang'][$menu_id])) {
    $_SESSION['keranjang'][$menu_id] += $jumlah;
    if ($_SESSION['keranjang'][$menu_id] > $menu['stok']) {
        $_SESSION['keranjang'][$menu_id] = $menu['stok'];
    }
} else {
    $_SESSION['keranjang'][$menu_id] = $jumlah;
}

$_SESSION['pesan'] = $menu['nama_menu'] . " ditambahkan ke keranjang.";
header("Location: ../pelanggan/menu.php");
?>