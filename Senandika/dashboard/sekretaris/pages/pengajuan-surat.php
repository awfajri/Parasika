<?php
/**
 * MODUL KELOLA PENGAJUAN SURAT - DASHBOARD SEKRETARIS
 * Modul ini digunakan oleh Sekretaris untuk memproses permintaan surat dari anggota.
 * Alur: Pending -> Diproses -> Selesai / Ditolak.
 */
session_start();
$page_title = 'Kelola Pengajuan Surat - Senandika';
$asset_path = '../../../';
require_once '../../../config/database.php';

/**
 * LOGIKA PEMROSESAN STATUS PENGAJUAN
 * Menangani perubahan status (Proses, Selesai, Tolak) via request POST.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id      = (int)$_POST['id'];
    $action  = $_POST['action'];
    $catatan = $_POST['catatan'] ?? null;

    if ($action === 'proses') {
        // Mengubah status menjadi 'diproses'
        $stmt = $conn->prepare("UPDATE pengajuan_surat SET status = 'diproses' WHERE id = ?");
        $stmt->bind_param("i", $id);
    } elseif ($action === 'selesai' || $action === 'tolak') {
        // Mengubah status akhir dan memberikan catatan balik ke pemohon
        $status = ($action === 'selesai') ? 'selesai' : 'ditolak';
        $stmt = $conn->prepare("UPDATE pengajuan_surat SET status = ?, catatan_sekre = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $catatan, $id);
    }
    
    if (isset($stmt) && $stmt->execute()) { 
        header("Location: pengajuan-surat.php?status=success"); 
    }
    exit;
}

include '../../../includes/head.php';
?>

<div class="d-flex">
  <!-- Sidebar Navigasi -->
  <?php include '../../../includes/sidebar.php'; ?>

  <div class="main-content w-100">
    <div class="content-area">
      <!-- Tombol Toggle Sidebar (Mobile) -->
      <button id="sidebarToggle" class="btn btn-light d-md-none mb-3"><i class="bi bi-list"></i> Menu</button>
      
      <div class="section-hero mb-4">
        <h1>Antrean Pengajuan Surat</h1>
        <p>Proses permintaan pembuatan surat dari anggota.</p>
      </div>

      <!-- Notifikasi Sukses -->
      <?php if (isset($_GET['status'])): ?>
      <script>
        document.addEventListener('DOMContentLoaded', () => { Swal.fire('Berhasil!', 'Status pengajuan diperbarui.', 'success'); });
      </script>
      <?php endif; ?>

      <!-- TABEL DAFTAR PENGAJUAN DARI ANGGOTA -->
      <div class="table-card p-3">
        <div class="table-responsive">
          <table class="table-custom">
            <thead>
              <tr>
                <th>Pemohon</th>
                <th>Perihal</th>
                <th>Detail & Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              /**
               * Mengambil data pengajuan dengan JOIN ke tabel users untuk nama pemohon.
               * Diurutkan agar status 'pending' muncul paling atas.
               */
              $res = $conn->query("SELECT p.*, u.nama_lengkap FROM pengajuan_surat p JOIN users u ON p.user_id = u.id ORDER BY p.status = 'pending' DESC, p.created_at DESC");
              while ($row = $res->fetch_assoc()):
                  $badge = 'bg-secondary';
                  if($row['status']=='pending')  $badge = 'bg-warning text-dark';
                  if($row['status']=='diproses') $badge = 'bg-info text-dark';
                  if($row['status']=='selesai')  $badge = 'bg-success';
                  if($row['status']=='ditolak')  $badge = 'bg-danger';
              ?>
              <tr>
                <td class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['perihal']) ?><br><small class="text-muted">Tujuan: <?= htmlspecialchars($row['tujuan']) ?></small></td>
                <td style="max-width:200px; white-space:normal;" class="small"><?= htmlspecialchars($row['keterangan']) ?></td>
                <td><span class="badge <?= $badge ?> rounded-pill px-3"><?= ucfirst($row['status']) ?></span></td>
                <td>
                  <?php if ($row['status'] === 'pending'): ?>
                    <!-- Form Sederhana untuk mulai memproses -->
                    <form method="POST" class="m-0 d-inline-block">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="proses">
                        <button type="submit" class="btn btn-sm btn-primary text-white"><i class="bi bi-gear"></i> Proses</button>
                    </form>
                  <?php elseif ($row['status'] === 'diproses'): ?>
                    <!-- Aksi lanjutan: Selesai atau Tolak (Membutuhkan Catatan) -->
                    <button onclick="tandaiSelesai(<?= $row['id'] ?>)" class="btn btn-sm btn-success text-white mb-1"><i class="bi bi-check-lg"></i> Selesai</button>
                    <button onclick="tolakPengajuan(<?= $row['id'] ?>)" class="btn btn-sm btn-danger text-white"><i class="bi bi-x-lg"></i> Tolak</button>
                  <?php else: ?>
                    <span class="text-muted small">Telah direspon</span>
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

<script>
/**
 * FUNGSI JAVASCRIPT UNTUK AKSI LANJUTAN
 * Menggunakan SweetAlert input untuk meminta catatan/alasan dari Sekretaris.
 */
function sendForm(id, action, textMsg, placeholder) {
    Swal.fire({
        title: textMsg,
        input: 'text',
        inputPlaceholder: placeholder,
        showCancelButton: true,
        confirmButtonColor: (action==='selesai' ? '#198754' : '#DC2626'),
        confirmButtonText: (action==='selesai' ? 'Selesaikan' : 'Tolak'),
        cancelButtonText: 'Batal'
    }).then((result) => {
        if(result.isConfirmed) {
            // Membuat form dinamis untuk pengiriman data via POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="id" value="${id}">
                <input type="hidden" name="action" value="${action}">
                <input type="hidden" name="catatan" value="${result.value || ''}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function tandaiSelesai(id) {
    sendForm(id, 'selesai', 'Selesaikan Pengajuan?', 'Beri catatan (misal: "Dokumen sudah diupload, silakan cari")');
}
function tolakPengajuan(id) {
    sendForm(id, 'tolak', 'Tolak Pengajuan Surat?', 'Berikan alasan penolakan...');
}
</script>

<?php include '../../../includes/scripts.php'; ?>