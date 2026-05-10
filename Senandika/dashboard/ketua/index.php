<?php
/**
 * DASHBOARD KETUA - SENANDIKA
 * Halaman utama untuk user dengan role 'ketua'.
 * Fokus pada pemantauan statistik dan log aktivitas pengunggahan dokumen.
 */
$required_role = 'ketua';
$page_title = 'Dashboard Ketua - Senandika';
$asset_path = '../../';
$base_path  = '../../';
require_once '../../includes/auth.php'; // Proteksi hak akses
include '../../includes/head.php';      // Metadata & Styles
?>

<div class="d-flex">

<!-- Sidebar Khusus Ketua -->
<?php include '../../includes/sidebar-ketua.php'; ?>

<div class="main-content w-100">
    <div class="content-area">
        <!-- Sidebar Toggle Mobile -->
        <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
            <i class="bi bi-list"></i> Menu
        </button>

    <!-- Hero Banner: Sambutan Khusus Ketua -->
    <div class="hero-banner">
        <h1>Selamat Datang, Ketua Umum!</h1>
        <p>Anda dapat memantau berbagai arsip dokumen organisasi,<br>
        seperti format proposal, surat keluar dan SK kepengurusan.</p>
    </div>

    <!-- SECTION: STATISTIK DOKUMEN (VISUAL BAR CHART) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
        <div class="card-custom">
            <div class="card-title">Total Surat</div>
            <?php
            require_once '../../config/database.php';
            // Hitung total semua dokumen
            $total_query = $conn->query("SELECT COUNT(*) as total FROM dokumen");
            $total_surat = $total_query->fetch_assoc()['total'];
            ?>
            <div class="card-number"><?= $total_surat ?></div>

            <?php
            /**
             * Query Distribusi Dokumen per Kategori
             */
            $kat_query = $conn->query("
              SELECT k.nama_kategori, COUNT(d.id) as jumlah 
              FROM kategori_arsip k 
              LEFT JOIN dokumen d ON k.id = d.kategori_id 
              GROUP BY k.id
            ");
            $stats = [];
            while ($row = $kat_query->fetch_assoc()) {
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
                        <div class="bar-fill" style="width:0%"
                           data-width="<?= round(($data['value'] / $data['max']) * 100) ?>%">
                        </div>
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
         * Mengambil 7 aktivitas pengunggahan dokumen terakhir
         * menggabungkan data dokumen dengan nama pengunggah (users).
         */
        $log_query = $conn->query("
          SELECT u.nama_lengkap, d.nama_dokumen, d.created_at 
          FROM dokumen d 
          JOIN users u ON d.uploader_id = u.id 
          ORDER BY d.created_at DESC 
          LIMIT 7
        ");
        $logs = [];
        if ($log_query) {
            while ($log = $log_query->fetch_assoc()) {
                $logs[] = [
                    'user' => $log['nama_lengkap'],
                    'aksi' => 'Mengunggah ' . $log['nama_dokumen'],
                    'date' => date('d M Y', strtotime($log['created_at']))
                ];
            }
        }
        if (empty($logs)) {
            $logs[] = ['user' => '-', 'aksi' => 'Belum ada aktivitas dokumen.', 'date' => '-'];
        }
        ?>

        <div class="activity-list">
            <?php foreach ($logs as $log): ?>
            <div class="activity-item">
                <div>
                    <div style="font-weight:700; font-family:var(--font-head); font-size:0.88rem; color:var(--primary);">
                        <?= htmlspecialchars($log['user']) ?>
                    </div>
                    <div style="font-size:0.88rem; color:var(--text-muted);">
                        <?= htmlspecialchars($log['aksi']) ?>
                    </div>
                </div>
                <span class="activity-date"><?= htmlspecialchars($log['date']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    </div>

</div>

</div>

<!-- Scripts Global -->
<?php include '../../includes/scripts.php'; ?>