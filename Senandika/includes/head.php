<?php
/**
 * GLOBAL HEAD COMPONENT
 * File ini berisi elemen <head> standar yang digunakan di seluruh aplikasi Senandika.
 * Memuat metadata, library CSS (Bootstrap, Icons), Font, dan Favicon.
 */
$page_title = $page_title ?? 'Senandika - Parasika';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- CSS Framework & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Typography (Google Fonts) -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <!-- Custom Stylesheet (Dashboard) -->
  <link href="<?= $asset_path ?? './' ?>assets/css/dashboard.css" rel="stylesheet">
  
  <!-- Global Icon / Favicon -->
  <link rel="icon" type="image/png" href="<?= $asset_path ?? './' ?>../assets/img/logo-parasika.png">

  <!-- Third-party Scripts (SweetAlert2) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>