<?php
session_start();
$page_title = 'Pengajuan Surat - Senandika';
$asset_path = '../../../';
require_once '../../../config/database.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_pengajuan'])) {
    $perihal = $_POST['perihal'];
    $tujuan = $_POST['tujuan'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("INSERT INTO pengajuan_surat (user_id, perihal, tujuan, keterangan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $perihal, $tujuan, $keterangan);
    if ($stmt->execute()) { $status = 'success'; } else { $status = 'error'; }
    $stmt->close();
    header("Location: pengajuan-surat.php?status=$status");
    exit;
}

include '../../../includes/head.php';
?>

<div class="d-flex">
  <?php include '../../../includes/sidebar-anggota.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3"><i class="bi bi-list"></i> Menu</button>
      <div class="section-hero mb-4"><h1>Layanan Pengajuan Surat</h1></div>

      <?php if (isset($_GET['status'])): ?>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          let status = '<?= $_GET['status'] ?>';
          if(status === 'success') Swal.fire('Terkirim!', 'Pengajuan surat sedang diproses.', 'success');
        });
      </script>
      <?php endif; ?>

      <div class="row g-4">
        <div class="col-lg-4">
          <div class="card-custom">
            <h5 class="fw-bold mb-4" style="font-family:var(--font-head);">Form Pengajuan</h5>
            <form method="POST">
              <div class="mb-3">
                <label class="form-label small fw-bold">Jenis / Perihal Surat</label>
                <input type="text" name="perihal" class="form-control" placeholder="Contoh: Surat Dispensasi" required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">Tujuan Surat</label>
                <input type="text" name="tujuan" class="form-control" placeholder="Contoh: Dosen Mata Kuliah" required>
              </div>
              <div class="mb-4">
                <label class="form-label small fw-bold">Keterangan / Detail</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Sebutkan NPM, keperluan, tanggal, dll" required></textarea>
              </div>
              <button type="submit" name="tambah_pengajuan" class="btn-add w-100 justify-content-center">Ajukan Surat</button>
            </form>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="table-card p-3">
            <div class="table-responsive">
              <table class="table-custom">
                <thead>
                  <tr>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th>Catatan Sekretaris</th>
                    <th>Tgl Ajukan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $res = $conn->query("SELECT * FROM pengajuan_surat WHERE user_id = $user_id ORDER BY created_at DESC");
                  while ($row = $res->fetch_assoc()):
                      $badge = 'bg-secondary';
                      if($row['status']=='pending') $badge = 'bg-warning text-dark';
                      if($row['status']=='diproses') $badge = 'bg-info text-dark';
                      if($row['status']=='selesai') $badge = 'bg-success';
                      if($row['status']=='ditolak') $badge = 'bg-danger';
                  ?>
                  <tr>
                    <td class="fw-bold"><?= htmlspecialchars($row['perihal']) ?></td>
                    <td><?= htmlspecialchars($row['tujuan']) ?></td>
                    <td><span class="badge <?= $badge ?> rounded-pill px-3"><?= ucfirst($row['status']) ?></span></td>
                    <td class="text-muted small"><?= htmlspecialchars($row['catatan_sekre'] ?? '-') ?></td>
                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../../includes/scripts.php'; ?>