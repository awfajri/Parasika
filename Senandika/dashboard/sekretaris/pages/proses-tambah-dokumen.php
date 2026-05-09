<?php
/**
 * HANDLER UNGGAH DOKUMEN - SENANDIKA
 * File ini menangani proses upload file dari form ke Cloud Storage (Supabase) 
 * dan menyimpan metadatanya ke database MySQL.
 */
session_start();
require_once '../../../config/database.php';
require_once '../../../includes/auth.php';

// Validasi: Pastikan request berasal dari form POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah-dokumen.php");
    exit;
}

// Menangkap data input dari form
$uploader_id  = $_SESSION['user_id'];
$nama_dokumen = trim($_POST['nama_dokumen']);
$kategori_id  = intval($_POST['kategori_id']);
$deskripsi    = trim($_POST['deskripsi'] ?? '');

// Menangkap informasi file dari $_FILES
$file       = $_FILES['file_dokumen'];
$file_name  = $file['name'];
$file_tmp   = $file['tmp_name'];
$file_size  = $file['size'];
$file_error = $file['error'];

/**
 * TAHAP 1: VALIDASI FILE
 * Mengecek ekstensi, error sistem, dan ukuran file (Maks 5MB).
 */
$allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
$file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

if (!in_array($file_ext, $allowed_ext)) {
    header("Location: tambah-dokumen.php?status=ext_error");
    exit;
}

if ($file_error !== 0) {
    header("Location: tambah-dokumen.php?status=upload_error");
    exit;
}

// Batasan ukuran file: 5MB = 5 * 1024 * 1024 bytes
if ($file_size > 5242880) {
    header("Location: tambah-dokumen.php?status=size_error");
    exit;
}

/**
 * TAHAP 2: KONFIGURASI SUPABASE STORAGE
 * Menggunakan REST API Supabase untuk menyimpan file secara cloud.
 */
$supabase_url = 'https://xhsklaikgrvuspytbrrq.supabase.co';
$supabase_key = 'sb_publishable_mho-sfVKTUZUGqMJe6GImA_GeyxciY-';
$bucket_name  = 'senandika_arsip';

// Membuat nama file unik menggunakan timestamp untuk menghindari duplikasi
$new_file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $file_name);
$mime_type     = mime_content_type($file_tmp);

// Membaca konten file mentah
$file_content = file_get_contents($file_tmp);

// Endpoint URL untuk pengunggahan object baru
$upload_url = $supabase_url . '/storage/v1/object/' . $bucket_name . '/' . rawurlencode($new_file_name);

/**
 * TAHAP 3: EKSEKUSI UPLOAD (via cURL)
 */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $upload_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $file_content);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $supabase_key,
    'apikey: ' . $supabase_key,
    'Content-Type: ' . $mime_type
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

/**
 * TAHAP 4: SIMPAN METADATA KE DATABASE
 * Jika upload ke Cloud berhasil (HTTP 200), simpan detail dokumen ke MySQL.
 */
if ($http_code == 200) {
    // Membangun Public URL untuk akses file nantinya
    $public_url = $supabase_url . '/storage/v1/object/public/' . $bucket_name . '/' . rawurlencode($new_file_name);

    $stmt = $conn->prepare("INSERT INTO dokumen (uploader_id, kategori_id, nama_dokumen, deskripsi, nama_file, file_url, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssi", $uploader_id, $kategori_id, $nama_dokumen, $deskripsi, $new_file_name, $public_url, $file_ext, $file_size);
    
    if ($stmt->execute()) {
        // Sukses: Redirect ke halaman kelola dokumen
        header("Location: kelola-dokumen.php?status=success");
    } else {
        // Gagal simpan ke DB lokal
        header("Location: tambah-dokumen.php?status=db_error");
    }
    $stmt->close();
} else {
    // Gagal upload ke Supabase
    header("Location: tambah-dokumen.php?status=supabase_error&code=$http_code");
}
?>
