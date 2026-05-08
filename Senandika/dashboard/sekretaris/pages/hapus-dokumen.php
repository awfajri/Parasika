<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location: kelola-dokumen.php");
    exit;
}

$id = intval($_GET['id']);

// Dapatkan data dokumen (terutama nama_file)
$stmt = $conn->prepare("SELECT nama_file, uploader_id FROM dokumen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokumen = $result->fetch_assoc();
$stmt->close();

if (!$dokumen) {
    echo "<script>alert('Dokumen tidak ditemukan!'); window.location.href='kelola-dokumen.php';</script>";
    exit;
}

// Cek akses (contoh: sekretaris boleh hapus semua, atau pemilik dokumen)
if ($_SESSION['role'] !== 'sekretaris' && $_SESSION['role'] !== 'ketua' && $_SESSION['user_id'] !== $dokumen['uploader_id']) {
    echo "<script>alert('Anda tidak memiliki akses untuk menghapus dokumen ini!'); window.location.href='kelola-dokumen.php';</script>";
    exit;
}

$nama_file = $dokumen['nama_file'];

// Hapus dari database
$del_stmt = $conn->prepare("DELETE FROM dokumen WHERE id = ?");
$del_stmt->bind_param("i", $id);

if ($del_stmt->execute()) {
    // Hapus dari Supabase Storage
    $supabase_url = 'https://xhsklaikgrvuspytbrrq.supabase.co';
    $supabase_key = 'sb_publishable_mho-sfVKTUZUGqMJe6GImA_GeyxciY-';
    $bucket_name = 'senandika_arsip';
    $delete_url = $supabase_url . '/storage/v1/object/' . $bucket_name . '/' . rawurlencode($nama_file);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $delete_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $supabase_key,
        'apikey: ' . $supabase_key
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<script>alert('Dokumen berhasil dihapus!'); window.location.href='kelola-dokumen.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus dokumen dari database!'); window.location.href='kelola-dokumen.php';</script>";
}

$del_stmt->close();
?>
