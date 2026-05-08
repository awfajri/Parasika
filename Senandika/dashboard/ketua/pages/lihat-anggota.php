<?php
session_start();
$page_title = 'Daftar Anggota - Senandika';
$asset_path = '../../../';
require_once '../../../config/database.php';
include '../../../includes/head.php';
?>

<div class="d-flex">
  <?php include '../../../includes/sidebar-ketua.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
        <i class="bi bi-list"></i> Menu
      </button>

      <div class="section-hero mb-4">
        <h1>Daftar Anggota Kabinet</h1>
      </div>

      <div class="table-card p-3">
        <div class="table-responsive">
          <table class="table-custom">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NPM</th>
                <th>Jabatan (Role)</th>
                <th>Terdaftar Pada</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("SELECT * FROM users ORDER BY role DESC, created_at DESC");
              $i = 1;
              while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $i++ ?></td>
                <td class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['npm']) ?></td>
                <td><span class="badge-kategori"><?= ucfirst($row['role']) ?></span></td>
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
<?php include '../../../includes/scripts.php'; ?>