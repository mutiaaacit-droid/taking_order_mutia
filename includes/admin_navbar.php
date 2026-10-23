<?php
// File ini bisa di-include di semua halaman admin
// Pastikan $base_url sudah terdefinisi dari header.php
?>
<nav class="bg-berry text-cream py-3 px-6 shadow-md">
    <div class="flex items-center justify-between">
        
        <!-- ===== BRAND ===== -->
        <a href="dashboard.php" class="flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-xl bg-white p-1 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                <img src="<?= $base_url ?? '../'; ?>assets/logo-brand.png" 
                     alt="Ruang Teduh Cafe" 
                     class="w-full h-full object-contain">
            </div>
            <div class="hidden sm:block">
                <h1 class="font-display font-bold text-lg leading-tight">Ruang Teduh</h1>
                <p class="text-[10px] uppercase tracking-[3px] text-blush font-semibold">Admin Panel</p>
            </div>
        </a>
        
        <!-- ===== USER ===== -->
        <div class="flex items-center gap-4">
            <div class="hidden md:flex items-center gap-2 px-3 py-2 rounded-lg bg-white/10">
                <div class="w-7 h-7 rounded-full bg-rose-dusty flex items-center justify-center text-white text-xs font-bold">
                    <?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)); ?>
                </div>
                <span class="text-sm font-medium"><?= htmlspecialchars($_SESSION['admin_nama']); ?></span>
            </div>
            <a href="logout.php" class="text-sm text-blush hover:text-white transition-colors px-3 py-2 rounded-lg hover:bg-white/10">
                Logout
            </a>
        </div>
    </div>
</nav>