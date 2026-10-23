<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();

$id = (int)$_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $id");
$m = mysqli_fetch_assoc($q);
if (!$m) { header("Location: menu.php"); exit; }

$judul = "Edit Menu";
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kategori_id = (int)$_POST['kategori_id'];
    $harga = (float)$_POST['harga'];
    $desc = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok = (int)$_POST['stok'];
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $slug = slugify($nama);

    $gambar = $m['gambar'];
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $res = uploadGambar($_FILES['gambar']);
        if ($res['success']) {
            if ($gambar) hapusGambar($gambar);
            $gambar = $res['filename'];
        } else {
            $error = $res['message'];
        }
    }

    if (!$error) {
        $gambar_val = $gambar ? "'$gambar'" : "NULL";
        $sql = "UPDATE menu SET kategori_id=$kategori_id, nama_menu='$nama', slug='$slug',
                harga=$harga, deskripsi='$desc', stok=$stok, status='$status',
                gambar=$gambar_val, is_featured=$is_featured WHERE id=$id";
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = "Menu berhasil diperbarui.";
            header("Location: menu.php");
            exit;
        }
    }
}

$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori WHERE is_active = 1 ORDER BY urutan");
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
        <div class="flex items-center gap-3 mb-5">
            <a href="<?= $base_url; ?>admin/menu.php" class="w-9 h-9 rounded-md bg-white border border-border-pink flex items-center justify-center text-berry hover:border-rose-dusty hover:text-rose-dusty transition-colors">
                <i class="ph ph-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-display text-2xl md:text-3xl font-bold text-berry">Edit Menu</h1>
                <p class="text-sm text-berry-soft"><?= htmlspecialchars($m['nama_menu']); ?></p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert-cafe alert-cafe-danger mb-5"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="grid lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 bg-white rounded-lg border border-border-pink p-5">
                <h2 class="font-display text-base font-bold text-berry mb-3">Informasi Menu</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Nama Menu *</label>
                        <input type="text" name="nama_menu" required class="input-cafe" value="<?= htmlspecialchars($m['nama_menu']); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Kategori *</label>
                        <select name="kategori_id" required class="input-cafe">
                            <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
                                <option value="<?= $k['id']; ?>" <?= $m['kategori_id'] == $k['id'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($k['nama_kategori']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Harga *</label>
                        <input type="number" name="harga" required min="0" step="500" class="input-cafe" value="<?= $m['harga']; ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Stok *</label>
                        <input type="number" name="stok" required min="0" class="input-cafe" value="<?= $m['stok']; ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Status *</label>
                        <select name="status" required class="input-cafe">
                            <option value="tersedia" <?= $m['status']=='tersedia'?'selected':''; ?>>Tersedia</option>
                            <option value="habis" <?= $m['status']=='habis'?'selected':''; ?>>Habis</option>
                            <option value="nonaktif" <?= $m['status']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="input-cafe"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" <?= $m['is_featured']?'checked':''; ?> class="w-4 h-4 rounded text-rose-dusty">
                            <span class="text-sm text-berry">Jadikan menu favorit</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-lg border border-border-pink p-5">
                    <h2 class="font-display text-base font-bold text-berry mb-3">Gambar</h2>
                    <label class="upload-area block cursor-pointer">
                        <input type="file" name="gambar" accept="image/*" class="hidden" id="fileInput">
                        <div id="uploadPlaceholder" <?= !empty($m['gambar']) ? 'class="hidden"' : ''; ?>>
                            <i class="ph ph-upload-simple text-3xl text-rose-soft mb-2 block"></i>
                            <p class="text-sm font-medium text-berry">Klik untuk upload</p>
                            <p class="text-xs text-berry-soft mt-1">JPG, PNG, WebP (max 2MB)</p>
                        </div>
                        <img id="imagePreview" class="upload-preview <?= empty($m['gambar']) ? 'hidden' : ''; ?>"
                             src="<?= !empty($m['gambar']) ? $base_url.'assets/uploads/menu/'.htmlspecialchars($m['gambar']) : ''; ?>"
                             alt="Preview">
                    </label>
                </div>

                <div class="bg-white rounded-lg border border-border-pink p-5">
                    <button type="submit" class="btn-primary-cafe w-full">
                        <i class="ph ph-check"></i> Update
                    </button>
                    <a href="<?= $base_url; ?>admin/menu.php" class="btn-outline-cafe w-full mt-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const f = e.target.files[0];
    if (f) {
        const r = new FileReader();
        r.onload = ev => {
            const p = document.getElementById('imagePreview');
            p.src = ev.target.result;
            p.classList.remove('hidden');
            document.getElementById('uploadPlaceholder').classList.add('hidden');
        };
        r.readAsDataURL(f);
    }
});
</script>

<?php include '../includes/footer.php'; ?>