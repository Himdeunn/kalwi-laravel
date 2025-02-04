<?php
require './src/config/connection.php'; // Sesuaikan dengan koneksi database kamu

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: categories.php");
    exit();
}

$categories_id = intval($_GET['id']);

// Hapus produk dari database
$stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->bind_param("i", $categories_id);
if ($stmt->execute()) {
    echo "<script>alert('Categories deleted successfully'); window.location.href='categories.php';</script>";
} else {
    echo "<script>alert('Error deleting Categories'); window.history.back();</script>";
}
$stmt->close();
?>
