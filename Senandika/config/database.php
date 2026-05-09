<?php
/**
 * KONFIGURASI DATABASE
 * File ini digunakan untuk menghubungkan aplikasi dengan server database MySQL.
 * Digunakan secara global di seluruh sistem Senandika.
 */

$host     = "localhost";
$dbname   = "db_senandika";
$username = "root";
$password = "";

// Membuat koneksi menggunakan ekstensi MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    // Jika gagal, hentikan eksekusi dan tampilkan pesan error
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Set charset ke utf8mb4 agar mendukung karakter khusus
$conn->set_charset("utf8mb4");
?>
