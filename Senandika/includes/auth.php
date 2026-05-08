<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ($base_path ?? '../../') . "login.php");
    exit;
}

// Cek role jika $required_role di-set di halaman pemanggil
if (isset($required_role) && $_SESSION['role'] !== $required_role) {
    // Redirect ke dashboard sesuai role masing-masing
    switch ($_SESSION['role']) {
        case 'sekretaris':
            header("Location: " . ($base_path ?? '../../') . "dashboard/sekretaris/index.php");
            break;
        case 'ketua':
            header("Location: " . ($base_path ?? '../../') . "dashboard/ketua/index.php");
            break;
        case 'anggota':
            header("Location: " . ($base_path ?? '../../') . "dashboard/anggota/index.php");
            break;
        default:
            header("Location: " . ($base_path ?? '../../') . "login.php");
            break;
    }
    exit;
}

// Helper: ambil data user yang sedang login
$current_user = [
    'id'           => $_SESSION['user_id'],
    'nama_lengkap' => $_SESSION['nama_lengkap'],
    'role'         => $_SESSION['role'],
];
?>