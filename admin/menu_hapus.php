<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();

$id = (int)$_GET['id'];
$q = mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id = $id");
$m = mysqli_fetch_assoc($q);

if ($m) {
    if ($m['gambar']) hapusGambar($m['gambar']);
    mysqli_query($koneksi, "DELETE FROM menu WHERE id = $id");
    $_SESSION['pesan'] = "Menu berhasil dihapus.";
}
header("Location: menu.php");
?>