<?php
require_once '../config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['gambar'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$result = uploadGambar($_FILES['gambar']);

if ($result['success']) {
    echo json_encode([
        'success' => true,
        'filename' => $result['filename'],
        'url' => '../assets/uploads/menu/' . $result['filename']
    ]);
} else {
    echo json_encode($result);
}
?>