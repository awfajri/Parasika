<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
?>
<aside class="sidebar" id="sidebar">
  <!-- Brand -->  
  <a href="<?= $asset_path ?>dashboard/sekretaris/index.php" class="sidebar-brand text-decoration-none">
    <img src="<?= $asset_path ?>assets/img/logo.jpg" alt="Logo Parasika" onerror="this.style.background='#fff';this.src=''">
    <span>Senandika</span>
  </a>
  <!-- Navigation -->
  <nav class="sidebar-nav">
    <a href="<?= $asset_path ?>dashboard/sekretaris/index.php" class="nav-link-item <?= ($current_page === 'index.php' && $current_dir === 'sekretaris') ? 'active' : '' ?>">
      <i class="bi bi-house"></i> Dashboard
    </a>
    <a href="<?= $asset_path ?>dashboard/sekretaris/pages/kelola-dokumen.php" class="nav-link-item <?= ($current_page === 'kelola-dokumen.php') ? 'active' : '' ?>">
      <i class="bi bi-archive"></i> Kelola Dokumen
    </a>
    <a href="<?= $asset_path ?>dashboard/sekretaris/pages/kelola-anggota.php" class="nav-link-item <?= ($current_page === 'kelola-anggota.php') ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Kelola Anggota
    </a>
  </nav>
  <!-- Logout -->  
  <div class="sidebar-logout">
    <a href="<?= $asset_path ?>logout.php" class="logout-btn">
      <i class="bi bi-box-arrow-right"></i> Log Out
    </a>
  </div>
</aside>