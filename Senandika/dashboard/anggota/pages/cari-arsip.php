<?php
/**
 * HALAMAN CARI ARSIP - DASHBOARD ANGGOTA
 * Modul ini memungkinkan Anggota untuk mencari dan melihat dokumen publik.
 * Fitur: Pencarian berdasarkan Keyword dan Filter berdasarkan Kategori.
 */
$page_title = 'Cari Arsip - Senandika';
$asset_path = '../../../';
include '../../../includes/head.php';

// Menangkap parameter pencarian dari URL (Metode GET)
$keyword  = trim($_GET['keyword'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');
?>

<div class="d-flex">

<!-- Memuat Sidebar Navigasi Anggota -->
<?php include '../../../includes/sidebar-anggota.php'; ?>

<div class="main-content w-100">
    <div class="content-area">

    <!-- Tombol Toggle Sidebar untuk mobile -->
    <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
        <i class="bi bi-list"></i> Menu
    </button>

    <div class="section-hero">
        <h1>Selamat Datang, Anggota Aktif!</h1>
    </div>

    <!-- FORM PENCARIAN -->
    <div class="card-custom">
        <h5 style="font-family:var(--font-head); font-weight:700; margin-bottom:20px;">
        Pencarian Arsip Dokumen
        </h5>

        <form method="GET" class="d-flex gap-3 flex-wrap align-items-center">
            <!-- Input Text Keyword -->
            <div class="search-box-anggota">
                <i class="bi bi-search"></i>
                <input type="text"
                    name="keyword"
                    value="<?= htmlspecialchars($keyword) ?>"
                    placeholder="Ketik nama atau deskripsi...">
            </div>

            <!-- Dropdown Filter Kategori -->
            <select name="kategori" class="form-select"
                    style="max-width:220px; border-radius:50px; font-family:var(--font-head); font-size:0.88rem; border:1px solid var(--border);">
                <option value="">Semua kategori</option>
                <option value="Surat Masuk"  <?= $kategori === 'Surat Masuk'  ? 'selected' : '' ?>>Surat Masuk</option>
                <option value="Surat Keluar" <?= $kategori === 'Surat Keluar' ? 'selected' : '' ?>>Surat Keluar</option>
                <option value="Proposal"     <?= $kategori === 'Proposal'     ? 'selected' : '' ?>>Proposal</option>
                <option value="LPJ"          <?= $kategori === 'LPJ'          ? 'selected' : '' ?>>LPJ</option>
                <option value="AD/ART"       <?= $kategori === 'AD/ART'       ? 'selected' : '' ?>>AD/ART</option>
                <option value="SK"           <?= $kategori === 'SK'           ? 'selected' : '' ?>>SK</option>
            </select>

            <button type="submit" class="btn-add" style="padding:9px 28px;">
                Cari
            </button>
        </form>
    </div>

    <?php
    /**
     * LOGIKA QUERY PENCARIAN
     * Membangun query SQL secara dinamis berdasarkan input user.
     */
    require_once '../../../config/database.php';

    $hasil = [];
    
    // Query dasar: menggabungkan tabel dokumen dengan kategori_arsip
    $sql = "SELECT d.id, d.nama_dokumen, d.file_url, d.created_at, k.nama_kategori 
            FROM dokumen d 
            JOIN kategori_arsip k ON d.kategori_id = k.id 
            WHERE 1=1";
    $types = '';
    $params = [];

    // Filter Keyword: Mencari di Nama Dokumen atau Deskripsi
    if (!empty($keyword)) {
        $sql .= " AND (d.nama_dokumen LIKE ? OR d.deskripsi LIKE ?)";
        $like_keyword = "%$keyword%";
        $types .= 'ss';
        $params[] = $like_keyword;
        $params[] = $like_keyword;
    }

    // Filter Kategori
    if (!empty($kategori)) {
        $sql .= " AND k.nama_kategori = ?";
        $types .= 's';
        $params[] = $kategori;
    }

    // Urutkan berdasarkan yang terbaru
    $sql .= " ORDER BY d.created_at DESC";

    // Menggunakan Prepared Statement untuk keamanan dari SQL Injection
    $stmt = $conn->prepare($sql);
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
    ?>

    <!-- TAMPILAN DATA HASIL PENCARIAN -->
    <div class="table-card mt-4">
        <div class="p-3">
        <?php if (empty($hasil)): ?>
        <!-- State: Data Tidak Ditemukan -->
        <div class="text-center py-5" style="color:var(--text-muted); font-family:var(--font-head);">
            <i class="bi bi-search" style="font-size:2.5rem; opacity:0.3;"></i>
            <p class="mt-3">Tidak ada dokumen ditemukan untuk pencarian
            <strong>"<?= htmlspecialchars($keyword) ?>"</strong>
            </p>
        </div>
        <?php else: ?>
        <!-- State: Data Ditemukan - Menampilkan Tabel -->
        <div class="table-responsive">
          <table class="table-custom">
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
              <?php foreach ($hasil as $i => $doc): ?>
              <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($doc['nama_dokumen']) ?></td>
                  <td><?= htmlspecialchars($doc['nama_kategori']) ?></td>
                  <td><?= htmlspecialchars(date('d M Y H:i', strtotime($doc['created_at']))) ?></td>
                  <td>
                  <!-- Link langsung ke URL file (Cloud Storage) -->
                  <a href="<?= htmlspecialchars($doc['file_url']) ?>"
                      class="btn-lihat-file" target="_blank">
                      <i class="bi bi-box-arrow-up-right"></i> Lihat File
                  </a>
                  </td>
              </tr>
              <?php endforeach; ?>
              </tbody>
          </table>
        </div>
        <?php endif; ?>
        </div>
    </div>

    </div>

</div>

<!-- Memuat Scripts global -->
<?php include '../../../includes/scripts.php'; ?>

</div>