<?php
session_start();
unset($_SESSION['pelanggan_id']);
unset($_SESSION['pelanggan_nama']);
unset($_SESSION['pelanggan_meja']);
unset($_SESSION['keranjang']);
session_destroy();
header("Location: ../index.php");
?>