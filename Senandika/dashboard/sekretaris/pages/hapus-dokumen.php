<?php
/**
 * HANDLER HAPUS DOKUMEN - SENANDIKA
 * File ini menangani penghapusan file baik dari Cloud Storage (Supabase) 
 * maupun dari database lokal MySQL.
 */
session_start();
require_once '../../../config/database.php';

// Validasi: Pastikan ID dokumen dikirimkan via URL
if (!isset($_GET['id'])) {
    header("Location: kelola-dokumen.php");
    exit;
}

$id = intval($_GET['id']);

/**
 * TAHAP 1: AMBIL NAMA FILE
 * Diperlukan untuk proses penghapusan di storage API.
 */
$stmt = $conn->prepare("SELECT nama_file FROM dokumen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokumen = $result->fetch_assoc();
$stmt->close();

if (!$dokumen) {
    header("Location: kelola-dokumen.php");
    exit;
}

$nama_file = $dokumen['nama_file'];

/**
 * TAHAP 2: HAPUS DARI CLOUD STORAGE (SUPABASE)
 * Menggunakan metode DELETE via cURL.
 */
$supabase_url = 'https://xhsklaikgrvuspytbrrq.supabase.co';
$supabase_key = 'sb_publishable_mho-sfVKTUZUGqMJe6GImA_GeyxciY-';
$bucket_name  = 'senandika_arsip';
$delete_url   = $supabase_url . '/storage/v1/object/' . $bucket_name . '/' . rawurlencode($nama_file);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $delete_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $supabase_key,
    'apikey: ' . $supabase_key
]);
$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

/**
 * TAHAP 3: HAPUS DARI DATABASE LOKAL
 */
$del_stmt = $conn->prepare("DELETE FROM dokumen WHERE id = ?");
$del_stmt->bind_param("i", $id);

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Memuat SweetAlert2 untuk feedback visual yang menarik -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; }</style>
</head>
<body>
<?php
// Eksekusi penghapusan database
if ($del_stmt->execute()) {
    // Berhasil: Tampilkan notifikasi sukses kemudian redirect
    echo "<script>
        Swal.fire('Berhasil!', 'Dokumen berhasil dihapus dari sistem.', 'success').then(() => {
            window.location.href = 'kelola-dokumen.php';
        });
    </script>";
} else {
    // Gagal: Tampilkan notifikasi error
    echo "<script>
        Swal.fire('Gagal!', 'Gagal menghapus dokumen dari database!', 'error').then(() => {
            window.location.href = 'kelola-dokumen.php';
        });
    </script>";
}
$del_stmt->close();
?>
</body>
</html>