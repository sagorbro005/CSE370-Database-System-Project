<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
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

// Fetch the customer data from the session and database
$customer_id = $_SESSION['customer_id'];

$sql = "SELECT s.Name, s.Customer_id, s.Phone_number, p.NID, p.Address, p.Email 
        FROM signup s 
        LEFT JOIN profile p ON s.Customer_id = p.Customer_id 
        WHERE s.Customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($name, $customer_id, $phone_number, $nid, $address, $email);
$stmt->fetch();
$stmt->close();

// Handle form submission to update profile data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $nid = $_POST['nid'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Update the profile information in both tables
    $update_profile_sql = "INSERT INTO profile (Customer_id, NID, Address, Email) 
                           VALUES (?, ?, ?, ?) 
                           ON DUPLICATE KEY UPDATE NID = ?, Address = ?, Email = ?";
    $update_stmt = $conn->prepare($update_profile_sql);
    $update_stmt->bind_param("issssss", $customer_id, $nid, $address, $email, $nid, $address, $email);
    $update_stmt->execute();
    $update_stmt->close();

    // Update signup table for name and phone number
    $update_signup_sql = "UPDATE signup SET Name = ?, Phone_number = ? WHERE Customer_id = ?";
    $update_signup_stmt = $conn->prepare($update_signup_sql);
    $update_signup_stmt->bind_param("ssi", $name, $phone_number, $customer_id);
    $update_signup_stmt->execute();
    $update_signup_stmt->close();

    echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Electricity Bill Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Roboto+Slab:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="navbar">
        <div class="logo">
            <h1>Electricity Bill Management System</h1>
        </div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="billing.html">Billing</a>
            <a href="help.html">Help</a>
            <a href="login.html" class="logout">Logout</a>
        </div>
    </div>

    <!-- Profile Heading -->
    <div class="profile-heading">
        <h2>Your Profile</h2>
    </div>

    <!-- Profile Form -->
    <div class="profile-container">
        <form action="profile.php" method="POST" class="profile-form">
            <div class="form-group">
                <label for="customer_id">Customer ID</label>
                <input type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>" readonly> <!-- The htmlspecialchars function prevents any malicious code from being executed. -->
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </div>
            <div class="form-group">
                <label for="nid">NID</label>
                <input type="text" id="nid" name="nid" value="<?php echo htmlspecialchars($nid); ?>" placeholder="Enter NID">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" placeholder="Enter your address"><?php echo htmlspecialchars($address); ?></textarea>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email">
            </div>
            <div class="form-buttons">
                <button type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
