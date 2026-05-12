<?php
/**
 * HANDLER UNGGAH DOKUMEN - SENANDIKA
 * File ini menangani proses upload dokumen baru oleh user (Sekretaris).
 * Alur kerjanya meliputi:
 * 1. Validasi request (hanya POST yang diizinkan).
 * 2. Ekstraksi data dari form teks dan file upload ($_FILES).
 * 3. Validasi file (ekstensi yang diizinkan, ukuran file max 5MB).
 * 4. Persiapan data untuk upload ke Supabase Storage (penentuan nama file unik dan MIME type).
 * 5. Mengirim file ke REST API Supabase menggunakan `file_get_contents` (stream context).
 * 6. Memeriksa respon HTTP dari Supabase.
 * 7. Jika berhasil (HTTP 200), menyimpan metadata (termasuk URL publik file) ke database MySQL.
 * 8. Redirect dengan status sukses atau menampilkan pesan error jika gagal.
 */

// Menampilkan error untuk mempermudah debugging (sebaiknya dimatikan di production murni)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../../config/database.php';
require_once '../../../includes/auth.php';

// Validasi HTTP Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah-dokumen.php");
    exit;
}

// Ambil data teks dari form
$uploader_id  = $_SESSION['user_id'];
$nama_dokumen = trim($_POST['nama_dokumen']);
$kategori_id  = intval($_POST['kategori_id']);
$deskripsi    = trim($_POST['deskripsi'] ?? '');

// Ambil data file
$file       = $_FILES['file_dokumen'];
$file_name  = $file['name'];
$file_tmp   = $file['tmp_name'];
$file_size  = $file['size'];
$file_error = $file['error'];

/**
 * TAHAP 1: VALIDASI FILE
 */
$allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
$file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

// Validasi Ekstensi
if (!in_array($file_ext, $allowed_ext)) {
    header("Location: tambah-dokumen.php?status=ext_error"); 
    exit;
}
// Validasi Error Upload dari Sistem
if ($file_error !== 0) {
    header("Location: tambah-dokumen.php?status=upload_error"); 
    exit;
}
// Validasi Ukuran (Maksimal 5MB = 5 * 1024 * 1024 bytes)
if ($file_size > 5242880) { 
    header("Location: tambah-dokumen.php?status=size_error"); 
    exit;
}

/**
 * TAHAP 2: PERSIAPAN UPLOAD KE SUPABASE
 */
$supabase_url = 'https://xxx.supabase.co';
$supabase_key = 'sb_publishable_xxx';
$bucket_name  = 'xxx';

// Membuat nama file yang unik untuk mencegah overwrite file dengan nama sama
// Format: timestamp_namafile-bersih.ext
$new_file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $file_name);

// Penentuan MIME Type secara aman
if (function_exists('mime_content_type')) {
    $mime_type = mime_content_type($file_tmp);
} else {
    $mime_type = isset($file['type']) ? $file['type'] : 'application/octet-stream';
}

// Membaca isi file fisik yang akan diunggah
$file_content = file_get_contents($file_tmp);
// Membangun endpoint URL tujuan
$upload_url = $supabase_url . '/storage/v1/object/' . $bucket_name . '/' . rawurlencode($new_file_name);

/**
 * TAHAP 3: EKSEKUSI UPLOAD (Menggunakan file_get_contents sebagai pengganti cURL)
 */
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Authorization: Bearer " . $supabase_key . "\r\n" .
                     "apikey: " . $supabase_key . "\r\n" .
                     "Content-Type: " . $mime_type . "\r\n",
        'content' => $file_content,
        'ignore_errors' => true // Mencegah script PHP berhenti (fatal error) jika Supabase mengembalikan status error (misal 400 Bad Request)
    ]
];

$context  = stream_context_create($options);
// Mengirim file
$response = file_get_contents($upload_url, false, $context);

// Mengekstrak kode HTTP response (misal: 200, 404, 500) dari header yang dikembalikan Supabase
$http_code = 0;
if (isset($http_response_header) && isset($http_response_header[0])) {
    preg_match('#HTTP/\d+\.\d+ (\d+)#', $http_response_header[0], $matches);
    if (isset($matches[1])) {
        $http_code = intval($matches[1]);
    }
}

// TAHAP 4: SIMPAN METADATA KE LOKAL
if ($http_code == 200) {
    // Membangun URL Publik agar file bisa diakses/diunduh oleh user nanti
    $public_url = $supabase_url . '/storage/v1/object/public/' . $bucket_name . '/' . rawurlencode($new_file_name);

    // Menyimpan rekam jejak file tersebut ke database
    $stmt = $conn->prepare("INSERT INTO dokumen (uploader_id, kategori_id, nama_dokumen, deskripsi, nama_file, file_url, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssi", $uploader_id, $kategori_id, $nama_dokumen, $deskripsi, $new_file_name, $public_url, $file_ext, $file_size);
    
    if ($stmt->execute()) {
        header("Location: kelola-dokumen.php?status=success");
    } else {
        die("Database Error: " . $stmt->error);
    }
    $stmt->close();
} else {
    // Jika upload ke cloud gagal, hentikan proses dan tampilkan pesan diagnostik
    die("<h3>Supabase Gagal Mengunggah!</h3>
         <p><b>HTTP Code:</b> $http_code</p>
         <p><b>Response:</b> $response</p>");
}
?>