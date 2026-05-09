<?php
/**
 * HANDLER EDIT DOKUMEN - SENANDIKA
 * File ini menangani pembaruan data dokumen di database.
 * Alur: Ambil Kategori ID -> Update Metadata -> (Opsional) Update File.
 */
session_start();
require_once '../../../config/database.php';

// Pastikan request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = (int)$_POST['id'];
    $nama_dokumen  = $_POST['nama_dokumen'];
    $kategori_nama = $_POST['kategori'];

    /**
     * TAHAP 1: KONVERSI NAMA KATEGORI KE ID
     * Karena form mengirimkan nama kategori, kita perlu mencari ID kategori_arsip-nya.
     */
    $stmt = $conn->prepare("SELECT id FROM kategori_arsip WHERE nama_kategori = ?");
    $stmt->bind_param("s", $kategori_nama);
    $stmt->execute();
    $res = $stmt->get_result();
    $kategori_row = $res->fetch_assoc();
    $kategori_id  = $kategori_row['id'] ?? null;
    $stmt->close();

    // Validasi jika kategori tidak ditemukan di database
    if (!$kategori_id) {
        header("Location: edit-dokumen.php?id=$id&error=Kategori tidak valid");
        exit;
    }

    /**
     * TAHAP 2: PEMBARUAN DATA (UPDATE)
     * Menyiapkan query update dasar untuk nama dokumen dan kategori.
     */
    $sql    = "UPDATE dokumen SET nama_dokumen = ?, kategori_id = ? WHERE id = ?";
    $types  = "sii";
    $params = [$nama_dokumen, $kategori_id, $id];

    /**
     * TAHAP 3: PENANGANAN FILE BARU (Jika ada)
     * (Catatan: Logika upload file ke Cloud dapat ditambahkan di sini jika diperlukan 
     * untuk mengganti file lama di Storage).
     */
    if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
        // Logika upload tambahan dapat disisipkan di bagian ini
    }

    // Eksekusi Update ke Database
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        // Berhasil: Redirect kembali ke halaman kelola dokumen
        header("Location: kelola-dokumen.php?status=success_edit");
    } else {
        // Gagal: Kembali ke halaman edit dengan pesan error
        header("Location: edit-dokumen.php?id=$id&status=error_edit");
    }
    
    $stmt->close();
} else {
    // Jika akses langsung tanpa POST, arahkan kembali
    header("Location: kelola-dokumen.php");
}
?>