<?php
session_start();
$page_title = 'Kelola Anggota - Senandika';
$asset_path = '../../../';
require_once '../../../config/database.php';

// Proses Terima/Tolak Anggota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id_user = (int)$_POST['user_id'];
    
    if ($_POST['action'] === 'approve') {
        $role = $_POST['role'];
        $stmt = $conn->prepare("UPDATE users SET status = 'approved', role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $id_user);
        if ($stmt->execute()) { $msg = "approved"; }
    } elseif ($_POST['action'] === 'reject') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id_user);
        if ($stmt->execute()) { $msg = "rejected"; }
    }
    header("Location: kelola-anggota.php?status=$msg");
    exit;
}

// Proses Hapus Anggota Aktif
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id_hapus);
    if ($stmt->execute()) { header("Location: kelola-anggota.php?status=deleted"); }
    $stmt->close();
    exit;
}

include '../../../includes/head.php';
?>
<div class="d-flex">
  <?php include '../../../includes/sidebar.php'; ?>
  <div class="main-content w-100">
    <div class="content-area">
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
        <i class="bi bi-list"></i> Menu
      </button>

      <div class="section-hero mb-4">
        <h1>Kelola Anggota & Kepengurusan</h1>
      </div>

      <?php if (isset($_GET['status'])): ?>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
            let status = '<?= $_GET['status'] ?>';
            if(status === 'approved') Swal.fire('Berhasil!', 'Akun berhasil dikonfirmasi.', 'success');
            if(status === 'rejected') Swal.fire('Ditolak!', 'Registrasi akun ditolak/dihapus.', 'success');
            if(status === 'deleted') Swal.fire('Dihapus!', 'Data anggota aktif dihapus.', 'success');
        });
      </script>
      <?php endif; ?>

      <div class="card-custom mb-4 border-2">
        <h5 class="mb-3 fw-bold" style="font-family:var(--font-head);">
           <i class="bi bi-person-fill-exclamation text-warning me-2"></i> Permintaan Registrasi
        </h5>
        <div class="table-responsive">
          <table class="table-custom">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NPM</th>
                <th>Aksi Konfirmasi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $res_pending = $conn->query("SELECT * FROM users WHERE status = 'pending' ORDER BY created_at ASC");
              $i = 1;
              if ($res_pending->num_rows > 0):
                  while ($row = $res_pending->fetch_assoc()):
              ?>
              <tr>
                <td><?= $i++ ?></td>
                <td class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['npm']) ?></td>
                <td>
                  <form method="POST" class="d-flex gap-2 align-items-center m-0">
                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <select name="role" class="form-select form-select-sm" style="width:140px;" required>
                      <option value="anggota">Anggota</option>
                      <option value="sekretaris">Sekretaris</option>
                      <option value="ketua">Ketua / Wakil</option>
                    </select>
                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success text-white" title="Terima">
                      <i class="bi bi-check-lg"></i> Terima
                    </button>
                    <button type="button" class="btn btn-sm btn-danger text-white btn-tolak" title="Tolak">
                      <i class="bi bi-x-lg"></i> Tolak
                    </button>
                  </form>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada antrean registrasi anggota baru.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-custom">
        <h5 class="mb-3 fw-bold" style="font-family:var(--font-head);">Daftar Anggota Aktif</h5>
        <div class="table-responsive">
          <table class="table-custom">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NPM</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $res_aktif = $conn->query("SELECT * FROM users WHERE status = 'approved' ORDER BY role DESC, created_at DESC");
              $j = 1;
              while ($row = $res_aktif->fetch_assoc()):
              ?>
              <tr>
                <td><?= $j++ ?></td>
                <td class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['npm']) ?></td>
                <td><span class="badge-kategori"><?= ucfirst($row['role']) ?></span></td>
                <td>
                  <?php if ($row['id'] !== $_SESSION['user_id']): ?>
                  <a href="?hapus=<?= $row['id'] ?>" class="btn-del">
                    <i class="bi bi-trash3"></i> Hapus
                  </a>
                  <?php else: ?>
                  <span class="text-muted small">Anda</span>
                  <?php endif; ?>
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
<?php include '../../../includes/scripts.php'; ?>