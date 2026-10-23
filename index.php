<?php
require_once 'config/koneksi.php';
$judul = "Selamat Datang - Ruang Teduh Cafe";
include 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-cream relative overflow-hidden">
    <!-- Dekorasi halus di background (subtle, bukan norak) -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(247,200,208,0.35) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(181,91,114,0.10) 0%, transparent 70%);"></div>
    </div>

    <div class="relative max-w-6xl w-full grid md:grid-cols-2 gap-10 lg:gap-16 items-center">

        <!-- KIRI - Brand + Tagline -->
        <div class="text-center md:text-left animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/70 border border-border-pink backdrop-blur-sm mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-dusty"></span>
                <span class="text-xs font-semibold tracking-wider uppercase text-berry-soft">Selamat Datang</span>
            </div>

            <div class="mb-6 flex justify-center md:justify-start">
                <img src="<?= $base_url; ?>assets/logo-brand.png"
                     alt="Ruang Teduh Cafe"
                     class="w-52 md:w-64 h-auto object-contain">
            </div>

            <p class="text-berry-soft text-base md:text-lg mb-8 leading-relaxed max-w-md mx-auto md:mx-0">
                Nikmati setiap momen berharga Anda dengan secangkir kopi pilihan dan hidangan lezat di suasana yang hangat dan nyaman.
            </p>

            <ul class="space-y-2.5 inline-block text-left">
                <li class="flex items-center gap-2.5 text-sm text-berry">
                    <i class="ph ph-check-circle text-rose-dusty text-lg"></i>
                    Pemesanan cepat &amp; tanpa antre
                </li>
                <li class="flex items-center gap-2.5 text-sm text-berry">
                    <i class="ph ph-check-circle text-rose-dusty text-lg"></i>
                    Menu pilihan dengan bahan premium
                </li>
                <li class="flex items-center gap-2.5 text-sm text-berry">
                    <i class="ph ph-check-circle text-rose-dusty text-lg"></i>
                    Pembayaran fleksibel di kasir
                </li>
            </ul>
        </div>

        <!-- KANAN - Form -->
        <div class="animate-fade-in-up" style="animation-delay: 0.15s;">
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 border border-border-pink">

                <div class="text-center mb-6">
                    <h2 class="font-display text-2xl md:text-3xl font-bold text-berry mb-1">Mulai Pesanan</h2>
                    <p class="text-sm text-berry-soft">Isi data diri Anda terlebih dahulu</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-cafe alert-cafe-danger mb-5">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= $base_url; ?>proses/simpan_pelanggan.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-berry mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <i class="ph ph-user absolute left-3 top-1/2 -translate-y-1/2 text-berry-soft text-lg"></i>
                            <input type="text" name="nama" required placeholder="Masukkan nama Anda" class="input-cafe pl-10">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-berry mb-1.5">Nomor Meja</label>
                        <div class="relative">
                            <i class="ph ph-squares-four absolute left-3 top-1/2 -translate-y-1/2 text-berry-soft text-lg"></i>
                            <input type="text" name="no_meja" required placeholder="Contoh: A01" class="input-cafe pl-10">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-berry mb-1.5">
                            No. HP <span class="text-berry-soft font-normal">(opsional)</span>
                        </label>
                        <div class="relative">
                            <i class="ph ph-phone absolute left-3 top-1/2 -translate-y-1/2 text-berry-soft text-lg"></i>
                            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" class="input-cafe pl-10">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-cafe w-full !py-3 !text-[15px] mt-2">
                        Mulai Pesan
                        <i class="ph ph-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-5 pt-5 border-t border-border-pink text-center">
                    <a href="<?= $base_url; ?>admin/login.php" class="text-xs text-berry-soft hover:text-rose-dusty inline-flex items-center gap-1.5">
                        <i class="ph ph-shield-check"></i>
                        Login Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>