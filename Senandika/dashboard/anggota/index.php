
    <?php
    require_once '../../config/database.php';
    $page_title = 'Dashboard Anggota - Senandika';
    $asset_path = '../../';
    include '../../includes/head.php';
    ?>

    <div class="d-flex">

    <?php include '../../includes/sidebar-anggota.php'; ?>

    <div class="main-content w-100">
        <div class="content-area">

        <!-- Hero Banner -->
        <div class="hero-banner">
            <h1>Selamat Datang, Anggota Aktif!</h1>
            <p>Anda dapat mencari dan mengakses berbagai arsip organisasi,<br>
            seperti format proposal, surat keluar dan SK kepengurusan.</p>
            <a href="pages/cari-arsip.php" class="btn-hero">Mulai Pencarian Arsip</a>
        </div>


        <!-- Stats (Total & Per Kategori) -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-title">Total Surat</div>
                    <?php
                    require_once '../../config/database.php';
                    $total_query = $conn->query("SELECT COUNT(*) as total FROM dokumen");
                    $total_surat = $total_query->fetch_assoc()['total'];
                    ?>
                    <div class="card-number"><?= $total_surat ?></div>

                    <?php
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
                                <div class="bar-fill" style="width:0%" data-width="<?= round(($data['value'] / $data['max']) * 100) ?>%"></div>
                            </div>
                            <div class="bar-value"><?= $data['value'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>


        </div>

    </div>

    </div>

    <?php include '../../includes/scripts.php'; ?>