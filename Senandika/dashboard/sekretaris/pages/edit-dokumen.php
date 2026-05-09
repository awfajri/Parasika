<?php
/**
 * HALAMAN EDIT DOKUMEN - DASHBOARD SEKRETARIS
 * Modul ini digunakan untuk mengubah metadata dokumen (Nama, Kategori) 
 * atau mengganti file fisik yang sudah tersimpan.
 */
$page_title = 'Edit Dokumen - Senandika';
$asset_path = '../../../';
include '../../../includes/head.php';
require_once '../../../config/database.php';

// Menangkap ID dokumen yang akan diedit dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/**
 * PENGAMBILAN DATA DOKUMEN
 * Mengambil data lama dari database untuk ditampilkan di form sebagai default value.
 */
$stmt = $conn->prepare("
    SELECT d.id, d.nama_dokumen as nama, k.nama_kategori as kategori 
    FROM dokumen d 
    JOIN kategori_arsip k ON d.kategori_id = k.id 
    WHERE d.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokumen = $result->fetch_assoc();
$stmt->close();

// Fallback jika dokumen tidak ditemukan untuk menghindari warning PHP
if (!$dokumen) {
    $dokumen = [
        'id'       => $id,
        'nama'     => '',
        'kategori' => '',
    ];
}
?>

<div class="d-flex">

  <!-- Sidebar Navigasi -->
  <?php include '../../../includes/sidebar.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
        <i class="bi bi-list"></i> Menu
      </button>

      <div class="section-hero mb-4">
        <h1>Edit Dokumen</h1>
      </div>

      <!-- Notifikasi Error jika ID tidak valid -->
      <?php if(empty($dokumen['nama']) && $id !== 0): ?>
          <div class="alert alert-danger" style="max-width:680px;">Dokumen tidak ditemukan di database.</div>
      <?php endif; ?>

      <div class="card-custom" style="max-width:680px;">
        <h5 class="mb-4" style="font-family:var(--font-head); font-weight:700;">
          Form Edit Dokumen
        </h5>

        <!-- Form Edit: Action diarahkan ke proses-edit-dokumen.php -->
        <form action="proses-edit-dokumen.php" method="POST" enctype="multipart/form-data">
          <!-- Hidden Input untuk mengirimkan ID Dokumen -->
          <input type="hidden" name="id" value="<?= htmlspecialchars($dokumen['id']) ?>">

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              Nama Dokumen
            </label>
            <input type="text" name="nama_dokumen" class="form-control"
                  value="<?= htmlspecialchars($dokumen['nama']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              Kategori
            </label>
            <select name="kategori" class="form-select" required>
              <option value="" disabled>Pilih kategori...</option>
              <?php
              /**
               * DAFTAR KATEGORI
               * Loop untuk menampilkan kategori dan menandai (selected) kategori lama.
               */
              $kategori_list = ['Surat Masuk','Surat Keluar','Proposal','LPJ','AD/ART','SK'];
              foreach ($kategori_list as $kat):
                $sel = (strtolower($kat) === strtolower($dokumen['kategori'])) ? 'selected' : '';
              ?>
              <option value="<?= $kat ?>" <?= $sel ?>><?= $kat ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              Ganti File (opsional)
            </label>
            <input type="file" name="file_dokumen" class="form-control"
                  accept=".pdf,.doc,.docx,.xls,.xlsx">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti file lama.</small>
          </div>

          <div class="d-flex gap-3">
            <button type="submit" class="btn-add">
              <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
            <a href="kelola-dokumen.php" class="btn btn-outline-secondary rounded-pill px-4"
              style="font-family:var(--font-head); font-size:.88rem;">
              Batal
            </a>
          </div>

        </form>
      </div>

    </div>
  </div>

</div>

<!-- Memuat Scripts -->
<?php include '../../../includes/scripts.php'; ?>