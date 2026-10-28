<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['pesan'] = "ID menu tidak valid.";
    header("Location: menu.php");
    exit;
}

// Ambil data menu
$q = mysqli_query($koneksi, "SELECT id, nama_menu, gambar FROM menu WHERE id = $id");
$m = $q ? mysqli_fetch_assoc($q) : null;

if (!$m) {
    $_SESSION['pesan'] = "Menu tidak ditemukan.";
    header("Location: menu.php");
    exit;
}

// Mulai transaksi agar konsisten (semua berhasil / semua dibatalkan)
mysqli_begin_transaction($koneksi);

try {
    // 1) Hapus semua order_detail yang mereferensikan menu ini
    $stmt1 = mysqli_prepare($koneksi, "DELETE FROM order_detail WHERE menu_id = ?");
    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    $hapusDetail = mysqli_stmt_affected_rows($stmt1);
    mysqli_stmt_close($stmt1);

    // 2) Hapus file gambar (jika ada)
    if (!empty($m['gambar']) && function_exists('hapusGambar')) {
        hapusGambar($m['gambar']);
    }

    // 3) Hapus menu
    $stmt2 = mysqli_prepare($koneksi, "DELETE FROM menu WHERE id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    $hapusMenu = mysqli_stmt_affected_rows($stmt2);
    mysqli_stmt_close($stmt2);

    if ($hapusMenu < 1) {
        throw new Exception("Menu gagal dihapus (mungkin sudah tidak ada).");
    }

    mysqli_commit($koneksi);

    $info = "Menu '{$m['nama_menu']}' berhasil dihapus permanen.";
    if ($hapusDetail > 0) {
        $info .= " Termasuk {$hapusDetail} baris order_detail yang terkait.";
    }
    $_SESSION['pesan'] = $info;
    $_SESSION['pesan_tipe'] = 'success';

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $_SESSION['pesan'] = "Gagal menghapus menu: " . $e->getMessage();
    $_SESSION['pesan_tipe'] = 'danger';
}

header("Location: menu.php");
exit;