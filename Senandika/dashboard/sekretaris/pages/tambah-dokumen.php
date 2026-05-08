<?php
$page_title = 'Tambah Dokumen - Senandika';
$asset_path = '../../../';
include '../../../includes/head.php';
require_once '../../../config/database.php';

// Ambil kategori dari database
$kategori_query = $conn->query("SELECT id, nama_kategori FROM kategori_arsip");
?>

<div class="d-flex">

  <?php include '../../../includes/sidebar.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">

      <!-- Section Hero -->
      <div class="section-hero mb-4">
        <h1>Tambah Dokumen</h1>
      </div>

      <div class="card-custom" style="max-width:680px;">
        <h5 class="mb-4" style="font-family:var(--font-head); font-weight:700;">
          Form Unggah Dokumen
        </h5>

        <!-- TODO: Hubungkan action form ke handler upload backend -->
        <form action="proses-tambah-dokumen.php" method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              Nama Dokumen
            </label>
            <input type="text" name="nama_dokumen" class="form-control"
                  placeholder="Contoh: Surat Peminjaman Aula" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              Kategori
            </label>
            <select name="kategori_id" class="form-select" required>
              <option value="" disabled selected>Pilih kategori</option>
              <?php while ($kat = $kategori_query->fetch_assoc()): ?>
                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              Deskripsi
            </label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi dokumen..."></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.9rem;">
              File Dokumen
            </label>
            <input type="file" name="file_dokumen" class="form-control"
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
            <small class="text-muted">Format: PDF, Word, Excel, JPG, PNG. Maksimal 5MB.</small>
          </div>

          <div class="d-flex gap-3">
            <button type="submit" class="btn-add">
              <i class="bi bi-upload"></i> Unggah Dokumen
            </button>
            <a href="kelola-dokumen.php" class="btn btn-outline-secondary rounded-pill px-4"
              style="font-family:var(--font-head); font-size:.88rem;">
              Batal
            </a>
          </div>

        </form>
      </div>

    </div><!-- /content-area -->

    <?php include '../../../includes/footer.php'; ?>
  </div>

</div>

<?php include '../../../includes/scripts.php'; ?>