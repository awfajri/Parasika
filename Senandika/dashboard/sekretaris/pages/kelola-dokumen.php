<?php
/**
 * MODUL KELOLA DOKUMEN - DASHBOARD SEKRETARIS
 * Halaman utama untuk manajemen arsip dokumen. 
 * Sekretaris dapat melihat, memfilter, mencari, mengedit, dan menghapus dokumen.
 */
require_once '../../../config/database.php';
$page_title = 'Kelola Dokumen - Senandika';
$asset_path = '../../../';
include '../../../includes/head.php';
?>

<div class="d-flex">

  <!-- Sidebar Navigasi Sekretaris -->
  <?php include '../../../includes/sidebar.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">
      <!-- Tombol Toggle Menu Mobile -->
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
        <i class="bi bi-list"></i> Menu
      </button>

      <div class="section-hero">
        <h1>Selamat Datang, Sekertaris!</h1>
      </div>

      <!-- KONTEN UTAMA: TABEL DOKUMEN -->
      <div class="table-card">

        <!-- Toolbar: Fitur Pencarian Live dan Tombol Tambah -->
        <div class="toolbar">
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text"
                  id="searchInput"
                  placeholder="Cari dokumen..."
                  oninput="filterTable(this.value)">
          </div>
          <a href="tambah-dokumen.php" class="btn-add">
            <i class="bi bi-plus-lg"></i>
            Tambah Dokumen
          </a>
        </div>

        <!-- Filter Kategori: Tombol Filter Dinamis -->
        <div class="px-3 pt-3 d-flex gap-2 flex-wrap">
          <?php
          $kategori_list = ['Semua', 'Surat masuk', 'Surat keluar', 'Proposal', 'LPJ', 'AD/ART', 'SK'];
          $active_kat    = $_GET['kategori'] ?? 'Semua';
          foreach ($kategori_list as $kat): ?>
          <a href="?kategori=<?= urlencode($kat) ?>"
              class="badge-kategori <?= ($active_kat === $kat) ? 'active-kat' : '' ?>"
              style="<?= ($active_kat === $kat) ? 'background:var(--primary);color:#fff;' : '' ?>; cursor:pointer; text-decoration:none;">
            <?= htmlspecialchars($kat) ?>
          </a>
          <?php endforeach; ?>
        </div>

        <div class="p-3 pt-3">
          <!-- Tampilan Toast / Alert Sukses jika ada parameter status -->
          <?php if (isset($_GET['status'])): ?>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
                let status = '<?= $_GET['status'] ?>';
                if(status === 'success') Swal.fire('Berhasil!', 'Dokumen berhasil ditambahkan.', 'success');
            });
          </script>
          <?php endif; ?>

          <div class="table-responsive">
            <table class="table-custom" id="dokumenTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Dokumen</th>
                  <th>Kategori</th>
                  <th>Ukuran</th>
                  <th>Waktu Unggah</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                /**
                 * LOGIKA PENAMPILAN DATA DOKUMEN
                 * Mengambil data dari tabel dokumen, JOIN dengan kategori.
                 * Mendukung filter berdasarkan kategori yang dipilih user.
                 */
                $active_kat = $_GET['kategori'] ?? 'Semua';
                $where_clause = "";
                if ($active_kat !== 'Semua') {
                    $stmt_kat = $conn->prepare("SELECT id FROM kategori_arsip WHERE nama_kategori = ?");
                    $stmt_kat->bind_param("s", $active_kat);
                    $stmt_kat->execute();
                    $res_kat = $stmt_kat->get_result();
                    if ($row_kat = $res_kat->fetch_assoc()) {
                        $kat_id = $row_kat['id'];
                        $where_clause = " WHERE d.kategori_id = $kat_id";
                    }
                    $stmt_kat->close();
                }

                $query = "SELECT d.*, k.nama_kategori as kategori 
                          FROM dokumen d 
                          LEFT JOIN kategori_arsip k ON d.kategori_id = k.id 
                          $where_clause
                          ORDER BY d.created_at DESC";
                
                $result = $conn->query($query);
                $i = 1;
                while ($doc = $result->fetch_assoc()): 
                
                  // Konversi Ukuran File dari bytes ke format yang mudah dibaca (KB/MB)
                  $bytes = $doc['ukuran_file'];
                  if ($bytes >= 1048576) {
                      $ukuran = number_format($bytes / 1048576, 2) . ' MB';
                  } elseif ($bytes >= 1024) {
                      $ukuran = number_format($bytes / 1024, 2) . ' KB';
                  } else {
                      $ukuran = $bytes . ' bytes';
                  }
                  
                  $tanggal = date('d M Y', strtotime($doc['created_at']));
                ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($doc['nama_dokumen']) ?></td>
                  <td>
                    <span class="badge-kategori">
                      <?= htmlspecialchars($doc['kategori']) ?>
                    </span>
                  </td>
                  <td><?= $ukuran ?></td>
                  <td><?= $tanggal ?></td>
                  <td>
                    <div class="action-btns">
                      <!-- Tombol Aksi: Lihat File, Edit Data, dan Hapus (via SweetAlert) -->
                      <a href="<?= htmlspecialchars($doc['file_url']) ?>" target="_blank"
                          class="btn-view" title="Lihat">
                        <i class="bi bi-eye-fill"></i>
                      </a>
                      <a href="edit-dokumen.php?id=<?= $doc['id'] ?>"
                          class="btn-edit" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="hapus-dokumen.php?id=<?= $doc['id'] ?>"
                          class="btn-del" title="Hapus">
                        <i class="bi bi-trash3"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <!-- Informasi jumlah data yang ditampilkan -->
          <div class="d-flex justify-content-between align-items-center mt-4 pb-2" style="font-size:0.85rem; color:var(--text-muted); font-family:var(--font-head);">
            <span>Menampilkan <?= $result->num_rows ?> dokumen</span>
            <nav>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active">
                  <a class="page-link" href="#"
                      style="background:var(--primary);border-color:var(--primary);">1</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
              </ul>
            </nav>
          </div>

        </div><!-- /p-3 -->
      </div><!-- /table-card -->

    </div><!-- /content-area -->

  </div><!-- /main-content -->

</div><!-- /d-flex -->

<script>
/**
 * FUNGSI PENCARIAN LIVE (Client-side)
 * Melakukan filtering pada baris tabel secara real-time berdasarkan input keyword.
 * @param {string} query - Kata kunci pencarian
 */
function filterTable(query) {
  const rows = document.querySelectorAll('#dokumenTable tbody tr');
  query = query.toLowerCase();
  rows.forEach(function(row) {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
}
</script>

<?php include '../../../includes/scripts.php'; ?>