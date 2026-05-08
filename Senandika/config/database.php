<?php
$host = "localhost";
$dbname = "db_senandika";
$username = "root";
$password = "";

// Membuat koneksi MySQLi
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
