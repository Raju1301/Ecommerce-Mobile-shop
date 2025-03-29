<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$transaction_id = $_GET['payment_id'] ?? 'N/A';

// Fetch user details
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fetch last ordered product
$product_query = "SELECT * FROM products ORDER BY id DESC LIMIT 1";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Order details
$quantity = 1;
$total_price = $product['price'];
$order_date = date('Y-m-d H:i:s');
$status = "Paid";
$payment_method = "Razorpay";

// Insert order into database
$order_query = "INSERT INTO orders (user_id, user_name, product_name, quantity, total_price, order_date, status, product_id, payment_method, transaction_id) 
                VALUES ('$user_id', '{$user['username']}', '{$product['product_name']}', '$quantity', '$total_price', '$order_date', '$status', '{$product['id']}', '$payment_method', '$transaction_id')";

if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);
} else {
    echo "<p>Error processing order.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f5f5f5; color: #333; text-align: center; }
        .container { width: 50%; margin: 50px auto; padding: 30px; background-color: #fff; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); }
        .btn-home { padding: 10px 20px; background-color: #388E3C; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; margin-top: 20px; text-decoration: none; }
        .btn-home:hover { background-color: #2E7D32; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Successful!</h2>
        <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
        <p><strong>Product:</strong> <?php echo $product['product_name']; ?></p>
        <p><strong>Total Price:</strong> ₹<?php echo $total_price; ?></p>
        <p><strong>Payment Method:</strong> Razorpay</p>
        <p><strong>Transaction ID:</strong> <?php echo $transaction_id; ?></p>
        <p><strong>Order Status:</strong> Paid</p>
        <a href="my-orders.php" class="btn-home">Go to My Orders</a>
    </div>
</body>
</html>
