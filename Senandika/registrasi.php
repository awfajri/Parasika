<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$error = $_SESSION['reg_error'] ?? '';
$success = $_SESSION['reg_success'] ?? '';
unset($_SESSION['reg_error'], $_SESSION['reg_success']);

$page_title = 'Registrasi - Senandika';
$asset_path = './';
include 'includes/head.php';
?>

<style>
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url("./assets/img/hero-3.jpeg") center/cover no-repeat;
    padding: 24px;
  }
.btn-back { position: fixed; top: 24px; left: 28px; display: flex; align-items: center; gap: 8px; background: #fff; color: var(--primary); font-family: var(--font-head); font-weight: 600; font-size: 0.88rem; padding: 9px 18px; border-radius: 50px; text-decoration: none; border: 1.5px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: all 0.18s ease; z-index: 100; }
.btn-back:hover { background: #FFF0F3; border-color: var(--primary); color: var(--primary); transform: translateX(-2px); }
.login-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: var(--radius); box-shadow: var(--shadow-md); padding: 48px 44px; width: 100%; max-width: 460px; border: 1px solid rgba(255, 255, 255, 0.2); }
.login-title { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; text-align: center; color: var(--primary); margin-bottom: 6px; }
.login-sub { text-align: center; font-size: 0.88rem; color: var(--text-muted); margin-bottom: 28px; }
.btn-login { background: var(--primary); color: #fff; font-family: var(--font-head); font-weight: 700; font-size: 0.95rem; padding: 12px; border-radius: 50px; border: none; cursor: pointer; width: 100%; transition: background 0.18s; }
.btn-login:hover { background: var(--primary-dark); }
</style>

<a href="login.php" class="btn-back">
  <i class="bi bi-arrow-left"></i> Ke Halaman Login
</a>

<div class="login-wrapper">
  <div class="login-card">
    <div class="login-title">Daftar Akun</div>
    <div class="login-sub">Bergabunglah dengan Sistem Senandika</div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="font-size:0.87rem;">
        <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success rounded-3 py-2 px-3 mb-3" style="font-size:0.87rem;">
        <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form action="proses-registrasi.php" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control rounded-pill" placeholder="Masukkan nama" required autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">NPM</label>
        <input type="text" name="npm" class="form-control rounded-pill" placeholder="Masukkan NPM" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">Password</label>
        <input type="password" name="password" class="form-control rounded-pill" placeholder="Buat password" required>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">Konfirmasi Password</label>
        <input type="password" name="konfirmasi_password" class="form-control rounded-pill" placeholder="Ulangi password" required>
      </div>

      <button type="submit" class="btn-login">Daftar Sekarang</button>
    </form>
  </div>
</div>
<?php include 'includes/scripts.php'; ?>