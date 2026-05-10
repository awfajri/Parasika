<?php
/**
 * ============================================================================
 * HANDLER HAPUS DOKUMEN - SENANDIKA
 * ============================================================================
 * File ini bertanggung jawab untuk menangani proses penghapusan dokumen.
 * Alur kerjanya meliputi:
 * 1. Menerima ID dokumen dari request GET.
 * 2. Mengambil nama file fisik dari database lokal berdasarkan ID tersebut.
 * 3. Menghapus file fisik di Cloud Storage (Supabase) via REST API.
 * 4. Menghapus rekaman (metadata) dokumen dari database MySQL lokal.
 * 5. Memberikan feedback (berhasil/gagal) kepada user melalui SweetAlert.
 * ============================================================================
 */

session_start();
require_once '../../../config/database.php';

// Validasi akses: Pastikan parameter 'id' tersedia di URL. Jika tidak, kembalikan ke halaman kelola.
if (!isset($_GET['id'])) {
    header("Location: kelola-dokumen.php");
    exit;
}

// Sanitasi input ID menjadi integer untuk keamanan
$id = intval($_GET['id']);

/**
 * TAHAP 1: AMBIL NAMA FILE DARI DATABASE
 * Kita butuh 'nama_file' untuk memberitahu Supabase file mana yang harus dihapus.
 */
$stmt = $conn->prepare("SELECT nama_file FROM dokumen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokumen = $result->fetch_assoc();
$stmt->close();

// Jika dokumen dengan ID tersebut tidak ditemukan di database
if (!$dokumen) {
    header("Location: kelola-dokumen.php");
    exit;
}

$nama_file = $dokumen['nama_file'];

/**
 * TAHAP 2: HAPUS FILE FISIK DI SUPABASE STORAGE
 * Menggunakan metode `file_get_contents` dengan stream context (No-cURL)
 * agar kompatibel dengan server/hosting yang mematikan fitur cURL.
 */
$supabase_url = 'https://xhsklaikgrvuspytbrrq.supabase.co';
$supabase_key = 'sb_publishable_mho-sfVKTUZUGqMJe6GImA_GeyxciY-';
$bucket_name = 'senandika_arsip';

// Bangun endpoint URL untuk operasi DELETE
$delete_url = $supabase_url . '/storage/v1/object/' . $bucket_name . '/' . rawurlencode($nama_file);

// Konfigurasi HTTP Request
$options = [
    'http' => [
        'method'  => 'DELETE',
        'header'  => "Authorization: Bearer " . $supabase_key . "\r\n" .
                     "apikey: " . $supabase_key . "\r\n",
        // 'ignore_errors' = true memastikan script tidak error fatal jika Supabase gagal merespon
        'ignore_errors' => true 
    ]
];

// Buat context stream dari opsi di atas dan eksekusi request DELETE
$context  = stream_context_create($options);
$response = file_get_contents($delete_url, false, $context);

/**
 * TAHAP 3: HAPUS METADATA DARI DATABASE LOKAL
 * Setelah file di cloud diproses (baik berhasil dihapus atau sudah tidak ada),
 * hapus rekaman dokumen terkait dari tabel 'dokumen'.
 */
$del_stmt = $conn->prepare("DELETE FROM dokumen WHERE id = ?");
$del_stmt->bind_param("i", $id);

?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; }</style>
</head>
<body>
<?php
/**
 * TAHAP 4: EKSEKUSI PENGHAPUSAN LOKAL DAN FEEDBACK UI
 */
if ($del_stmt->execute()) {
    // Jika eksekusi query hapus berhasil
    echo "<script>
        Swal.fire('Berhasil!', 'Dokumen berhasil dihapus dari sistem.', 'success').then(() => {
            window.location.href = 'kelola-dokumen.php';
        });
    </script>";
} else {
    // Jika eksekusi query hapus gagal
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