<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT b.*, t.name FROM booking b JOIN trains t ON b.train_id = t.id WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .top-nav {
            background-color: #003366;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            flex-wrap: wrap;
        }
        .top-nav .left {
            font-size: 24px;
            font-weight: bold;
        }
        .top-nav .right {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 16px;
        }
        .top-nav .right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .top-nav .right a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
            color: #003366;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #e6f0ff;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="top-nav">
    <div class="left">TrainBooking</div>
    <div class="right">
        <?php if (isset($_SESSION['user_name'])): ?>
            <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
            <a href="index.php">Home</a>
            <a href="booking_history.php">My Bookings</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <h2>My Booking History</h2>
    <?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your bookings.";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT b.*, t.name FROM booking b JOIN trains t ON b.train_id = t.id WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - TrainBooking</title>
    <link rel="stylesheet" href  <h2>My Booking History</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Train</th>
                <th>Date</th>
                <th>Fare</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['travel_date']) ?></td>
                    <td>₹<?= htmlspecialchars($row['total_fare']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] !== 'Cancelled'): ?>
                            <a href="cancel_booking.php?id=<?= $row['id'] ?>" 
                                onclick="return confirm('Are you sure you want to cancel this booking?');">
                                Cancel
                            </a>

                        <?php else: ?>
                            Cancelled
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</body>
</html>

</div>

</body>
</html>
