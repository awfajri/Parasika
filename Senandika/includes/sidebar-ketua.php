<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
?>
<aside class="sidebar" id="sidebar">
  <!-- Brand -->
  <a href="<?= $asset_path ?>dashboard/ketua/index.php" class="sidebar-brand text-decoration-none">
    <img src="<?= $asset_path ?>assets/img/logo.jpg" alt="Logo Parasika" onerror="this.style.background='#fff';this.src=''">
    <span>Senandika</span>
  </a>
  <!-- Navigation -->
  <nav class="sidebar-nav">
    <a href="<?= $asset_path ?>dashboard/ketua/index.php" class="nav-link-item <?= ($current_page === 'index.php' && $current_dir === 'ketua') ? 'active' : '' ?>">
      <i class="bi bi-house"></i> Dashboard
    </a>
    <a href="<?= $asset_path ?>dashboard/ketua/pages/laporan-arsip.php" class="nav-link-item <?= ($current_page === 'laporan-arsip.php') ? 'active' : '' ?>">
      <i class="bi bi-archive"></i> Laporan Arsip
    </a>
    <a href="<?= $asset_path ?>dashboard/ketua/pages/lihat-anggota.php" class="nav-link-item <?= ($current_page === 'lihat-anggota.php') ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Lihat Anggota
    </a>
  </nav>
  <!-- Logout -->  
  <div class="sidebar-logout">
    <a href="<?= $asset_path ?>logout.php" class="logout-btn">
      <i class="bi bi-box-arrow-right"></i> Log Out
    </a>
  </div>
</aside>