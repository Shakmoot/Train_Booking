<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "train_booking";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$booking_id = $_GET['id'] ?? null;

if (!$booking_id) {
    echo "No booking ID provided.";
    exit;
}

$sql = "SELECT b.*, t.name AS train_name, t.from_station, t.to_station, t.departure_time, t.arrival_time
        FROM booking b
        JOIN trains t ON b.train_id = t.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);  
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Booking not found.";
    exit;
}

$passengers = json_decode($booking['passenger_details'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmation</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: #003366;
      margin-bottom: 20px;
    }
    .info {
      margin-bottom: 20px;
    }
    .info p {
      margin: 5px 0;
    }
    .passenger-list {
      margin-top: 20px;
    }
    .passenger-list li {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Booking Confirmed!</h2>
    <div class="info">
      <p><strong>PNR:</strong> <?php echo $booking['id']; ?></p>
      <p><strong>Train:</strong> <?php echo $booking['train_name']; ?> (<?php echo $booking['train_id']; ?>)</p>
      <p><strong>From:</strong> <?php echo $booking['from_station']; ?> → <strong>To:</strong> <?php echo $booking['to_station']; ?></p>
      <p><strong>Departure:</strong> <?php echo $booking['departure_time']; ?> | <strong>Arrival:</strong> <?php echo $booking['arrival_time']; ?></p>
      <p><strong>Travel Date:</strong> <?php echo $booking['travel_date']; ?></p>
      <p><strong>Class:</strong> <?php echo $booking['class']; ?></p>
      <p><strong>Status:</strong> <?php echo $booking['status']; ?></p>
      <p><strong>Total Fare:</strong> ₹<?php echo $booking['total_fare']; ?></p>
      <a href="index.php">Go to Homepage</a>
    </div>

    <h3>Passenger Details</h3>
    <ul class="passenger-list">
      <?php foreach ($passengers as $p): ?>
        <li>
          <?php echo htmlspecialchars($p['name']); ?> (<?php echo $p['age']; ?> yrs, <?php echo $p['gender']; ?>) - 
          Berth: <?php echo $p['berth']; ?>, Food: <?php echo $p['food']; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

</body>
</html>