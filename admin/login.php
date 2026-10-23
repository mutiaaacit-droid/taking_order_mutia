<?php
require_once '../config/koneksi.php';
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}
$judul = "Login Admin - Ruang Teduh Cafe";
include '../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-cream relative overflow-hidden">
    <!-- Dekorasi subtle -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(247,200,208,0.35) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(181,91,114,0.08) 0%, transparent 70%);"></div>
    </div>

    <div class="relative max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 border border-border-pink">

            <!-- Logo Brand -->
            <div class="text-center mb-6">
                <img src="<?= $base_url; ?>assets/logo-brand.png"
                     alt="Ruang Teduh Cafe"
                     class="h-16 w-auto object-contain mx-auto mb-3">
                <h1 class="font-display text-xl font-bold text-berry">Admin Login</h1>
                <p class="text-xs text-berry-soft mt-1">Masuk ke dashboard administrator</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-cafe alert-cafe-danger mb-4">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= $base_url; ?>admin/proses_login.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-berry mb-1.5">Username</label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-3 top-1/2 -translate-y-1/2 text-berry-soft text-lg"></i>
                        <input type="text" name="username" required class="input-cafe pl-10" placeholder="Masukkan username">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-berry mb-1.5">Password</label>
                    <div class="relative">
                        <i class="ph ph-lock absolute left-3 top-1/2 -translate-y-1/2 text-berry-soft text-lg"></i>
                        <input type="password" name="password" required class="input-cafe pl-10" placeholder="Masukkan password">
                    </div>
                </div>
                <button type="submit" class="btn-primary-cafe w-full !py-3">
                    <i class="ph ph-sign-in"></i>
                    Login
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-border-pink text-center">
                <a href="<?= $base_url; ?>index.php" class="text-xs text-berry-soft hover:text-rose-dusty inline-flex items-center gap-1.5">
                    <i class="ph ph-arrow-left"></i>
                    Kembali ke Halaman Pelanggan
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>