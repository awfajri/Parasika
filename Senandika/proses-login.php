<?php
session_start();

// Tolak akses langsung (bukan dari form POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$npm = trim($_POST['npm'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validasi input kosong
if (empty($npm) || empty($password)) {
    $_SESSION['login_error'] = "NPM dan password tidak boleh kosong.";
    header("Location: login.php");
    exit;
}

// Cari user berdasarkan npm
$stmt = $conn->prepare("SELECT id, nama_lengkap, password, role FROM users WHERE npm = ?");
$stmt->bind_param("s", $npm);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

// Cek apakah user ditemukan dan password cocok
if ($user && password_verify($password, $user['password'])) {

    // Simpan data ke session
    $_SESSION['user_id']      = $user['id'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['role']         = $user['role'];

    // Catat log aktivitas login
    $aksi     = "Login ke sistem";
    $log_stmt = $conn->prepare("INSERT INTO log_aktivitas (user_id, aksi) VALUES (?, ?)");
    $log_stmt->bind_param("is", $user['id'], $aksi);
    $log_stmt->execute();
    $log_stmt->close();

    // Redirect ke dashboard sesuai role
    switch ($user['role']) {
        case 'sekretaris':
            header("Location: dashboard/sekretaris/index.php");
            break;
        case 'ketua':
            header("Location: dashboard/ketua/index.php");
            break;
        case 'anggota':
            header("Location: dashboard/anggota/index.php");
            break;
        default:
            $_SESSION['login_error'] = "Role tidak dikenali.";
            header("Location: login.php");
            break;
    }

} else {
    // Username atau password salah
    $_SESSION['login_error'] = "NPM atau password salah.";
    header("Location: login.php");
}

exit;
?>