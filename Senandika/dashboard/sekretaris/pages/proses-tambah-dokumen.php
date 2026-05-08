<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../includes/auth.php';

// Cek apakah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah-dokumen.php");
    exit;
}

$uploader_id = $_SESSION['user_id'];
$nama_dokumen = trim($_POST['nama_dokumen']);
$kategori_id = intval($_POST['kategori_id']);
$deskripsi = trim($_POST['deskripsi'] ?? '');

// Info File
$file = $_FILES['file_dokumen'];
$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
$file_size = $file['size'];
$file_error = $file['error'];

// Validasi File
$allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

if (!in_array($file_ext, $allowed_ext)) {
    echo "<script>alert('Ekstensi file tidak diizinkan!'); window.location.href='tambah-dokumen.php';</script>";
    exit;
}

if ($file_error !== 0) {
    echo "<script>alert('Terjadi kesalahan saat mengunggah file!'); window.location.href='tambah-dokumen.php';</script>";
    exit;
}

// 5MB = 5 * 1024 * 1024 bytes
if ($file_size > 5242880) {
    echo "<script>alert('Ukuran file maksimal 5MB!'); window.location.href='tambah-dokumen.php';</script>";
    exit;
}

// Supabase Config
$supabase_url = 'https://xhsklaikgrvuspytbrrq.supabase.co';
$supabase_key = 'sb_publishable_mho-sfVKTUZUGqMJe6GImA_GeyxciY-';
$bucket_name = 'senandika_arsip';

// Nama file unik
$new_file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $file_name);
$mime_type = mime_content_type($file_tmp);

// Baca konten file
$file_content = file_get_contents($file_tmp);

// Endpoint URL upload
$upload_url = $supabase_url . '/storage/v1/object/' . $bucket_name . '/' . rawurlencode($new_file_name);

// Proses upload ke Supabase via cURL
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

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    // Berhasil upload, dapatkan public URL
    $public_url = $supabase_url . '/storage/v1/object/public/' . $bucket_name . '/' . rawurlencode($new_file_name);

    // Insert ke database
    $stmt = $conn->prepare("INSERT INTO dokumen (uploader_id, kategori_id, nama_dokumen, deskripsi, nama_file, file_url, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssi", $uploader_id, $kategori_id, $nama_dokumen, $deskripsi, $new_file_name, $public_url, $file_ext, $file_size);
    
    if ($stmt->execute()) {
        echo "<script>alert('Dokumen berhasil ditambahkan!'); window.location.href='kelola-dokumen.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan ke database!'); window.location.href='tambah-dokumen.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Gagal mengunggah ke Supabase! HTTP Code: $http_code'); window.location.href='tambah-dokumen.php';</script>";
}
?>
