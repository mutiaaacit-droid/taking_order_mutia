<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
    JOIN pelanggan p ON o.pelanggan_id = p.id
    WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
    ORDER BY o.waktu_order ASC");

$total_pendapatan = 0;
$total_order = 0;
$data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    $total_order++;
    if ($row['status'] == 'selesai') $total_pendapatan += $row['total_harga'];
}

$judul = "Cetak Laporan - Ruang Teduh Cafe";
include '../includes/header.php';
?>

<!-- Tombol Aksi (disembunyikan saat print) -->
<div class="no-print bg-white border-b border-border-pink py-3 px-6 sticky top-0 z-50">
    <div class="max-w-5xl mx-auto flex items-center justify-between gap-3 flex-wrap">
        <a href="<?= $base_url; ?>admin/laporan.php?tgl_awal=<?= urlencode($tgl_awal); ?>&tgl_akhir=<?= urlencode($tgl_akhir); ?>"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-md border border-border-pink text-sm text-berry-soft hover:border-rose-dusty hover:text-rose-dusty transition-colors">
            <i class="ph ph-arrow-left"></i> Kembali ke Laporan
        </a>
        <button onclick="window.print()" class="btn-primary-cafe">
            <i class="ph ph-printer"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- Area Laporan (yang akan dicetak) -->
<div class="bg-cream py-8 px-4 print:bg-white print:py-0">
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-10 shadow-sm border border-border-pink print:shadow-none print:border-0 print:p-0 print:max-w-none">

        <!-- Header Laporan -->
        <div class="text-center border-b-2 border-double border-berry pb-4 mb-5">
            <img src="<?= $base_url; ?>assets/logo-brand.png"
                 alt="Ruang Teduh Cafe"
                 class="h-16 w-auto object-contain mx-auto mb-2">
            <h1 class="font-display text-xl font-bold tracking-widest text-berry mb-1">RUANG TEDUH CAFE</h1>
            <p class="text-xs text-berry-soft">Jl. Contoh Alamat No. 123, Kota Anda</p>
            <p class="text-xs text-berry-soft">Telp: 021-1234567 &middot; Email: info@ruangteduh.com</p>
        </div>

        <!-- Judul Laporan -->
        <h2 class="font-display text-center text-lg font-bold text-berry mb-4 uppercase tracking-wider">Laporan Order</h2>

        <!-- Info Periode -->
        <div class="mb-4 text-sm text-berry">
            <table class="w-auto">
                <tr>
                    <td class="pr-2 py-0.5 align-top">Periode</td>
                    <td class="pr-2 py-0.5 align-top">:</td>
                    <td class="py-0.5"><?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></td>
                </tr>
                <tr>
                    <td class="pr-2 py-0.5 align-top">Tanggal Cetak</td>
                    <td class="pr-2 py-0.5 align-top">:</td>
                    <td class="py-0.5"><?= date('d/m/Y H:i'); ?></td>
                </tr>
                <tr>
                    <td class="pr-2 py-0.5 align-top">Total Order</td>
                    <td class="pr-2 py-0.5 align-top">:</td>
                    <td class="py-0.5"><?= $total_order; ?> transaksi</td>
                </tr>
            </table>
        </div>

        <!-- Tabel Data -->
        <table class="w-full text-xs border-collapse">
            <thead>
                <tr class="bg-blush-light">
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-left" style="width: 32px;">No</th>
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-left">Kode Order</th>
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-left">Nama Pelanggan</th>
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-center" style="width: 55px;">Meja</th>
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-right" style="width: 100px;">Total</th>
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-center" style="width: 75px;">Status</th>
                    <th class="border border-berry/60 px-2 py-2 text-berry font-semibold text-center" style="width: 110px;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="7" class="border border-berry/60 px-2 py-6 text-center text-berry-soft">
                            Tidak ada data pada periode ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($data as $o): ?>
                    <tr class="<?= $no % 2 === 0 ? 'bg-cream-alt' : ''; ?>">
                        <td class="border border-berry/60 px-2 py-1.5 text-center text-berry"><?= $no++; ?></td>
                        <td class="border border-berry/60 px-2 py-1.5 font-mono text-[11px] text-berry"><?= htmlspecialchars($o['kode_order']); ?></td>
                        <td class="border border-berry/60 px-2 py-1.5 text-berry"><?= htmlspecialchars($o['nama']); ?></td>
                        <td class="border border-berry/60 px-2 py-1.5 text-center text-berry"><?= htmlspecialchars($o['no_meja']); ?></td>
                        <td class="border border-berry/60 px-2 py-1.5 text-right text-berry"><?= rupiah($o['total_harga']); ?></td>
                        <td class="border border-berry/60 px-2 py-1.5 text-center text-berry"><?= ucfirst($o['status']); ?></td>
                        <td class="border border-berry/60 px-2 py-1.5 text-center text-berry text-[11px]"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="bg-blush-light">
                    <td colspan="4" class="border border-berry/60 px-2 py-2 text-right font-bold text-berry">Total Pendapatan (Order Selesai)</td>
                    <td colspan="3" class="border border-berry/60 px-2 py-2 font-bold text-berry"><?= rupiah($total_pendapatan); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Tanda Tangan -->
        <div class="mt-10 text-right text-sm text-berry">
            <p>Kota Anda, <?= date('d/m/Y'); ?></p>
            <p class="mt-1">Administrator,</p>
            <p class="mt-20 inline-block border-t border-berry pt-1 min-w-[180px] text-center font-semibold">
                <?= htmlspecialchars($_SESSION['admin_nama']); ?>
            </p>
        </div>
    </div>
</div>

<!-- Style tambahan khusus print -->
<style>
    @media print {
        @page { size: A4; margin: 15mm; }
        .no-print { display: none !important; }
        body { background: white !important; }
        .print\:bg-white { background: white !important; }
    }
</style>

<?php include '../includes/footer.php'; ?>