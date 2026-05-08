<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registrasi.php");
    exit;
}
require_once 'config/database.php';

$nama = trim($_POST['nama_lengkap']);
$npm = trim($_POST['npm']);
$pass1 = $_POST['password'];
$pass2 = $_POST['konfirmasi_password'];

if (empty($nama) || empty($npm) || empty($pass1) || empty($pass2)) {
    $_SESSION['reg_error'] = "Semua kolom harus diisi.";
    header("Location: registrasi.php"); exit;
}

if ($pass1 !== $pass2) {
    $_SESSION['reg_error'] = "Password dan Konfirmasi Password tidak cocok.";
    header("Location: registrasi.php"); exit;
}

// Cek apakah NPM sudah terdaftar
$stmt = $conn->prepare("SELECT id FROM users WHERE npm = ?");
$stmt->bind_param("s", $npm);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $_SESSION['reg_error'] = "NPM sudah terdaftar di sistem.";
    header("Location: registrasi.php"); exit;
}
$stmt->close();

// Simpan dengan status 'pending'
$hashed = password_hash($pass1, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (nama_lengkap, npm, password, role, status) VALUES (?, ?, ?, 'anggota', 'pending')");
$stmt->bind_param("sss", $nama, $npm, $hashed);

if ($stmt->execute()) {
    $_SESSION['reg_success'] = "Registrasi berhasil! Silakan tunggu Sekretaris mengkonfirmasi akun Anda sebelum dapat Login.";
} else {
    $_SESSION['reg_error'] = "Terjadi kesalahan pada sistem.";
}
$stmt->close();

header("Location: registrasi.php");
exit;
?>