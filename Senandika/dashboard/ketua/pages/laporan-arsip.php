    <?php
    $page_title = 'Laporan Arsip - Senandika';
    $asset_path = '../../../';
    include '../../../includes/head.php';
    ?>

    <div class="d-flex">

    <?php include '../../../includes/sidebar-ketua.php'; ?>

    <div class="main-content w-100">
        <div class="content-area">

        <!-- Section Hero -->
        <div class="section-hero">
            <h1>Selamat Datang, Ketua Umum!</h1>
        </div>

        <!-- Filter Kategori -->
        <div class="card-custom mb-4">
            <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
            <label style="font-family:var(--font-head); font-weight:700; font-size:0.95rem; white-space:nowrap;">
                Filter Kategori:
            </label>
            <select name="kategori" class="form-select" style="max-width:280px; border-radius:8px; font-family:var(--font-head); font-size:0.88rem;">
                <option value="">Semua kategori</option>
                <option value="Surat Masuk">Surat Masuk</option>
                <option value="Surat Keluar">Surat Keluar</option>
                <option value="Proposal">Proposal</option>
                <option value="LPJ">LPJ</option>
                <option value="AD/ART">AD/ART</option>
                <option value="SK">SK</option>
            </select>

            <div class="d-flex gap-2 ms-auto">
                <button type="submit"
                        style="background:var(--primary); color:#fff; font-family:var(--font-head); font-weight:700; font-size:0.88rem; padding:10px 24px; border-radius:8px; border:none; cursor:pointer;">
                Terapkan
                </button>
                <a href="laporan-arsip.php"
                style="background:#fff; color:var(--text-dark); font-family:var(--font-head); font-weight:700; font-size:0.88rem; padding:10px 24px; border-radius:8px; border:1px solid var(--border); text-decoration:none; display:inline-flex; align-items:center;">
                Reset
                </a>
            </div>
            </form>
        </div>

        <!-- Tabel Dokumen -->
        <div class="table-card">
            <div class="p-3">
            <table class="table-custom" id="laporanTable">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokumen</th>
                    <th>Kategori</th>
                    <th>Waktu Unggah</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                require_once '../../../config/database.php';
                $kategori = trim($_GET['kategori'] ?? '');
                $sql = "SELECT d.id, d.nama_dokumen, d.file_url, d.created_at, k.nama_kategori
                        FROM dokumen d
                        JOIN kategori_arsip k ON d.kategori_id = k.id
                        WHERE 1=1";
                $types = '';
                $params = [];
                if (!empty($kategori)) {
                    $sql .= " AND k.nama_kategori = ?";
                    $types .= 's';
                    $params[] = $kategori;
                }
                $sql .= " ORDER BY d.created_at DESC";
                $stmt = $conn->prepare($sql);
                $hasil = [];
                if ($stmt) {
                    if (!empty($params)) {
                        $stmt->bind_param($types, ...$params);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $hasil[] = $row;
                    }
                    $stmt->close();
                }
                if (empty($hasil)):
                ?>
                <tr><td colspan="5" class="text-center">Tidak ada dokumen ditemukan.</td></tr>
                <?php else:
                foreach ($hasil as $i => $doc): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($doc['nama_dokumen']) ?></td>
                    <td><?= htmlspecialchars($doc['nama_kategori']) ?></td>
                    <td><?= htmlspecialchars($doc['created_at']) ?></td>
                    <td>
                    <a href="<?= htmlspecialchars($doc['file_url']) ?>"
                        class="btn-lihat-file" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i> Lihat File
                    </a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        </div>
    </div>

    </div>

    <?php include '../../../includes/scripts.php'; ?>