<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "train_booking";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to book.";
    exit;
}

$user_id = $_SESSION['user_id'];
$train_id = $_POST['train_id'] ?? null;

if (!$train_id || !isset($_POST['passenger_name'])) {
    echo "Invalid booking request.";
    exit;
}

// Fetch train to validate existence
$sql = "SELECT * FROM trains WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $train_id);
$stmt->execute();
$result = $stmt->get_result();
$train = $result->fetch_assoc();

if (!$train) {
    echo "Train not found.";
    exit;
}

// ✅ Booking confirmation logic
$travel_date = date('Y-m-d');
$class = 'SL';

$passengers = [];
for ($i = 0; $i < count($_POST['passenger_name']); $i++) {
    $passengers[] = [
        'name' => $_POST['passenger_name'][$i],
        'age' => $_POST['age'][$i],
        'gender' => $_POST['gender'][$i],
        'berth' => $_POST['berth_preference'][$i],
        'food' => $_POST['catering'][$i]
    ];
}

$passenger_json = json_encode($passengers);
$total_fare = count($passengers) * 500;

$sql = "INSERT INTO booking (user_id, train_id, travel_date, class, status, booking_time, passenger_details, total_fare)
        VALUES (?, ?, ?, ?, 'Confirmed', NOW(), ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssd", $user_id, $train_id, $travel_date, $class, $passenger_json, $total_fare);
$stmt->execute();

$booking_id = $stmt->insert_id;
header("Location: confirm.php?id=$booking_id");
exit();
?>