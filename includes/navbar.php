<nav class="navbar-cafe">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo Brand -->
            <a href="<?= $base_url; ?>pelanggan/menu.php" class="flex items-center gap-3 group">
                <img src="<?= $base_url; ?>assets/logo-brand.png"
                     alt="Ruang Teduh Cafe"
                     class="h-11 md:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
            </a>

            <?php if (isset($_SESSION['pelanggan_id'])): ?>
            <div class="flex items-center gap-2 md:gap-3">
                <!-- Info Pelanggan -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-md bg-blush/60">
                    <i class="ph ph-user-circle text-rose-dusty text-lg"></i>
                    <span class="text-sm font-medium text-berry"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span>
                    <span class="text-berry-soft/50">·</span>
                    <span class="text-xs text-berry-soft">Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></span>
                </div>

                <!-- Keranjang -->
                <a href="<?= $base_url; ?>pelanggan/keranjang.php"
                   class="relative inline-flex items-center gap-2 px-3 md:px-4 py-2 rounded-md border border-border-pink bg-white text-berry text-sm font-medium hover:border-rose-dusty hover:text-rose-dusty transition-colors">
                    <i class="ph ph-shopping-cart text-base"></i>
                    <span class="hidden sm:inline">Keranjang</span>
                    <?php $qty = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0; ?>
                    <?php if ($qty > 0): ?>
                        <span class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1 rounded-full bg-rose-dusty text-white text-[10px] font-bold flex items-center justify-center">
                            <?= $qty; ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Selesai -->
                <a href="<?= $base_url; ?>pelanggan/logout_pelanggan.php"
                   title="Selesai & Logout"
                   class="w-9 h-9 rounded-md border border-border-pink bg-white flex items-center justify-center text-berry-soft hover:text-rose-dusty hover:border-rose-dusty transition-colors">
                    <i class="ph ph-sign-out text-base"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>