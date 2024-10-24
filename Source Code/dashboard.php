<?php
session_start();

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$host = "localhost";
$dbname = "signup";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the customer data from the database using the session customer_id
$customer_id = $_SESSION['customer_id'];
$sql = "SELECT Name, Customer_id, Phone_number FROM signup WHERE Customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->store_result(); // Make sure to store the result

if ($stmt->num_rows > 0) {
    $stmt->bind_result($name, $customer_id, $phone_number);
    $stmt->fetch();
} else {
    echo "No user found with this ID.";
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Management Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Roboto+Slab:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css"> 
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="navbar">
        <div class="logo">
            <h1>Electricity Bill Management System</h1>
        </div>
        <div class="nav-links">
            <a href="profile.php">Profile</a>
            <a href="billing.html">Billing</a>
            <a href="help.html">Help</a>
            <a href="login.html" class="logout">Logout</a>
        </div>
    </div>

    <!-- Dashboard Heading -->
    <div class="dashboard-heading">
        <h2>Welcome to Electricity Bill Management System</h2>
        <h3>Dashboard</h3>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard">
        <div class="customer-info-container">
            <!-- First Row -->
            <div class="customer-info-box">
                <p><strong>Customer Name:</strong> <span><?php echo $name; ?></span></p>
            </div>
            <div class="customer-info-box">
                <p><strong>Customer ID:</strong> <span><?php echo $customer_id; ?></span></p>
            </div>
        </div>

        <div class="customer-info-container">
            <!-- Second Row -->
            <div class="customer-info-box">
                <p><strong>Phone Number:</strong> <span><?php echo $phone_number; ?></span></p>
            </div>
            <div class="customer-info-box">
                <p><strong>Current Capacity:</strong> <span>150 kWh</span></p>
            </div>
        </div>

        <div class="customer-info-container">
            <!-- Third Row (Newly Added Information) -->
            <div class="customer-info-box">
                <p><strong>Meter Rent:</strong> <span>460 Taka</span></p>
            </div>
            <div class="customer-info-box">
                <p><strong>Current Month:</strong> <span>September 2024</span></p>
            </div>
        </div>
    </div>
</body>
</html>
