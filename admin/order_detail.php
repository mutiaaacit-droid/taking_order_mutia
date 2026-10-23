<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $waktu_selesai = $status === 'selesai' ? "waktu_selesai = NOW()," : "";
    mysqli_query($koneksi, "UPDATE orders SET status='$status', $waktu_selesai waktu_selesai=waktu_selesai WHERE id=$id");
    $_SESSION['pesan'] = "Status order berhasil diperbarui.";
    header("Location: order_detail.php?id=$id");
    exit;
}

$judul = "Detail Order";
$q = mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o
    JOIN pelanggan p ON o.pelanggan_id = p.id WHERE o.id = $id");
$o = mysqli_fetch_assoc($q);
if (!$o) { header("Location: order.php"); exit; }

$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $id");

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
        <div class="flex items-center gap-3 mb-5">
            <a href="<?= $base_url; ?>admin/order.php" class="w-9 h-9 rounded-md bg-white border border-border-pink flex items-center justify-center text-berry hover:border-rose-dusty hover:text-rose-dusty transition-colors">
                <i class="ph ph-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-display text-2xl md:text-3xl font-bold text-berry">Detail Order</h1>
                <p class="text-sm text-berry-soft font-mono"><?= $o['kode_order']; ?></p>
            </div>
        </div>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert-cafe alert-cafe-success alert-auto-hide mb-5"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-5">
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-lg border border-border-pink p-5">
                    <h2 class="font-display text-base font-bold text-berry mb-3">Informasi Pelanggan</h2>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-berry-soft">Nama:</span> <span class="font-medium text-berry"><?= htmlspecialchars($o['nama']); ?></span></div>
                        <div><span class="text-berry-soft">No. HP:</span> <span class="font-medium text-berry"><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></span></div>
                        <div><span class="text-berry-soft">No. Meja:</span> <span class="font-medium text-berry"><?= htmlspecialchars($o['no_meja']); ?></span></div>
                        <div><span class="text-berry-soft">Waktu:</span> <span class="font-medium text-berry"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></span></div>
                        <div><span class="text-berry-soft">Catatan:</span> <span class="text-berry"><?= htmlspecialchars($o['catatan'] ?: '-'); ?></span></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-border-pink p-5">
                    <h2 class="font-display text-base font-bold text-berry mb-3">Update Status</h2>
                    <form method="POST">
                        <select name="status" class="input-cafe mb-3">
                            <option value="pending" <?= $o['status']=='pending'?'selected':''; ?>>Pending</option>
                            <option value="diproses" <?= $o['status']=='diproses'?'selected':''; ?>>Diproses</option>
                            <option value="selesai" <?= $o['status']=='selesai'?'selected':''; ?>>Selesai</option>
                            <option value="dibatalkan" <?= $o['status']=='dibatalkan'?'selected':''; ?>>Dibatalkan</option>
                        </select>
                        <button class="btn-primary-cafe w-full">
                            <i class="ph ph-check"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-border-pink overflow-hidden">
                    <div class="p-5 border-b border-border-pink">
                        <h2 class="font-display text-base font-bold text-berry">Rincian Item</h2>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-cream-alt">
                            <tr class="text-left">
                                <th class="px-5 py-2.5 font-semibold text-berry text-xs uppercase">Menu</th>
                                <th class="px-5 py-2.5 font-semibold text-berry text-xs uppercase text-right">Harga</th>
                                <th class="px-5 py-2.5 font-semibold text-berry text-xs uppercase text-center">Qty</th>
                                <th class="px-5 py-2.5 font-semibold text-berry text-xs uppercase text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-pink">
                            <?php while ($d = mysqli_fetch_assoc($qd)): ?>
                            <tr>
                                <td class="px-5 py-3 font-medium text-berry"><?= htmlspecialchars($d['nama_menu']); ?></td>
                                <td class="px-5 py-3 text-right text-berry"><?= rupiah($d['harga']); ?></td>
                                <td class="px-5 py-3 text-center text-berry"><?= $d['jumlah']; ?></td>
                                <td class="px-5 py-3 text-right font-semibold text-berry"><?= rupiah($d['subtotal']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="bg-cream-alt">
                            <tr>
                                <th colspan="3" class="px-5 py-3 text-right text-berry">Total</th>
                                <th class="px-5 py-3 text-right text-rose-dusty text-lg"><?= rupiah($o['total_harga']); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>