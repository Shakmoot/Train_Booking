# Train_Booking 🚂

A web application for booking train tickets, providing a seamless experience for users to search, book, and manage their train journeys.

## 🌟 Project Status

| Feature           | Status      |
|-------------------|-------------|
| **Core Functionality** | **Active**  |
| User Authentication | ✅ Implemented |
| Train Search      | ✅ Implemented |
| Booking           | ✅ Implemented |
| Booking History   | ✅ Implemented |
| Cancellation      | ✅ Implemented |
| **Development**   | **Ongoing** |

## 🚀 Features

- **User Authentication:** Secure registration and login system to manage user accounts.
- **Train Search:** Easily search for trains based on source and destination.
- **Real-time Availability:** (Assumed based on typical booking systems, actual implementation might vary) Display train availability and details.
- **Booking Management:** Book tickets for multiple passengers with options for catering and berth preferences.
- **Booking History:** View a comprehensive list of past and upcoming bookings.
- **Cancellation:** Option to cancel bookings (with confirmation).
- **Responsive Design:** (Inferred from CSS) Aims to provide a good user experience across different devices.

## 📚 Table of Contents

- [Project Title & Badges](#train_booking-🚂)
- [Description](#-description)
- [Table of Contents](#-table-of-contents)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Usage](#-usage)
- [How to Use](#-how-to-use)
- [Project Structure](#-project-structure)
- [Contributing](#-contributing)
- [License](#-license)
- [Important Links](#-important-links)
- [Footer](#-footer)

## 🛠️ Tech Stack

- **Language:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL (Inferred from `db_connect.php` and connection strings)
- **Server:** Apache (Inferred from PHP usage, common for PHP applications)

## 💡 Installation

This project requires a local development environment with PHP and a MySQL database.

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/Shakmoot/Train_Booking.git
    cd Train_Booking
    ```

2.  **Set up the Database:**
    - Create a MySQL database named `train_booking`.
    - Import the necessary database schema. (Note: The schema is not provided, so manual creation of tables like `users`, `trains`, `booking` will be required based on the code's usage.)
    - **Example Table Structures (based on code):**
      - `users`: `id` (INT, PK, AI), `name` (VARCHAR), `email` (VARCHAR, UNIQUE), `password` (VARCHAR)
      - `trains`: `id` (INT, PK, AI), `name` (VARCHAR), `from_station` (VARCHAR), `to_station` (VARCHAR), `departure_time` (TIME), `arrival_time` (TIME), `days` (VARCHAR), `class_3A_price` (DECIMAL), `class_3A_availability` (VARCHAR), `class_2A_price` (DECIMAL), `class_2A_availability` (VARCHAR)
      - `booking`: `id` (INT, PK, AI), `user_id` (INT, FK to users), `train_id` (INT, FK to trains), `travel_date` (DATE), `class` (VARCHAR), `status` (VARCHAR), `booking_time` (DATETIME), `passenger_details` (JSON), `total_fare` (DECIMAL)

3.  **Configure Database Connection:**
    - Edit the `db_connect.php` file to match your database credentials:
      ```php
      <?php
      $servername = "localhost";
      $username = "root"; // Your database username
      $password = "";     // Your database password
      $dbname = "train_booking"; // Your database name

      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      ?>
      ```

4.  **Run a Local Server:**
    - Use PHP's built-in server or a web server like Apache/Nginx.
    - If using PHP's built-in server, navigate to the project directory in your terminal and run:
      ```bash
      php -S localhost:8000
      ```

5.  **Access the Application:**
    - Open your web browser and go to `http://localhost:8000` (or your configured server address).

## 📖 Usage

This application is a functional train booking system. Users can:

1.  **Register/Login:** Create a new account or log in with existing credentials.
2.  **Search for Trains:** Use the search bar on the homepage (`index.php`) to find trains between two stations.
3.  **View Train Details:** Browse the search results to see train names, routes, timings, and class availability/pricing.
4.  **Book Tickets:** Select a train and proceed to the booking page (`book.php`) to enter passenger details (name, age, gender, food preference, berth preference).
5.  **Confirm Booking:** Review booking details and finalize the transaction. A confirmation page (`confirm.php`) will display the PNR and passenger information.
6.  **View Booking History:** Access your booking history (`booking_history.php`) to see all your past bookings and their status.
7.  **Cancel Bookings:** Cancel eligible bookings directly from the booking history page.

## 📝 How to Use

### 1. Registration & Login

- Navigate to `register.php` to create a new user account.
- Use `login.php` to sign in to your account.

### 2. Searching and Booking

- On the homepage (`index.php`), enter your **Source** and **Destination** stations and click **Search**.
- The results will display available trains.
- Click the **Book Now** button for the desired train.
- On the booking page (`book.php`), fill in the details for each passenger.
- Click **Confirm Booking** to finalize.

### 3. Managing Bookings

- After booking, you'll be redirected to the confirmation page (`confirm.php`).
- You can view your booking history by navigating to `booking_history.php`.
- From the history page, you can cancel your bookings by clicking the **Cancel** link (if the booking is eligible).

## 📁 Project Structure

```
Train_Booking/
├── book.php                # Handles train booking form and processing
├── booking_history.php     # Displays the user's booking history
├── cancel_booking.php      # Handles booking cancellation logic
├── confirm.php             # Displays booking confirmation details
├── db_connect.php          # Database connection handler
├── index.php               # Homepage: displays train search and results
├── login.php               # User login page
├── logout.php              # Handles user logout
├── process_booking.php     # Processes the submitted booking data
├── register.php            # User registration page
├── search_trains.php       # API endpoint for searching trains
├── style.css               # Global CSS styles
├── README.md               # Project README file
└── ... (other potential files like images, etc.)
```

## 🔗 API Reference

- **`search_trains.php`**:
  - **GET `/search_trains.php?source=XXX&destination=YYY`**: Retrieves a list of trains traveling from `source` to `destination`.
  - **GET `/search_trains.php`**: Retrieves all trains in the system.
  - **Response:** JSON array of train objects.

## 🤝 Contributing

Contributions are welcome! If you'd like to contribute, please fork the repository and submit a pull request. You can also open an issue to discuss potential changes or report bugs.

## 📜 License

This project is not specified with a license. Please refer to the repository owner for licensing information.

## 🔗 Important Links

- **GitHub Repository:** [Shakmoot/Train_Booking](https://github.com/Shakmoot/Train_Booking)

## 📝 Footer

© 2023 Train_Booking. All rights reserved.

- **Repository:** [Train_Booking](https://github.com/Shakmoot/Train_Booking)
- **Author:** Shakmoot
- **Contact:** [Please refer to GitHub profile for contact information]

Made with ❤️. Please consider starring ⭐, forking 🍴, and reporting issues 🐛 if you find this project useful!


---
**<p align="center">Generated by [ReadmeCodeGen](https://www.readmecodegen.com/)</p>**