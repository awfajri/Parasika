<?php
session_start();
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nama_dokumen = $_POST['nama_dokumen'];
    $kategori_nama = $_POST['kategori'];

    // 1. Dapatkan ID Kategori berdasarkan nama kategori yang dipilih
    $stmt = $conn->prepare("SELECT id FROM kategori_arsip WHERE nama_kategori = ?");
    $stmt->bind_param("s", $kategori_nama);
    $stmt->execute();
    $res = $stmt->get_result();
    $kategori_row = $res->fetch_assoc();
    $kategori_id = $kategori_row['id'] ?? null;
    $stmt->close();

    if (!$kategori_id) {
        // Fallback jika kategori tidak ditemukan
        header("Location: edit-dokumen.php?id=$id&error=Kategori tidak valid");
        exit;
    }

    // 2. Query Update Dasar (Tanpa File)
    $sql = "UPDATE dokumen SET nama_dokumen = ?, kategori_id = ? WHERE id = ?";
    $types = "sii";
    $params = [$nama_dokumen, $kategori_id, $id];

    // 3. Handle File Upload (Opsional - sesuaikan dengan logika upload bucket kamu)
    if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
        // TODO: Sisipkan logika upload file ke Supabase/Bucket di sini
        // $file_url = upload_to_bucket($_FILES['file_dokumen']);
        //
        // Jika berhasil upload, perbarui query:
        // $sql = "UPDATE dokumen SET nama_dokumen = ?, kategori_id = ?, file_url = ? WHERE id = ?";
        // $types = "sisi";
        // $params = [$nama_dokumen, $kategori_id, $file_url, $id];
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        header("Location: kelola-dokumen.php?status=success_edit");
    } else {
        header("Location: edit-dokumen.php?id=$id&status=error_edit");
    }
    
    $stmt->close();
} else {
    header("Location: kelola-dokumen.php");
}
?>