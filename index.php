<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Train Booking</title>
  <link rel="stylesheet" href="style.css">
  <style>
    
    .top-nav {
      background-color: #003366;
      padding: 30px 20px; /* Increased padding for height */
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      color: white;
      min-height: 100px; /* Optional: set a minimum height */
    }

    .top-nav .left {
      font-size: 40px;
      font-weight: bold;
    }
    .top-nav .right a {
      color: white;
      margin-left: 15px;
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
    <a href="booking_history.php">Booking History</a>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
  <?php endif; ?>
</div>

</div>

  <section class="search-section">
    <form id="searchForm">
      <input type="text" id="source" placeholder="From Station" required>
      <input type="text" id="destination" placeholder="To Station" required>
      <button type="submit">Search</button>
    </form>
  </section>

<div class="main-content">
    <!-- Sidebar Filters -->
    <div class="sidebar">
      <h5>Filters</h5>
      <button>AC & Non-AC</button>
      <button>AC Only</button>
      <button>Non-AC Only</button>

      <h5>Class</h5>
      <button>Sleeper Class</button>
      <button>3A</button>
      <button>2A</button>
      <button>1A</button>

      <h5>Select Date</h5>
      <button>Oct 1</button>
      <button>Oct 2</button>
      <button>Oct 3</button>
      <button>Oct 4</button>
      <button>Oct 5</button>
      <button>Oct 6</button>
    </div>

  <section class="train-options">
    <div id="train-results"></div>
  </section>

  
  <script>
    function renderTrainCard(train) {
      return `
        <div class="train-card">
          <h3>${train.name} (${train.id})</h3>
          <p><strong>From:</strong> ${train.from_station} → <strong>To:</strong> ${train.to_station}</p>
          <p><strong>Departure:</strong> ${train.departure_time} | <strong>Arrival:</strong> ${train.arrival_time}</p>
          <p><strong>Days:</strong> ${train.days}</p>
          <p><strong>3A:</strong> ₹${train.class_3A_price} - ${train.class_3A_availability}</p>
          <p><strong>2A:</strong> ₹${train.class_2A_price} - ${train.class_2A_availability}</p>
          <form action="book.php" method="POST">
            <input type="hidden" name="train_id" value="${train.id}">
            <button type="submit">Book Now</button>
          </form>
        </div>
      `;
    }

    function loadTrains(source = '', destination = '') {
      let url = 'search_trains.php';
      if (source && destination) {
        url += `?source=${encodeURIComponent(source)}&destination=${encodeURIComponent(destination)}`;
      }

      fetch(url)
        .then(response => response.json())
        .then(data => {
          const container = document.getElementById('train-results');
          container.innerHTML = '';

          if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = '<p>No trains found.</p>';
            return;
          }

          data.forEach(train => {
            container.innerHTML += renderTrainCard(train);
          });
        })
        .catch(error => {
          console.error('Error fetching train data:', error);
        });
    }

    // Load all trains on page load
    window.onload = () => {
      loadTrains();
    };

    // Handle search form
    document.getElementById('searchForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const source = document.getElementById('source').value;
      const destination = document.getElementById('destination').value;
      loadTrains(source, destination);
    });
  </script>

</body>
</html>
