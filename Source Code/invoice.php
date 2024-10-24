<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.html");
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

// Fetch customer details from signup table
$customer_id = $_SESSION['customer_id'];
$sql = "SELECT Name, Phone_number FROM signup WHERE Customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($name, $phone_number);
$stmt->fetch();
$stmt->close();

// Billing details (in real application, this would be fetched from a database)
$amount = 3000.00;
$service_charge = 65.00;
$vat = 47.00;
$total_amount = $amount + $service_charge + $vat;
$transaction_id = "TXN" . rand(100000, 999999);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice | Electricity Bill Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&family=Roboto+Slab:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="invoice.css">
</head>
<body>
    <div class="invoice-container">
        <header>
            <h1>Invoice</h1>
            <p>Electricity Bill Management System</p>
        </header>

        <main>
            <section class="customer-info">
                <h2>Customer Information</h2>
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Customer ID:</strong> <?php echo $customer_id; ?></p>
                <p><strong>Phone Number:</strong> <?php echo $phone_number; ?></p>
            </section>

            <section class="billing-info">
                <h2>Billing Information</h2>
                <p><strong>Amount:</strong> <?php echo number_format($amount, 2); ?> BDT</p>
                <p><strong>Service Charge:</strong> <?php echo number_format($service_charge, 2); ?> BDT</p>
                <p><strong>VAT/TAX:</strong> <?php echo number_format($vat, 2); ?> BDT</p>
                <p><strong>Total Amount:</strong> <?php echo number_format($total_amount, 2); ?> BDT</p>
                <p><strong>Transaction ID:</strong> <?php echo $transaction_id; ?></p>
            </section>

            <!-- Print Invoice Button -->
            <section class="print-button">
                <button onclick="window.print()">Print Invoice</button>
            </section>
        </main>

        <footer>
            <p>&copy; 2024 Electricity Provider. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
