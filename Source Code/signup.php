<?php
// Database connection details
$host = "localhost"; 
$dbname = "signup"; 
$username = "root"; 
$password = ""; 

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form input data
    $name = $_POST['Name'];
    $customer_id = $_POST['id'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password and confirm password
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='signup.html';</script>";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the data into the signup table
    $sql = "INSERT INTO signup (Name, Customer_id, Phone_number, Password) VALUES (?, ?, ?, ?)";    // placeholder market to prevent SQL Injection

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $name, $customer_id, $phone_number, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect or show success message
        echo "<script>alert('Account created successfully!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='signup.html';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
