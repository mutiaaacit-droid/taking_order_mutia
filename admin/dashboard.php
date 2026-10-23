<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();
$judul = "Dashboard Admin";

$total_menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as j FROM menu"))['j'];
$total_order = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as j FROM orders"))['j'];
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as j FROM orders WHERE status='pending'"))['j'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) as j FROM orders WHERE status='selesai'"))['j'];

$recent_orders = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
    JOIN pelanggan p ON o.pelanggan_id = p.id
    ORDER BY o.waktu_order DESC LIMIT 5");

include '../includes/header.php';
?>

<div class="min-h-screen bg-cream">

    <!-- Admin Navbar -->
    <nav class="bg-white border-b border-border-pink py-3 px-6 sticky top-0 z-50">
        <div class="flex items-center justify-between">
            <a href="<?= $base_url; ?>admin/dashboard.php" class="flex items-center gap-3">
                <img src="<?= $base_url; ?>assets/logo-brand.png" alt="Ruang Teduh" class="h-10 w-auto object-contain">
                <span class="hidden md:inline text-[10px] uppercase tracking-[2px] text-berry-soft font-semibold pl-3 border-l border-border-pink">Admin Panel</span>
            </a>
            <div class="flex items-center gap-3">
                <span class="text-sm text-berry hidden sm:block"><?= htmlspecialchars($_SESSION['admin_nama']); ?></span>
                <a href="<?= $base_url; ?>admin/logout.php"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-border-pink text-sm text-berry-soft hover:border-rose-dusty hover:text-rose-dusty transition-colors">
                    <i class="ph ph-sign-out"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar-admin w-60 flex-shrink-0 hidden md:block">
            <p class="text-[10px] uppercase tracking-wider text-white/40 font-semibold mb-3 px-4">Menu Utama</p>
            <ul class="space-y-1">
                <li><a href="<?= $base_url; ?>admin/dashboard.php" class="nav-link active">
                    <i class="ph ph-house text-lg"></i> Dashboard
                </a></li>
                <li><a href="<?= $base_url; ?>admin/kategori.php" class="nav-link">
                    <i class="ph ph-tag text-lg"></i> Kategori
                </a></li>
                <li><a href="<?= $base_url; ?>admin/menu.php" class="nav-link">
                    <i class="ph ph-coffee text-lg"></i> Kelola Menu
                </a></li>
                <li><a href="<?= $base_url; ?>admin/order.php" class="nav-link">
                    <i class="ph ph-receipt text-lg"></i> Data Order
                </a></li>
                <li><a href="<?= $base_url; ?>admin/laporan.php" class="nav-link">
                    <i class="ph ph-chart-line-up text-lg"></i> Laporan
                </a></li>
            </ul>

            <p class="text-[10px] uppercase tracking-wider text-white/40 font-semibold mb-3 mt-6 px-4">Akun</p>
            <ul class="space-y-1">
                <li><a href="<?= $base_url; ?>admin/profil.php" class="nav-link">
                    <i class="ph ph-user-circle text-lg"></i> Profil Admin
                </a></li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="flex-1 p-6 lg:p-8 min-w-0">
            <div class="mb-6">
                <h1 class="font-display text-2xl md:text-3xl font-bold text-berry mb-1">Dashboard</h1>
                <p class="text-sm text-berry-soft">Ringkasan aktivitas Ruang Teduh Cafe</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="stat-card">
                    <div class="stat-card__icon"><i class="ph ph-coffee"></i></div>
                    <p class="text-xs text-berry-soft mb-1">Total Menu</p>
                    <p class="text-2xl font-bold text-berry"><?= $total_menu; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon"><i class="ph ph-receipt"></i></div>
                    <p class="text-xs text-berry-soft mb-1">Total Order</p>
                    <p class="text-2xl font-bold text-berry"><?= $total_order; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon"><i class="ph ph-clock"></i></div>
                    <p class="text-xs text-berry-soft mb-1">Order Pending</p>
                    <p class="text-2xl font-bold text-berry"><?= $total_pending; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon"><i class="ph ph-currency-circle-dollar"></i></div>
                    <p class="text-xs text-berry-soft mb-1">Pendapatan</p>
                    <p class="text-lg font-bold text-berry"><?= rupiah($total_pendapatan); ?></p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg border border-border-pink overflow-hidden">
                <div class="p-5 border-b border-border-pink flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold text-berry">Order Terbaru</h2>
                    <a href="<?= $base_url; ?>admin/order.php" class="text-sm text-rose-dusty hover:text-rose-dark font-medium inline-flex items-center gap-1">
                        Lihat Semua <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-pink">
                            <?php while ($o = mysqli_fetch_assoc($recent_orders)): ?>
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