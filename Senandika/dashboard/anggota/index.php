<?php
/**
 * DASHBOARD ANGGOTA - SENANDIKA
 * Halaman utama untuk user dengan role 'anggota'.
 * Menampilkan ringkasan statistik jumlah surat yang tersedia di sistem.
 */
$page_title = 'Dashboard Anggota - Senandika';
$required_role = 'anggota';
$asset_path = '../../'; // Path ke folder assets (css/js) relatif dari file ini
$base_path  = '../../';
require_once '../../includes/auth.php'; // Proteksi session agar hanya user login yang bisa akses
include '../../includes/head.php';      // Memuat meta head, bootstrap, dan fonts
?>

    <div class="d-flex">

    <!-- Sidebar Khusus Anggota -->
    <?php include '../../includes/sidebar-anggota.php'; ?>

    <div class="main-content w-100">
        <div class="content-area">
            <!-- Tombol Toggle Sidebar untuk tampilan mobile -->
            <button id="sidebarToggle" class="btn btn-light d-md-none mb-3">
                <i class="bi bi-list"></i> Menu
            </button>   

        <!-- Hero Banner: Sambutan untuk Anggota -->
        <div class="hero-banner">
            <h1>Selamat Datang, Anggota Aktif!</h1>
            <p>Anda dapat mencari dan mengakses berbagai arsip organisasi,<br>
            seperti format proposal, surat keluar dan SK kepengurusan.</p>
            <a href="pages/cari-arsip.php" class="btn-hero">Mulai Pencarian Arsip</a>
        </div>

        

    </div>

    </div>

    <!-- Memuat Library JS (Bootstrap, SweetAlert, Custom Main JS) -->
    <?php include '../../includes/scripts.php'; ?>