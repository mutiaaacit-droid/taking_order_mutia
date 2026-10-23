<?php
require_once '../config/koneksi.php';
require_once '../includes/auth.php';
requireAdmin();
$judul = "Kelola Kategori";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'tambah') {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
        $slug = slugify($nama);
        mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori, slug) VALUES ('$nama', '$slug')");
        $_SESSION['pesan'] = "Kategori berhasil ditambahkan.";
    } elseif ($action === 'edit') {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
        $slug = slugify($nama);
        mysqli_query($koneksi, "UPDATE kategori SET nama_kategori='$nama', slug='$slug' WHERE id=$id");
        $_SESSION['pesan'] = "Kategori berhasil diperbarui.";
    } elseif ($action === 'hapus') {
        mysqli_query($koneksi, "DELETE FROM kategori WHERE id=$id");
        $_SESSION['pesan'] = "Kategori berhasil dihapus.";
    }
    header("Location: kategori.php");
    exit;
}

$kategori = mysqli_query($koneksi, "SELECT k.*, (SELECT COUNT(*) FROM menu WHERE kategori_id = k.id) as total_menu
    FROM kategori k ORDER BY k.urutan, k.nama_kategori");

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
        <h1 class="font-display text-2xl md:text-3xl font-bold text-berry mb-5">Kelola Kategori</h1>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert-cafe alert-cafe-success alert-auto-hide mb-5"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-5">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-border-pink p-5">
                    <h2 class="font-display text-base font-bold text-berry mb-3">Tambah Kategori</h2>
                    <form method="POST" class="space-y-3">
                        <input type="hidden" name="action" value="tambah">
                        <div>
                            <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required class="input-cafe" placeholder="Contoh: Dessert">
                        </div>
                        <button type="submit" class="btn-primary-cafe w-full">
                            <i class="ph ph-plus"></i> Tambah
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-border-pink overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-cream-alt">
                            <tr class="text-left">
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Kategori</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider">Slug</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider text-center">Menu</th>
                                <th class="px-5 py-3 font-semibold text-berry text-xs uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-pink">
                            <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                            <tr class="hover:bg-cream-alt/50">
                                <td class="px-5 py-3 font-medium text-berry"><?= htmlspecialchars($k['nama_kategori']); ?></td>
                                <td class="px-5 py-3 text-berry-soft font-mono text-xs"><?= $k['slug']; ?></td>
                                <td class="px-5 py-3 text-center">
                                    <span class="badge-cafe badge-cafe-primary"><?= $k['total_menu']; ?></span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-1.5 justify-center">
                                        <button onclick="editKategori(<?= $k['id']; ?>, '<?= htmlspecialchars($k['nama_kategori']); ?>')"
                                                class="w-8 h-8 rounded-md bg-blush-light flex items-center justify-center text-rose-dusty hover:bg-rose-dusty hover:text-white transition-colors">
                                            <i class="ph ph-pencil-simple text-sm"></i>
                                        </button>
                                        <form method="POST" onsubmit="return confirm('Hapus kategori ini?')" class="inline">
                                            <input type="hidden" name="action" value="hapus">
                                            <input type="hidden" name="id" value="<?= $k['id']; ?>">
                                            <button class="w-8 h-8 rounded-md bg-red-50 flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition-colors">
                                                <i class="ph ph-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-berry/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg p-5 w-full max-w-md">
        <h3 class="font-display text-lg font-bold text-berry mb-3">Edit Kategori</h3>
        <form method="POST" class="space-y-3">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label class="block text-xs font-semibold text-berry mb-1.5 uppercase tracking-wider">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="edit_nama" required class="input-cafe">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary-cafe flex-1">Simpan</button>
                <button type="button" onclick="closeModal()" class="btn-outline-cafe flex-1">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function editKategori(id, nama) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    const m = document.getElementById('editModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeModal() {
    const m = document.getElementById('editModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
</script>

<?php include '../includes/footer.php'; ?>