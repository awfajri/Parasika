<?php
/**
 * DASHBOARD SEKRETARIS - SENANDIKA
 * Halaman utama untuk Sekretaris sebagai admin sistem.
 * Menampilkan statistik dokumen, distribusi kategori, dan log aktivitas terbaru.
 */
$required_role = 'sekretaris';
$page_title = 'Dashboard - Senandika';
$asset_path = '../../';
$base_path  = '../../';
require_once '../../includes/auth.php'; // Proteksi hak akses sekretaris
include '../../includes/head.php';      // Metadata & CSS
?>

<div class="d-flex">

  <!-- Sidebar Utama (Akses penuh untuk Sekretaris) -->
  <?php include '../../includes/sidebar.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">
      <!-- Tombol Menu Mobile -->
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
        <i class="bi bi-list"></i> Menu
      </button>

      <!-- Hero Banner Sekretaris -->
      <div class="hero-banner">
        <h1>Selamat Datang, Sekretaris!</h1>
        <p>Anda dapat mengelola berbagai arsip dokumen organisasi,<br>
          seperti format proposal, surat keluar dan SK kepengurusan.</p>
        <a href="pages/kelola-dokumen.php" class="btn-hero">Mulai Kelola Dokumen</a>
      </div>

      <!-- SECTION: STATISTIK TOTAL DOKUMEN -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card-custom">
            <div class="card-title">Total Surat</div>
            <?php
            require_once '../../config/database.php';
            // Menghitung jumlah baris di tabel dokumen
            $total_query = $conn->query("SELECT COUNT(*) as total FROM dokumen");
            $total_surat = $total_query->fetch_assoc()['total'];
            ?>
            <div class="card-number"><?= $total_surat ?></div>

            <?php
            /**
             * Query Distribusi Kategori Dokumen
             * Menghitung jumlah file per kategori untuk ditampilkan dalam bar chart.
             */
            $kat_query = $conn->query("
              SELECT k.nama_kategori, COUNT(d.id) as jumlah 
              FROM kategori_arsip k 
              LEFT JOIN dokumen d ON k.id = d.kategori_id 
              GROUP BY k.id
            ");
            $stats = [];
            while ($row = $kat_query->fetch_assoc()) {
                // 'max' 80 digunakan sebagai skala visual bar chart
                $stats[$row['nama_kategori']] = ['value' => $row['jumlah'], 'max' => 80];
            }
            ?>
            <div class="bar-chart">
              <div class="bar-row mb-1">
                <div style="width:70px"></div>
                <div style="flex:1;display:flex;justify-content:space-between;font-size:0.75rem;color:var(--text-muted);font-family:var(--font-head);">
                  <span>0</span><span>10</span><span>20</span><span>40</span><span>80</span>
                </div>
                <div style="width:26px"></div>
              </div>
              
              <?php foreach ($stats as $label => $data): ?>
              <div class="bar-row">
                <div class="bar-label"><?= htmlspecialchars($label) ?></div>
                <div class="bar-track">
                  <!-- Lebar bar diatur secara dinamis via data-width (main.js) -->
                  <div class="bar-fill" style="width:0%" data-width="<?= round(($data['value'] / $data['max']) * 100) ?>%"></div>
                </div>
                <div class="bar-value"><?= $data['value'] ?></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION: LOG AKTIVITAS TERBARU -->
      <div class="card-custom">
        <div style="font-size:1.1rem;font-weight:700;font-family:var(--font-head);margin-bottom:16px;">
          Log Aktivitas Terbaru Sistem
        </div>
        
        <?php
        /**
         * Menampilkan 7 aktivitas pengunggahan dokumen terbaru
         * JOIN dengan tabel users untuk mendapatkan nama pengunggah.
         */
        $log_query = $conn->query("
          SELECT u.nama_lengkap, d.nama_dokumen, d.created_at 
          FROM dokumen d 
          JOIN users u ON d.uploader_id = u.id 
          ORDER BY d.created_at DESC 
          LIMIT 7
        ");
        $activities = [];
        if ($log_query) {
            while ($log = $log_query->fetch_assoc()) {
                $activities[] = [
                    'user' => $log['nama_lengkap'],
                    'name' => 'Mengunggah ' . $log['nama_dokumen'],
                    'date' => date('d M Y', strtotime($log['created_at']))
                ];
            }
        }
        if (empty($activities)) {
            $activities[] = ['user' => '-', 'name' => 'Belum ada aktivitas unggah dokumen.', 'date' => '-'];
        }
        ?>
        <div class="activity-list">
          <?php foreach ($activities as $act): ?>
          <div class="activity-item">
            <div>
              <div style="font-weight:700; font-family:var(--font-head); font-size:0.88rem; color:var(--primary);">
                  <?= htmlspecialchars($act['user']) ?>
              </div>
              <div style="font-size:0.88rem; color:var(--text-muted);">
                  <?= htmlspecialchars($act['name']) ?>
              </div>
            </div>
            <span class="activity-date"><?= htmlspecialchars($act['date']) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>

</div>

<!-- Scripts Global (Bootstrap, SweetAlert, Custom Main JS) -->
<?php include '../../includes/scripts.php'; ?>