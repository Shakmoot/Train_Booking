<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit;
}

$booking_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$booking_id) {
    echo "Invalid request.";
    exit;
}

// Update booking status to 'Cancelled' only if it belongs to the logged-in user
$sql = "UPDATE booking SET status = 'Cancelled' WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();

header("Location: booking_history.php");
exit;
?>