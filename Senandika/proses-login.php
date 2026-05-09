<?php
/**
 * PROSES LOGIN USER
 * File ini menangani verifikasi kredensial user untuk masuk ke sistem.
 * Alur: Validasi Input -> Cari User -> Verifikasi Password -> Cek Status -> Set Session -> Redirect
 */
session_start();

// Mencegah akses langsung (hanya menerima request POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$npm      = trim($_POST['npm'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validasi input: NPM dan Password wajib diisi
if (empty($npm) || empty($password)) {
    $_SESSION['login_error'] = "NPM dan password tidak boleh kosong.";
    header("Location: login.php");
    exit;
}

/**
 * Mencari data user berdasarkan NPM
 */
$stmt = $conn->prepare("SELECT id, nama_lengkap, password, role, status FROM users WHERE npm = ?");
$stmt->bind_param("s", $npm);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

/**
 * Verifikasi Password dan Status Akun
 * 1. Menggunakan password_verify() untuk mencocokkan password plain dengan hash di database.
 * 2. Akun dengan status 'pending' tidak diperbolehkan masuk.
 */
if ($user && password_verify($password, $user['password'])) {
    
    // Proteksi: Hanya akun yang sudah di-'aktif'-kan oleh Sekretaris yang bisa login
    if ($user['status'] === 'pending') {
        $_SESSION['login_error'] = "Akun Anda belum dikonfirmasi oleh Sekretaris. Silakan tunggu.";
        header("Location: login.php");
        exit;
    }

    // Login Berhasil: Set data penting ke dalam Session
    $_SESSION['user_id']      = $user['id'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['role']         = $user['role'];

    /**
     * Catat Log Aktivitas
     * Setiap keberhasilan login dicatat ke tabel log_aktivitas untuk audit.
     */
    $aksi     = "Login ke sistem";
    $log_stmt = $conn->prepare("INSERT INTO log_aktivitas (user_id, aksi) VALUES (?, ?)");
    $log_stmt->bind_param("is", $user['id'], $aksi);
    $log_stmt->execute();
    $log_stmt->close();

    /**
     * Redirect Berdasarkan Role
     * Mengarahkan user ke dashboard yang sesuai dengan hak aksesnya.
     */
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
    // Login Gagal: Kredensial tidak cocok
    $_SESSION['login_error'] = "NPM atau password salah.";
    header("Location: login.php");
}

exit;
?>