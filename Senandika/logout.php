<?php
// TODO: Tambahkan logika destroy session di sini
session_start();
session_destroy();

header('Location: ./login.php');
exit;
?>
