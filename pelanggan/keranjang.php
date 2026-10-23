<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit;
}
$judul = "Keranjang - Ruang Teduh Cafe";
include '../includes/header.php';
include '../includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= $base_url; ?>pelanggan/menu.php"
           class="w-10 h-10 rounded-md bg-white border border-border-pink flex items-center justify-center text-berry hover:border-rose-dusty hover:text-rose-dusty transition-colors">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-berry">Keranjang</h1>
            <p class="text-sm text-berry-soft"><?= count($keranjang); ?> item</p>
        </div>
    </div>

    <?php if (empty($keranjang)): ?>
        <div class="bg-white rounded-lg border border-border-pink p-10 md:p-14 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-blush flex items-center justify-center mb-4">
                <i class="ph ph-shopping-cart text-3xl text-rose-dusty"></i>
            </div>
            <h2 class="font-display text-xl font-bold text-berry mb-2">Keranjang masih kosong</h2>
            <p class="text-berry-soft text-sm mb-6">Ayo pilih menu favorit Anda terlebih dahulu</p>
            <a href="<?= $base_url; ?>pelanggan/menu.php" class="btn-primary-cafe inline-flex">
                <i class="ph ph-fork-knife"></i>
                Lihat Menu
            </a>
        </div>
    <?php else: ?>
        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Items -->
            <div class="lg:col-span-2 space-y-3">
                <?php foreach ($keranjang as $mid => $jml):
                    $q = mysqli_query($koneksi, "SELECT m.*, k.nama_kategori FROM menu m
                        JOIN kategori k ON m.kategori_id = k.id WHERE m.id = $mid");
                    $m = mysqli_fetch_assoc($q);
                    if (!$m) continue;
                    $sub = $m['harga'] * $jml;
                    $total += $sub;
                ?>
                <div class="bg-white rounded-lg border border-border-pink p-4 flex gap-4">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-md overflow-hidden flex-shrink-0 bg-blush-light flex items-center justify-center">
                        <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/menu/' . $m['gambar'])): ?>
                            <img src="<?= $base_url; ?>assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>"
                                 alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="ph ph-image text-3xl text-rose-soft"></i>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <div class="min-w-0">
                                <p class="text-xs text-rose-dusty font-semibold"><?= htmlspecialchars($m['nama_kategori']); ?></p>
                                <h3 class="font-bold text-berry truncate"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                            </div>
                            <a href="<?= $base_url; ?>proses/hapus_keranjang.php?id=<?= $mid; ?>"
                               class="w-8 h-8 rounded-md flex items-center justify-center text-berry-soft hover:bg-red-50 hover:text-red-600 transition-colors shrink-0">
                                <i class="ph ph-trash text-base"></i>
                            </a>
                        </div>
                        <p class="text-xs text-berry-soft mb-2"><?= rupiah($m['harga']); ?> / item</p>

                        <div class="flex items-center justify-between">
                            <form action="<?= $base_url; ?>proses/update_keranjang.php" method="POST" class="flex items-center gap-2">
                                <input type="hidden" name="menu_id" value="<?= $mid; ?>">
                                <input type="number" name="jumlah" value="<?= $jml; ?>" min="1" max="<?= $m['stok']; ?>"
                                       class="w-16 px-2 py-1.5 border border-border-pink rounded-md text-center text-sm focus:outline-none focus:border-rose-dusty"
                                       onchange="this.form.submit()">
                            </form>
                            <span class="font-bold text-rose-dusty"><?= rupiah($sub); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-border-pink p-6 sticky top-24">
                    <h2 class="font-display text-lg font-bold text-berry mb-4">Ringkasan</h2>

                    <div class="space-y-2 mb-4 pb-4 border-b border-border-pink">
                        <div class="flex justify-between text-sm">
                            <span class="text-berry-soft">Subtotal</span>
                            <span class="font-semibold text-berry"><?= rupiah($total); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-berry-soft">Pajak (10%)</span>
                            <span class="font-semibold text-berry"><?= rupiah($total * 0.1); ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-5">
                        <span class="font-bold text-berry">Total</span>
                        <span class="text-xl font-bold text-rose-dusty"><?= rupiah($total * 1.1); ?></span>
                    </div>

                    <a href="<?= $base_url; ?>pelanggan/checkout.php" class="btn-primary-cafe w-full">
                        <i class="ph ph-check-circle"></i>
                        Checkout
                    </a>
                    <a href="<?= $base_url; ?>pelanggan/menu.php" class="btn-outline-cafe w-full mt-2">
                        <i class="ph ph-arrow-left"></i>
                        Lanjut Pesan
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>