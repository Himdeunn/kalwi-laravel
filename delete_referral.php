<?php
require './src/config/connection.php'; // Sesuaikan dengan koneksi database kamu

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: referral.php");
    exit();
}

$referral_id = intval($_GET['id']);

// Hapus referral dari database
$stmt = $conn->prepare("DELETE FROM code_referral WHERE id = ?");
$stmt->bind_param("i", $referral_id);
if ($stmt->execute()) {
    echo "<script>alert('Referral deleted successfully'); window.location.href='referral.php';</script>";
} else {
    echo "<script>alert('Error deleting referral'); window.history.back();</script>";
}
$stmt->close();
?>
