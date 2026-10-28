<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();
$judul = "Laporan Order";

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
    JOIN pelanggan p ON o.pelanggan_id = p.id
    WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
    ORDER BY o.waktu_order DESC");

$total_pendapatan = 0;
$data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] == 'selesai') $total_pendapatan += $row['total_harga'];
}

include '../includes/header.php';
?>
<div class="min-h-screen bg-cream">
    <nav class="bg-white border-b border-border-pink py-3 px-6 sticky top-0 z-50">
        <div class="flex items-center justify-between">
            <a href="<?= $base_url; ?>admin/dashboard.php" class="flex items-center gap-3">
                <img src="<?= $base_url; ?>assets/logo-brand.png" alt="Ruang Teduh" class="h-10 w-auto object-contain">
                <span class="hidden md:inline text-[10px] uppercase tracking-[2px] text-berry-soft font-semibold pl-3 border-l border-border-pink">Admin Panel</span>
            </a>
            <a href="<?= $base_url; ?>admin/logout.php" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-border-pink text-sm text-berry-soft hover:border-rose-dusty hover:text-rose-dusty transition-colors">
                <i class="ph ph-sign-out"></i> Logout
            </a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-6 lg:p-8">
        <h1 class="font-display text-2xl md:text-3xl font-bold text-berry mb-5">Laporan Order</h1>

        <form method="GET" class="bg-white rounded-lg border border-border-pink p-5 mb-5">
            <div class="grid sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Dari</label>
                    <input type="date" name="tgl_awal" class="input-cafe" value="<?= $tgl_awal; ?>">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Sampai</label>
                    <input type="date" name="tgl_akhir" class="input-cafe" value="<?= $tgl_akhir; ?>">
                </div>
                <div class="flex items-end">
                    <button class="btn-primary-cafe w-full">
                        <i class="ph ph-magnifying-glass"></i> Filter
                    </button>
                </div>
                <div class="flex items-end gap-2">
                    <a href="<?= $base_url; ?>admin/cetak_laporan.php?tgl_awal=<?= $tgl_awal; ?>&tgl_akhir=<?= $tgl_akhir; ?>" target="_blank"
                       class="btn-outline-cafe flex-1 !px-2 !text-xs">
                        <i class="ph ph-printer"></i> Cetak
                    </a>
                </div>
            </div>
        </form>

        <div class="bg-rose-dusty text-white rounded-lg p-6 mb-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-blush mb-1 uppercase tracking-wider font-semibold">Total Pendapatan (Selesai)</p>
                    <p class="text-2xl md:text-3xl font-bold"><?= rupiah($total_pendapatan); ?></p>
                </div>
                <i class="ph ph-currency-circle-dollar text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-border-pink overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-cream-alt">
                        <tr class="text-left">
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">No</th>
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Pelanggan</th>
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Meja</th>
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-pink">
                        <?php $no = 1; foreach ($data as $o): ?>
                        <tr class="hover:bg-cream-alt/50">
                            <td class="px-5 py-3 text-berry-soft"><?= $no++; ?></td>
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
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>