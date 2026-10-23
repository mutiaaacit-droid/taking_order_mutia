<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id']) || !isset($_SESSION['last_order_id'])) {
    header("Location: ../index.php");
    exit;
}

$oid = (int)$_SESSION['last_order_id'];
$q_order = mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o
    JOIN pelanggan p ON o.pelanggan_id = p.id WHERE o.id = $oid");
$order = mysqli_fetch_assoc($q_order);

if (!$order) {
    header("Location: ../index.php");
    exit;
}

$q_detail = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $oid");
$detail = [];
while ($d = mysqli_fetch_assoc($q_detail)) $detail[] = $d;

$judul = "Struk Pesanan - Ruang Teduh Cafe";
include '../includes/header.php';
?>

<div class="min-h-screen bg-cream py-8 md:py-10 px-4">
    <div class="max-w-md mx-auto">

        <!-- Sukses -->
        <div class="text-center mb-6 no-print">
            <div class="w-16 h-16 mx-auto rounded-full bg-blush flex items-center justify-center mb-3">
                <i class="ph ph-check-circle text-4xl text-rose-dusty"></i>
            </div>
            <h1 class="font-display text-2xl font-bold text-berry mb-1">Pesanan Berhasil</h1>
            <p class="text-sm text-berry-soft">Tunjukkan struk ini ke kasir atau pelayan</p>
        </div>

        <!-- Struk -->
        <div class="struk-wrapper" id="strukArea">
            <!-- Logo Brand di Struk -->
            <div class="text-center mb-3">
                <img src="<?= $base_url; ?>assets/logo-brand.png"
                     alt="Ruang Teduh Cafe"
                     class="h-14 w-auto object-contain mx-auto mb-1">
                <p class="text-[11px] text-berry-soft">Jl. Contoh Alamat No. 123, Kota Anda</p>
                <p class="text-[11px] text-berry-soft">Telp: 021-1234567</p>
            </div>

            <div class="struk-divider"></div>

            <div class="space-y-1 text-xs">
                <div class="row-line"><span>Kode Order</span><span class="font-mono"><?= htmlspecialchars($order['kode_order']); ?></span></div>
                <div class="row-line"><span>Tanggal</span><span><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></span></div>
                <div class="row-line"><span>Nama</span><span><?= htmlspecialchars($order['nama']); ?></span></div>
                <div class="row-line"><span>No. Meja</span><span><?= htmlspecialchars($order['no_meja']); ?></span></div>
            </div>

            <div class="struk-divider"></div>

            <div class="space-y-2 text-xs">
                <?php foreach ($detail as $d): ?>
                <div>
                    <p class="font-semibold text-berry"><?= htmlspecialchars($d['nama_menu']); ?></p>
                    <div class="row-line">
                        <span><?= $d['jumlah']; ?> × <?= number_format($d['harga'], 0, ',', '.'); ?></span>
                        <span><?= number_format($d['subtotal'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="struk-divider"></div>

            <div class="row-line font-bold text-sm">
                <span>TOTAL</span>
                <span class="text-rose-dusty"><?= rupiah($order['total_harga']); ?></span>
            </div>

            <?php if (!empty($order['catatan'])): ?>
            <div class="struk-divider"></div>
            <div class="text-xs">
                <p class="font-bold text-berry mb-1">Catatan:</p>
                <p class="text-berry-soft"><?= nl2br(htmlspecialchars($order['catatan'])); ?></p>
            </div>
            <?php endif; ?>

            <div class="struk-divider"></div>

            <div class="text-center text-xs text-berry-soft">
                <p>Terima kasih atas kunjungan Anda</p>
                <p>Selamat menikmati</p>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex gap-3 mt-6 no-print">
            <a href="<?= $base_url; ?>pelanggan/menu.php" class="btn-outline-cafe flex-1 !text-sm">
                <i class="ph ph-plus"></i>
                Order Lagi
            </a>
            <a href="<?= $base_url; ?>pelanggan/logout_pelanggan.php" class="btn-primary-cafe flex-1 !text-sm">
                <i class="ph ph-sign-out"></i>
                Selesai
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>