<?php
session_start();
require './src/config/connection.php'; // Sesuaikan dengan koneksi database kamu

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Tangkap data dari form
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi input tidak boleh kosong
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo "<script>alert('All fields are required'); window.history.back();</script>";
    exit();
}

// Validasi apakah password baru dan konfirmasi password cocok
if ($new_password !== $confirm_password) {
    echo "<script>alert('New password and confirm password do not match'); window.history.back();</script>";
    exit();
}

// Ambil password lama dari database
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

// Periksa apakah password lama cocok
if (!password_verify($current_password, $hashed_password)) {
    echo "<script>alert('Incorrect current password'); window.history.back();</script>";
    exit();
}

// Hash password baru
$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password baru ke database
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $new_hashed_password, $user_id);
if ($stmt->execute()) {
    echo "<script>alert('Password changed successfully'); window.location.href='admindashboard.php';</script>";
} else {
    echo "<script>alert('Error updating password'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
