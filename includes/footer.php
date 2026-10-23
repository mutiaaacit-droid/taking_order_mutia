<footer class="bg-berry text-cream mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Brand -->
            <div>
                <a href="<?= $base_url ?? '/taking_order_mutia/'; ?>index.php" class="inline-block mb-4">
                    <img src="<?= $base_url ?? '/taking_order_mutia/'; ?>assets/logo-brand.png"
                         alt="Ruang Teduh Cafe"
                         class="h-16 w-auto object-contain"
                         style="filter: brightness(0) invert(1) opacity(0.95);">
                </a>
                <p class="text-sm text-blush/80 leading-relaxed max-w-xs">
                    Tempat ternyaman untuk menikmati secangkir kopi dan hidangan pilihan bersama orang tersayang.
                </p>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="font-display font-bold text-base mb-4 text-white">Kontak</h4>
                <ul class="space-y-2.5 text-sm text-blush/80">
                    <li class="flex items-start gap-2.5">
                        <i class="ph ph-map-pin text-rose-soft text-base mt-0.5 shrink-0"></i>
                        <span>Jl. Contoh Alamat No. 123, Kota Anda</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="ph ph-phone text-rose-soft text-base shrink-0"></i>
                        <span>021-1234567</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="ph ph-envelope text-rose-soft text-base shrink-0"></i>
                        <span>info@ruangteduh.com</span>
                    </li>
                </ul>
            </div>

            <!-- Jam -->
            <div>
                <h4 class="font-display font-bold text-base mb-4 text-white">Jam Operasional</h4>
                <ul class="space-y-1.5 text-sm text-blush/80">
                    <li class="flex justify-between"><span>Senin – Jumat</span><span class="text-white/90">08.00 – 22.00</span></li>
                    <li class="flex justify-between"><span>Sabtu – Minggu</span><span class="text-white/90">08.00 – 23.00</span></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 mt-8 pt-6 text-center text-xs text-blush/60">
            <p>&copy; <?= date('Y'); ?> Ruang Teduh Cafe. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    setTimeout(function() { $('.alert-auto-hide').fadeOut('slow'); }, 5000);
});
</script>
</body>
</html>