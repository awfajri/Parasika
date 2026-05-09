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

<link rel="stylesheet" href="assets/css/auth.css">

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
        <div class="password-wrapper">
          <input type="password" name="password" id="passwordInput" class="form-control rounded-pill" placeholder="Buat password" required>
          <button type="button" class="toggle-password" onclick="togglePass('passwordInput', 'eye1')">
            <i class="bi bi-eye" id="eye1"></i>
          </button>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-family:var(--font-head); font-size:.88rem;">Konfirmasi Password</label>
        <div class="password-wrapper">
          <input type="password" name="konfirmasi_password" id="confirmPasswordInput" class="form-control rounded-pill" placeholder="Ulangi password" required>
          <button type="button" class="toggle-password" onclick="togglePass('confirmPasswordInput', 'eye2')">
            <i class="bi bi-eye" id="eye2"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-login">Daftar Sekarang</button>
    </form>
  </div>
</div>
<script>
function togglePass(inputId, eyeId) {
  const input = document.getElementById(inputId);
  const eye = document.getElementById(eyeId);
  if (input.type === 'password') {
    input.type = 'text';
    eye.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    input.type = 'password';
    eye.classList.replace('bi-eye-slash', 'bi-eye');
  }
}
</script>
<?php include 'includes/scripts.php'; ?>