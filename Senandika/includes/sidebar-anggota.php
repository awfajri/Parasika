    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    $current_dir  = basename(dirname($_SERVER['PHP_SELF']));
    ?>
    <aside class="sidebar" id="sidebar">

    <!-- Brand -->
    <a href="<?= $asset_path ?>dashboard/anggota/index.php" class="sidebar-brand text-decoration-none">
        <img src="<?= $asset_path ?>assets/img/logo.jpg" alt="Logo Parasika"
            onerror="this.style.background='#fff';this.src=''">
        <span>Senandika</span>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <a href="<?= $asset_path ?>dashboard/anggota/index.php"
        class="nav-link-item <?= ($current_page === 'index.php' && $current_dir === 'anggota') ? 'active' : '' ?>">
        <i class="bi bi-house"></i>
        Dashboard
        </a>

        <a href="<?= $asset_path ?>dashboard/anggota/pages/cari-arsip.php"
        class="nav-link-item <?= ($current_page === 'cari-arsip.php') ? 'active' : '' ?>">
        <i class="bi bi-search"></i>
        Cari Arsip
        </a>
    </nav>

    <!-- Logout -->
    <div class="sidebar-logout">
        <a href="<?= $asset_path ?>logout.php" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i>
        Log Out
        </a>
    </div>

    </aside>