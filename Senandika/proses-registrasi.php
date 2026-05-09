<?php
/**
 * PROSES REGISTRASI USER BARU
 * File ini menangani pendaftaran anggota baru ke dalam sistem.
 * Alur: Validasi Input -> Cek Duplikasi NPM -> Hash Password -> Simpan ke Database (Pending)
 */
session_start();

// Mencegah akses langsung ke file melalui URL (hanya menerima request POST dari form)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registrasi.php");
    exit;
}

require_once 'config/database.php';

// Mengambil data dari form dan membersihkan whitespace
$nama  = trim($_POST['nama_lengkap']);
$npm   = trim($_POST['npm']);
$pass1 = $_POST['password'];
$pass2 = $_POST['konfirmasi_password'];

// Validasi: Pastikan tidak ada kolom yang kosong
if (empty($nama) || empty($npm) || empty($pass1) || empty($pass2)) {
    $_SESSION['reg_error'] = "Semua kolom harus diisi.";
    header("Location: registrasi.php"); 
    exit;
}

// Validasi: Pastikan password dan konfirmasi password identik
if ($pass1 !== $pass2) {
    $_SESSION['reg_error'] = "Password dan Konfirmasi Password tidak cocok.";
    header("Location: registrasi.php"); 
    exit;
}

/**
 * Validasi Duplikasi NPM
 * Mengecek apakah NPM sudah pernah terdaftar sebelumnya untuk menghindari akun ganda.
 */
$stmt = $conn->prepare("SELECT id FROM users WHERE npm = ?");
$stmt->bind_param("s", $npm);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['reg_error'] = "NPM sudah terdaftar di sistem.";
    header("Location: registrasi.php"); 
    exit;
}
$stmt->close();

/**
 * Penyimpanan Data ke Database
 * 1. Password di-hash menggunakan BCRYPT (PASSWORD_DEFAULT) untuk keamanan.
 * 2. Status default adalah 'pending' karena memerlukan konfirmasi dari Sekretaris.
 * 3. Role default adalah 'anggota'.
 */
$hashed = password_hash($pass1, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (nama_lengkap, npm, password, role, status) VALUES (?, ?, ?, 'anggota', 'pending')");
$stmt->bind_param("sss", $nama, $npm, $hashed);

if ($stmt->execute()) {
    // Berhasil mendaftar
    $_SESSION['reg_success'] = "Registrasi berhasil! Silakan tunggu Sekretaris mengkonfirmasi akun Anda sebelum dapat Login.";
} else {
    // Gagal karena masalah teknis database
    $_SESSION['reg_error'] = "Terjadi kesalahan pada sistem.";
}

$stmt->close();
$conn->close();

// Kembali ke halaman registrasi untuk menampilkan pesan (sukses/gagal)
header("Location: registrasi.php");
exit;
?>