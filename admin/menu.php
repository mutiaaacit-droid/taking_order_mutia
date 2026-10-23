<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();
$judul = "Kelola Menu";

$q = mysqli_query($koneksi, "SELECT m.*, k.nama_kategori FROM menu m
    JOIN kategori k ON m.kategori_id = k.id
    ORDER BY k.urutan, m.nama_menu");

include '../includes/header.php';
?>
<div class="min-h-screen bg-cream">
    <nav class="bg-white border-b border-border-pink py-3 px-6 sticky top-0 z-50">
        <div class="flex items-center justify-between">
            <a href="<?= $base_url; ?>admin/dashboard.php" class="flex items-center gap-3">
                <img src="<?= $base_url; ?>assets/logo-brand.png" alt="Ruang Teduh" class="h-10 w-auto object-contain">
                <span class="hidden md:inline text-[10px] uppercase tracking-[2px] text-berry-soft font-semibold pl-3 border-l border-border-pink">Admin Panel</span>
            </a>
            <div class="flex items-center gap-3">
                <span class="text-sm text-berry hidden sm:block"><?= htmlspecialchars($_SESSION['admin_nama']); ?></span>
                <a href="<?= $base_url; ?>admin/logout.php" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-border-pink text-sm text-berry-soft hover:border-rose-dusty hover:text-rose-dusty transition-colors">
                    <i class="ph ph-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="flex">
        <aside class="sidebar-admin w-60 flex-shrink-0 hidden md:block">
            <ul class="space-y-1">
                <li><a href="<?= $base_url; ?>admin/dashboard.php" class="nav-link"><i class="ph ph-house text-lg"></i> Dashboard</a></li>
                <li><a href="<?= $base_url; ?>admin/kategori.php" class="nav-link"><i class="ph ph-tag text-lg"></i> Kategori</a></li>
                <li><a href="<?= $base_url; ?>admin/menu.php" class="nav-link active"><i class="ph ph-coffee text-lg"></i> Kelola Menu</a></li>
                <li><a href="<?= $base_url; ?>admin/order.php" class="nav-link"><i class="ph ph-receipt text-lg"></i> Data Order</a></li>
                <li><a href="<?= $base_url; ?>admin/laporan.php" class="nav-link"><i class="ph ph-chart-line-up text-lg"></i> Laporan</a></li>
                <li><a href="<?= $base_url; ?>admin/profil.php" class="nav-link"><i class="ph ph-user-circle text-lg"></i> Profil</a></li>
            </ul>
        </aside>

        <main class="flex-1 p-6 lg:p-8 min-w-0">
            <div class="flex items-center justify-between mb-5 gap-3 flex-wrap">
                <div>
                    <h1 class="font-display text-2xl md:text-3xl font-bold text-berry mb-1">Kelola Menu</h1>
                    <p class="text-sm text-berry-soft">Manajemen katalog menu cafe</p>
                </div>
                <a href="<?= $base_url; ?>admin/menu_tambah.php" class="btn-primary-cafe">
                    <i class="ph ph-plus"></i> Tambah Menu
                </a>
            </div>

            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="alert-cafe alert-cafe-success alert-auto-hide mb-5"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
            <?php endif; ?>

            <div class="bg-white rounded-lg border border-border-pink overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-cream-alt">
                            <tr class="text-left">
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Menu</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Kategori</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Harga</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Stok</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-pink">
                            <?php while ($m = mysqli_fetch_assoc($q)): ?>
                            <tr class="hover:bg-cream-alt/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-md overflow-hidden flex-shrink-0 bg-blush-light flex items-center justify-center">
                                            <?php if (!empty($m['gambar']) && file_exists(__DIR__ . '/../assets/uploads/menu/' . $m['gambar'])): ?>
                                                <img src="<?= $base_url; ?>assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="ph ph-image text-rose-soft"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-berry"><?= htmlspecialchars($m['nama_menu']); ?></p>
                                            <?php if ($m['is_featured']): ?>
                                                <span class="inline-flex items-center gap-1 text-[10px] text-rose-dusty font-semibold">
                                                    <i class="ph ph-star-fill"></i> Favorit
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3"><span class="badge-cafe badge-cafe-primary"><?= htmlspecialchars($m['nama_kategori']); ?></span></td>
                                <td class="px-5 py-3 font-semibold text-berry"><?= rupiah($m['harga']); ?></td>
                                <td class="px-5 py-3 text-berry"><?= $m['stok']; ?></td>
                                <td class="px-5 py-3">
                                    <span class="badge-status" data-status="<?= $m['status'] == 'tersedia' ? 'selesai' : ($m['status'] == 'habis' ? 'pending' : 'dibatalkan'); ?>">
                                        <span class="dot"></span>
                                        <?= $m['status'] == 'tersedia' ? 'Tersedia' : ($m['status'] == 'habis' ? 'Habis' : 'Nonaktif'); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-1.5 justify-center">
                                        <a href="<?= $base_url; ?>admin/menu_edit.php?id=<?= $m['id']; ?>"
                                           class="w-8 h-8 rounded-md bg-blush-light flex items-center justify-center text-rose-dusty hover:bg-rose-dusty hover:text-white transition-colors"
                                           title="Edit">
                                            <i class="ph ph-pencil-simple text-sm"></i>
                                        </a>
                                        <a href="<?= $base_url; ?>admin/menu_hapus.php?id=<?= $m['id']; ?>"
                                           onclick="return confirm('Hapus menu ini?')"
                                           class="w-8 h-8 rounded-md bg-red-50 flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition-colors"
                                           title="Hapus">
                                            <i class="ph ph-trash text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>