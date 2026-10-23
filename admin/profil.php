<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();

$judul = "Profil Admin";
$adminId = $_SESSION['admin_id'];
$q = mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $adminId");
$admin = mysqli_fetch_assoc($q);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profil') {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
        $email = mysqli_real_escape_string($koneksi, $_POST['email']);
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);

        $sql = "UPDATE admin SET nama_lengkap='$nama', email='$email', username='$username' WHERE id=$adminId";
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['admin_nama'] = $nama;
            $message = "Profil berhasil diperbarui.";
            $q = mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $adminId");
            $admin = mysqli_fetch_assoc($q);
        } else {
            $error = "Gagal: " . mysqli_error($koneksi);
        }
    }

    if ($action === 'ganti_password') {
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if (!password_verify($old, $admin['password'])) $error = "Password lama salah.";
        elseif ($new !== $confirm) $error = "Konfirmasi password tidak cocok.";
        elseif (strlen($new) < 6) $error = "Password minimal 6 karakter.";
        else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE admin SET password='$hash' WHERE id=$adminId");
            $message = "Password berhasil diubah.";
        }
    }
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

    <div class="max-w-5xl mx-auto p-6 lg:p-8">
        <h1 class="font-display text-2xl md:text-3xl font-bold text-berry mb-5">Profil Admin</h1>

        <?php if ($message): ?><div class="alert-cafe alert-cafe-success mb-5"><?= $message; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert-cafe alert-cafe-danger mb-5"><?= $error; ?></div><?php endif; ?>

        <div class="grid md:grid-cols-2 gap-5">
            <div class="bg-white rounded-lg border border-border-pink p-5">
                <h2 class="font-display text-base font-bold text-berry mb-4">Informasi Profil</h2>
                <form method="POST" class="space-y-3">
                    <input type="hidden" name="action" value="update_profil">
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required class="input-cafe" value="<?= htmlspecialchars($admin['nama_lengkap']); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Username</label>
                        <input type="text" name="username" required class="input-cafe" value="<?= htmlspecialchars($admin['username']); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" name="email" class="input-cafe" value="<?= htmlspecialchars($admin['email'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn-primary-cafe w-full">Simpan</button>
                </form>
            </div>

            <div class="bg-white rounded-lg border border-border-pink p-5">
                <h2 class="font-display text-base font-bold text-berry mb-4">Ganti Password</h2>
                <form method="POST" class="space-y-3">
                    <input type="hidden" name="action" value="ganti_password">
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Password Lama</label>
                        <input type="password" name="old_password" required class="input-cafe">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Password Baru</label>
                        <input type="password" name="new_password" required minlength="6" class="input-cafe">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Konfirmasi</label>
                        <input type="password" name="confirm_password" required minlength="6" class="input-cafe">
                    </div>
                    <button type="submit" class="btn-outline-cafe w-full">Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>