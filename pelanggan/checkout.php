<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit;
}
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) {
    header("Location: keranjang.php");
    exit;
}
$total = 0;
$items = [];
foreach ($keranjang as $mid => $jml) {
    $q = mysqli_query($koneksi, "SELECT m.*, k.nama_kategori FROM menu m
        JOIN kategori k ON m.kategori_id = k.id WHERE m.id = $mid");
    $m = mysqli_fetch_assoc($q);
    if ($m) {
        $sub = $m['harga'] * $jml;
        $total += $sub;
        $items[] = ['menu' => $m, 'jumlah' => $jml, 'subtotal' => $sub];
    }
}
$pajak = $total * 0.1;
$grandTotal = $total + $pajak;

$judul = "Checkout - Ruang Teduh Cafe";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">

    <div class="flex items-center gap-3 mb-6">
        <a href="<?= $base_url; ?>pelanggan/keranjang.php"
           class="w-10 h-10 rounded-md bg-white border border-border-pink flex items-center justify-center text-berry hover:border-rose-dusty hover:text-rose-dusty transition-colors">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-berry">Konfirmasi Pesanan</h1>
            <p class="text-sm text-berry-soft">Periksa kembali pesanan Anda</p>
        </div>
    </div>

    <form action="<?= $base_url; ?>proses/simpan_order.php" method="POST">
        <div class="grid lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-4">

                <!-- Data Pelanggan -->
                <div class="bg-white rounded-lg border border-border-pink p-6">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-md bg-blush flex items-center justify-center">
                            <i class="ph ph-user text-lg text-rose-dusty"></i>
                        </div>
                        <h2 class="font-display text-lg font-bold text-berry">Data Pelanggan</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-berry-soft mb-0.5">Nama</p>
                            <p class="font-semibold text-berry"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-berry-soft mb-0.5">No. Meja</p>
                            <p class="font-semibold text-berry"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-white rounded-lg border border-border-pink p-6">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-md bg-blush flex items-center justify-center">
                            <i class="ph ph-note-pencil text-lg text-rose-dusty"></i>
                        </div>
                        <h2 class="font-display text-lg font-bold text-berry">Catatan Tambahan</h2>
                    </div>
                    <textarea name="catatan" rows="3" class="input-cafe"
                              placeholder="Contoh: tanpa gula, extra shot, dll (opsional)"></textarea>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-border-pink p-6 sticky top-24">
                    <h2 class="font-display text-lg font-bold text-berry mb-4">Rincian</h2>

                    <div class="space-y-3 mb-4 max-h-72 overflow-y-auto pr-1">
                        <?php foreach ($items as $item): ?>
                        <div class="flex justify-between gap-2 text-sm">
                            <div class="min-w-0">
                                <p class="font-semibold text-berry truncate"><?= htmlspecialchars($item['menu']['nama_menu']); ?></p>
                                <p class="text-xs text-berry-soft"><?= $item['jumlah']; ?> × <?= rupiah($item['menu']['harga']); ?></p>
                            </div>
                            <span class="font-semibold text-berry shrink-0"><?= rupiah($item['subtotal']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t border-border-pink pt-3 space-y-2 mb-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-berry-soft">Subtotal</span>
                            <span class="font-semibold text-berry"><?= rupiah($total); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-berry-soft">Pajak (10%)</span>
                            <span class="font-semibold text-berry"><?= rupiah($pajak); ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-5 pt-3 border-t border-border-pink">
                        <span class="font-bold text-berry">Total</span>
                        <span class="text-xl font-bold text-rose-dusty"><?= rupiah($grandTotal); ?></span>
                    </div>

                    <button type="submit" class="btn-primary-cafe w-full !py-3">
                        <i class="ph ph-paper-plane-tilt"></i>
                        Kirim Pesanan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>