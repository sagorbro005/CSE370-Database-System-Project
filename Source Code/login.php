<?php
session_start();

// Database connection details
$host = "localhost";
$dbname = "signup";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM signup WHERE Customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['customer_id'] = $user['Customer_id']; // Set session variable
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Customer ID not found!'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
