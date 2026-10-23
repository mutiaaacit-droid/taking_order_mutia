<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit;
}
$judul = "Menu - Ruang Teduh Cafe";
include '../includes/header.php';
include '../includes/navbar.php';

$kategori_filter = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;
$where = "WHERE m.status = 'tersedia'";
if ($kategori_filter > 0) {
    $where .= " AND m.kategori_id = $kategori_filter";
}
$query = mysqli_query($koneksi, "SELECT m.*, k.nama_kategori FROM menu m
    JOIN kategori k ON m.kategori_id = k.id
    $where ORDER BY k.urutan, m.nama_menu");
$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori WHERE is_active = 1 ORDER BY urutan");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
        <div>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-berry mb-1">Menu Pilihan</h1>
            <p class="text-berry-soft text-sm">Jelajahi aneka hidangan lezat yang kami sediakan</p>
        </div>
        <a href="<?= $base_url; ?>pelanggan/keranjang.php" class="btn-primary-cafe shrink-0">
            <i class="ph ph-shopping-cart"></i>
            Lihat Keranjang
        </a>
    </div>

    <!-- Alert -->
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert-cafe alert-cafe-success alert-auto-hide mb-6">
            <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
        </div>
    <?php endif; ?>

    <!-- Filter Kategori -->
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="<?= $base_url; ?>pelanggan/menu.php"
           class="<?= $kategori_filter == 0 ? 'bg-rose-dusty text-white border-rose-dusty' : 'bg-white text-berry border-border-pink hover:border-rose-dusty' ?> px-4 py-2 rounded-md border text-sm font-medium transition-colors">
            Semua
        </a>
        <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
            <a href="<?= $base_url; ?>pelanggan/menu.php?kategori=<?= $k['id']; ?>"
               class="<?= $kategori_filter == $k['id'] ? 'bg-rose-dusty text-white border-rose-dusty' : 'bg-white text-berry border-border-pink hover:border-rose-dusty' ?> px-4 py-2 rounded-md border text-sm font-medium transition-colors">
                <?= htmlspecialchars($k['nama_kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Grid Menu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-6">
        <?php if (mysqli_num_rows($query) == 0): ?>
            <div class="col-span-full text-center py-16">
                <i class="ph ph-package text-6xl text-border-pink"></i>
                <p class="text-berry-soft mt-3">Belum ada menu tersedia.</p>
            </div>
        <?php endif; ?>

        <?php while ($m = mysqli_fetch_assoc($query)): ?>
            <div class="menu-card">
                <div class="menu-card__image">
                    <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/menu/' . $m['gambar'])): ?>
                        <img src="<?= $base_url; ?>assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>"
                             alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                             loading="lazy">
                    <?php else: ?>
                        <div class="menu-card__image-placeholder">
                            <i class="ph ph-image"></i>
                        </div>
                    <?php endif; ?>

                    <?php if ($m['is_featured']): ?>
                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white/95 text-rose-dusty text-xs font-bold shadow-sm">
                            <i class="ph ph-star-fill"></i>
                            Favorit
                        </span>
                    <?php endif; ?>
                </div>

                <div class="menu-card__body">
                    <span class="badge-cafe badge-cafe-primary mb-2 self-start"><?= htmlspecialchars($m['nama_kategori']); ?></span>
                    <h3 class="menu-card__title"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                    <p class="text-sm text-berry-soft line-clamp-2 mb-3"><?= htmlspecialchars($m['deskripsi']); ?></p>
                    <p class="text-xs text-berry-soft mb-3">Stok: <?= $m['stok']; ?></p>
                    <div class="menu-card__price mb-3"><?= rupiah($m['harga']); ?></div>

                    <form action="<?= $base_url; ?>proses/tambah_keranjang.php" method="POST" class="mt-auto flex gap-2">
                        <input type="hidden" name="menu_id" value="<?= $m['id']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" max="<?= $m['stok']; ?>"
                               class="w-16 px-2 py-2 border border-border-pink rounded-md text-center text-sm focus:outline-none focus:border-rose-dusty focus:ring-2 focus:ring-rose-dusty/10">
                        <button type="submit" class="btn-primary-cafe flex-1 !py-2 !text-xs">
                            <i class="ph ph-plus"></i>
                            Tambah
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>