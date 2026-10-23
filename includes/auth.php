<?php
require_once __DIR__ . '/../config/koneksi.php';

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function isPelangganLoggedIn() {
    return isset($_SESSION['pelanggan_id']) && !empty($_SESSION['pelanggan_id']);
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        $_SESSION['error'] = 'Silakan login terlebih dahulu.';
        header('Location: login.php');
        exit;
    }
}

function requirePelanggan($redirectTo = '../index.php') {
    if (!isPelangganLoggedIn()) {
        header("Location: $redirectTo");
        exit;
    }
}
?>