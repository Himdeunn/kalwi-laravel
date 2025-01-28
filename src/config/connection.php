<?php
// Konfigurasi koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "kalwi-laravel";

// Melakukan koneksi ke database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set zona waktu default menjadi Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Menonaktifkan error reporting untuk menghindari tampilan pesan error di halaman
error_reporting(0);
?>
