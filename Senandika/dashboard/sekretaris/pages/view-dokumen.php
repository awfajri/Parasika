<?php
/**
 * HALAMAN DETAIL DOKUMEN - DASHBOARD SEKRETARIS
 * Menampilkan rincian metadata dokumen secara mendalam.
 */
$page_title = 'Detail Dokumen - Senandika';
$asset_path = '../../../';
include '../../../includes/head.php';

// Menangkap ID dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/**
 * PLACEHOLDER DATA 
 * Bagian ini dapat dihubungkan ke query database untuk menarik rincian lengkap dokumen.
 */
$dokumen = [
  'id'       => $id,
  'nama'     => 'Surat Peminjaman Aula',
  'kategori' => 'Surat keluar',
  'ukuran'   => '2MB',
  'tanggal'  => '2 Mei 2026',
  'diunggah_oleh' => 'Senandika',
  'file_path' => '#',   
];
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
        <h1>Detail Dokumen</h1>
      </div>

      <!-- KARTU INFORMASI RINCIAN -->
      <div class="card-custom" style="max-width:680px;">
        <h5 class="mb-4" style="font-family:var(--font-head); font-weight:700;">
          Informasi Dokumen
        </h5>

        <table style="width:100%; font-size:.92rem; font-family:var(--font-body);">
          <tr>
            <td style="padding:10px 0; color:var(--text-muted); width:160px; font-family:var(--font-head); font-weight:600; font-size:.85rem;">Nama Dokumen</td>
            <td style="padding:10px 0;"><?= htmlspecialchars($dokumen['nama']) ?></td>
          </tr>
          <tr>
            <td style="padding:10px 0; color:var(--text-muted); font-family:var(--font-head); font-weight:600; font-size:.85rem;">Kategori</td>
            <td style="padding:10px 0;">
              <span class="badge-kategori"><?= htmlspecialchars($dokumen['kategori']) ?></span>
            </td>
          </tr>
          <tr>
            <td style="padding:10px 0; color:var(--text-muted); font-family:var(--font-head); font-weight:600; font-size:.85rem;">Ukuran</td>
            <td style="padding:10px 0;"><?= htmlspecialchars($dokumen['ukuran']) ?></td>
          </tr>
          <tr>
            <td style="padding:10px 0; color:var(--text-muted); font-family:var(--font-head); font-weight:600; font-size:.85rem;">Waktu Unggah</td>
            <td style="padding:10px 0;"><?= htmlspecialchars($dokumen['tanggal']) ?></td>
          </tr>
          <tr>
            <td style="padding:10px 0; color:var(--text-muted); font-family:var(--font-head); font-weight:600; font-size:.85rem;">Diunggah Oleh</td>
            <td style="padding:10px 0;"><?= htmlspecialchars($dokumen['diunggah_oleh']) ?></td>
          </tr>
        </table>

        <!-- Tombol Aksi Lanjutan -->
        <div class="d-flex gap-3 mt-4">
          <a href="<?= htmlspecialchars($dokumen['file_path']) ?>"
              class="btn-add" target="_blank" download>
            <i class="bi bi-download"></i> Unduh Dokumen
          </a>
          <a href="edit-dokumen.php?id=<?= $id ?>" class="btn-add"
              style="background:var(--primary-light);">
            <i class="bi bi-pencil"></i> Edit
          </a>
          <a href="kelola-dokumen.php" class="btn btn-outline-secondary rounded-pill px-4"
              style="font-family:var(--font-head); font-size:.88rem;">
            Kembali
          </a>
        </div>
      </div>

    </div>
    <!-- Global Footer -->
    <?php include '../../../includes/footer.php'; ?>
  </div>

</div>

<!-- Memuat Scripts -->
<?php include '../../../includes/scripts.php'; ?>