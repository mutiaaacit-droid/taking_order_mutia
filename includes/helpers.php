<?php
/**
 * Helper functions untuk Ruang Teduh Cafe
 */

if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return "Rp " . number_format((float)$angka, 0, ',', '.');
    }
}

if (!function_exists('generateKodeOrder')) {
    function generateKodeOrder() {
        return "RTD-" . date("YmdHis") . "-" . rand(100, 999);
    }
}

if (!function_exists('slugify')) {
    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}

if (!function_exists('uploadGambar')) {
    function uploadGambar($file, $targetDir = null) {
        if ($targetDir === null) {
            $targetDir = __DIR__ . '/../assets/uploads/menu/';
        }
        
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File tidak valid'];
        }
        
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed)) {
            return ['success' => false, 'message' => 'Format harus JPG, PNG, atau WebP'];
        }
        
        if ($file['size'] > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'Ukuran maksimal 2MB'];
        }
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'menu_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetPath = $targetDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'message' => 'Gagal upload file'];
    }
}

if (!function_exists('hapusGambar')) {
    function hapusGambar($filename, $dir = null) {
        if ($dir === null) {
            $dir = __DIR__ . '/../assets/uploads/menu/';
        }
        if ($filename && file_exists($dir . $filename)) {
            return unlink($dir . $filename);
        }
        return false;
    }
}

if (!function_exists('tanggalIndo')) {
    function tanggalIndo($date, $withTime = false) {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $ts = strtotime($date);
        $hasil = date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
        if ($withTime) $hasil .= ' ' . date('H:i', $ts);
        return $hasil;
    }
}

if (!function_exists('statusBadge')) {
    function statusBadge($status) {
        $map = [
            'pending'    => ['class' => 'badge-cafe-warning', 'label' => 'Pending'],
            'diproses'   => ['class' => 'badge-cafe-info',    'label' => 'Diproses'],
            'selesai'    => ['class' => 'badge-cafe-success', 'label' => 'Selesai'],
            'dibatalkan' => ['class' => 'badge-cafe-danger',  'label' => 'Dibatalkan'],
        ];
        return $map[$status] ?? ['class' => 'badge-cafe-primary', 'label' => ucfirst($status)];
    }
}
?>