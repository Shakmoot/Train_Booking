<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
  <style>
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
  </style>
</head>
<body>

<div class="top-nav">
  <div class="left">TrainBooking</div>
  <div class="right">
    <?php if (isset($_SESSION['user_name'])): ?>
      <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
      <a href="index.php">Home</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Log in</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </div>
</div>




<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "train_booking";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$train_id = $_POST['train_id'] ?? null;

if (!$train_id) {
    echo "No train selected.";
    exit;
}

$sql = "SELECT * FROM trains WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $train_id);
$stmt->execute();
$result = $stmt->get_result();
$train = $result->fetch_assoc();

// ✅ Booking confirmation logic
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['passenger_name'])) {
    $user_id = $_SESSION['user_id'];
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
}


if (!$train) {
    echo "Train not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Train - <?php echo htmlspecialchars($train['name']); ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #003366;
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 24px;
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
    .train-info p {
      margin: 5px 0;
    }
    form {
      margin-top: 20px;
    }
    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 15px;
    }
    .form-group {
      flex: 1;
      min-width: 200px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }
    input[type="text"],
    input[type="number"],
    select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
    }
    button:hover {
      background-color: #0056b3;
    }
    .note {
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
    }
    .passenger-block {
      border: 1px solid #ccc;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 6px;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>


  <div class="container">
    <h2>Booking: <?php echo htmlspecialchars($train['name']); ?> (<?php echo $train['id']; ?>)</h2>
    <div class="train-info">
      <p><strong>From:</strong> <?php echo $train['from_station']; ?> → <strong>To:</strong> <?php echo $train['to_station']; ?></p>
      <p><strong>Departure:</strong> <?php echo $train['departure_time']; ?> | <strong>Arrival:</strong> <?php echo $train['arrival_time']; ?></p>
      <p><strong>Days:</strong> <?php echo $train['days']; ?></p>
    </div>

    <div class="note"><strong>Note:</strong> Please submit full name of the passengers instead of initials. ID card will be required during journey.</div>


      
    <form method="POST" action="process_booking.php">
        <input type="hidden" name="train_id" value="<?php echo $train['id']; ?>">

      <h3>Passenger Details</h3>
      <div id="passenger-container">
        <div class="passenger-block">
          <div class="form-row">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" name="passenger_name[]" minlength="3" maxlength="16" required>
            </div>
            <div class="form-group">
              <label>Age</label>
              <input type="number" name="age[]" min="1" max="120" required>
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="gender[]" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
                         </div>
            <div class="form-group">
              <label>Berth Preference</label>
              <select name="berth_preference[]">
                <option value="No Preference">No Preference</option>
                <option value="Lower">Lower</option>
                <option value="Middle">Middle</option>
                <option value="Upper">Upper</option>
              </select>
            </div>
            <div class="form-group">
              <label>Catering Option</label>
              <select name="catering[]">
                <option value="None">None</option>
                <option value="Veg">Veg</option>
                <option value="Non-Veg">Non-Veg</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <button type="button" onclick="addPassenger()">+ Add Another Passenger</button>
      <br><br>
      <button type="submit">Confirm Booking</button>
     
    </form>
  </div>

  <script>
    function addPassenger() {
      const container = document.getElementById('passenger-container');
      const block = container.children[0].cloneNode(true);
      container.appendChild(block);
    }
  </script>

</body>
</html>