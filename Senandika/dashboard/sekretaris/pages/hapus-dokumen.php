<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../includes/auth.php';
include '../../../includes/head.php'; // Panggil head untuk load library sweetalert

if (!isset($_GET['id'])) {
    header("Location: kelola-dokumen.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT nama_file, uploader_id FROM dokumen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokumen = $result->fetch_assoc();
$stmt->close();

if (!$dokumen) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire('Error', 'Dokumen tidak ditemukan!', 'error').then(()=> window.location.href='kelola-dokumen.php'); });</script>";
    exit;
}

$nama_file = $dokumen['nama_file'];
$del_stmt = $conn->prepare("DELETE FROM dokumen WHERE id = ?");
$del_stmt->bind_param("i", $id);

if ($del_stmt->execute()) {
    // Proses hapus di Supabase
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
    curl_exec($ch);
    curl_close($ch);

    echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire('Berhasil!', 'Dokumen berhasil dihapus!', 'success').then(()=> window.location.href='kelola-dokumen.php'); });</script>";
} else {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { Swal.fire('Gagal!', 'Gagal menghapus dokumen!', 'error').then(()=> window.location.href='kelola-dokumen.php'); });</script>";
}

$del_stmt->close();
?>