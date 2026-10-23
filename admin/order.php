<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();
$judul = "Data Order";

$filter = $_GET['status'] ?? '';
$where = "";
if ($filter != '') {
    $f = mysqli_real_escape_string($koneksi, $filter);
    $where = "WHERE o.status = '$f'";
}
$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
    JOIN pelanggan p ON o.pelanggan_id = p.id
    $where ORDER BY o.waktu_order DESC");

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
                <li><a href="<?= $base_url; ?>admin/menu.php" class="nav-link"><i class="ph ph-coffee text-lg"></i> Kelola Menu</a></li>
                <li><a href="<?= $base_url; ?>admin/order.php" class="nav-link active"><i class="ph ph-receipt text-lg"></i> Data Order</a></li>
                <li><a href="<?= $base_url; ?>admin/laporan.php" class="nav-link"><i class="ph ph-chart-line-up text-lg"></i> Laporan</a></li>
                <li><a href="<?= $base_url; ?>admin/profil.php" class="nav-link"><i class="ph ph-user-circle text-lg"></i> Profil</a></li>
            </ul>
        </aside>

        <main class="flex-1 p-6 lg:p-8 min-w-0">
            <div class="mb-5">
                <h1 class="font-display text-2xl md:text-3xl font-bold text-berry mb-1">Data Order</h1>
                <p class="text-sm text-berry-soft">Kelola semua pesanan pelanggan</p>
            </div>

            <!-- Filter - SEMUA PAKAI WARNA PALET -->
            <div class="flex flex-wrap gap-2 mb-5">
                <?php
                $tabs = [
                    '' => 'Semua',
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ];
                foreach ($tabs as $val => $label):
                    $active = $filter === $val;
                ?>
                <a href="<?= $base_url; ?>admin/order.php<?= $val ? '?status=' . $val : ''; ?>"
                   class="<?= $active ? 'bg-rose-dusty text-white border-rose-dusty' : 'bg-white text-berry border-border-pink hover:border-rose-dusty' ?> px-3.5 py-1.5 rounded-md border text-sm font-medium transition-colors">
                    <?= $label; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="bg-white rounded-lg border border-border-pink overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-cream-alt">
                            <tr class="text-left">
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Kode</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Pelanggan</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Meja</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Waktu</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-pink">
                            <?php if (mysqli_num_rows($q) == 0): ?>
                            <tr><td colspan="7" class="text-center py-10 text-berry-soft">Belum ada data order.</td></tr>
                            <?php endif; ?>
                            <?php while ($o = mysqli_fetch_assoc($q)): ?>
                            <tr class="hover:bg-cream-alt/50">
                                <td class="px-5 py-3 font-mono text-xs text-berry-soft"><?= $o['kode_order']; ?></td>
                                <td class="px-5 py-3 font-medium text-berry"><?= htmlspecialchars($o['nama']); ?></td>
                                <td class="px-5 py-3 text-berry"><?= $o['no_meja']; ?></td>
                                <td class="px-5 py-3 font-semibold text-berry"><?= rupiah($o['total_harga']); ?></td>
                                <td class="px-5 py-3">
                                    <span class="badge-status" data-status="<?= $o['status']; ?>">
                                        <span class="dot"></span>
                                        <?= ucfirst($o['status']); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-berry-soft text-xs"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                                <td class="px-5 py-3 text-center">
                                    <a href="<?= $base_url; ?>admin/order_detail.php?id=<?= $o['id']; ?>"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-blush-light text-rose-dusty hover:bg-rose-dusty hover:text-white transition-colors text-xs font-medium">
                                        <i class="ph ph-eye"></i> Detail
                                    </a>
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