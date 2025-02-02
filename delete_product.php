<?php
require './src/config/connection.php'; // Sesuaikan dengan koneksi database kamu

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: product.php");
    exit();
}

$product_id = intval($_GET['id']);

// Hapus produk dari database
$stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
$stmt->bind_param("i", $product_id);
if ($stmt->execute()) {
    echo "<script>alert('Product deleted successfully'); window.location.href='product.php';</script>";
} else {
    echo "<script>alert('Error deleting product'); window.history.back();</script>";
}
$stmt->close();
?>
