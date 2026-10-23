<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit;
}
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) {
    header("Location: ../pelanggan/keranjang.php");
    exit;
}

$pelanggan_id = $_SESSION['pelanggan_id'];
$no_meja      = $_SESSION['pelanggan_meja'];
$catatan      = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');
$kode_order   = generateKodeOrder();

$subtotal = 0;
foreach ($keranjang as $mid => $jml) {
    $q = mysqli_query($koneksi, "SELECT harga FROM menu WHERE id = $mid");
    $m = mysqli_fetch_assoc($q);
    if ($m) $subtotal += $m['harga'] * $jml;
}
$pajak = $subtotal * 0.1;
$total = $subtotal + $pajak;

$q_order = "INSERT INTO orders (kode_order, pelanggan_id, no_meja, subtotal, pajak, total_harga, catatan)
            VALUES ('$kode_order', $pelanggan_id, '$no_meja', $subtotal, $pajak, $total, '$catatan')";
if (!mysqli_query($koneksi, $q_order)) {
    die("Gagal menyimpan order: " . mysqli_error($koneksi));
}
$order_id = mysqli_insert_id($koneksi);

foreach ($keranjang as $mid => $jml) {
    $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $mid");
    $m = mysqli_fetch_assoc($q);
    if (!$m) continue;
    $sub = $m['harga'] * $jml;
    $nama_menu = mysqli_real_escape_string($koneksi, $m['nama_menu']);
    $gambar_menu = $m['gambar'] ? "'{$m['gambar']}'" : "NULL";
    
    mysqli_query($koneksi, "INSERT INTO order_detail (order_id, menu_id, nama_menu, gambar_menu, harga, jumlah, subtotal)
                            VALUES ($order_id, $mid, '$nama_menu', $gambar_menu, {$m['harga']}, $jml, $sub)");
    mysqli_query($koneksi, "UPDATE menu SET stok = stok - $jml WHERE id = $mid");
}

$_SESSION['keranjang'] = [];
$_SESSION['last_order_id'] = $order_id;
header("Location: ../pelanggan/sukses.php");
?>