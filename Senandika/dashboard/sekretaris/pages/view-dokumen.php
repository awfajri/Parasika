<?php
$page_title = 'Detail Dokumen - Senandika';
$asset_path = '../../../';
include '../../../includes/head.php';

// TODO: Ambil data dokumen berdasarkan $id dari database
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Placeholder data – ganti dengan query database
$dokumen = [
  'id'       => $id,
  'nama'     => 'Surat Peminjaman Aula',
  'kategori' => 'Surat keluar',
  'ukuran'   => '2MB',
  'tanggal'  => '2 Mei 2026',
  'diunggah_oleh' => 'Senandika',
  'file_path' => '#',   // TODO: path file sesungguhnya
];
?>

<div class="d-flex">

  <?php include '../../../includes/sidebar.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">

      <div class="section-hero mb-4">
        <h1>Detail Dokumen</h1>
      </div>

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
    <?php include '../../../includes/footer.php'; ?>
  </div>

</div>

<?php include '../../../includes/scripts.php'; ?>