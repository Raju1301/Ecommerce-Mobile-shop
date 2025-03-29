<?php
session_start();
include("db.php");

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Validate order_id from GET
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("Invalid Order ID.");
}

$order_id = intval($_GET['order_id']);

// Fetch the order details
$stmt = $conn->prepare("SELECT order_id, delivery_status FROM orders WHERE order_id = ?");
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_status = trim($_POST["status"]);

    // Update order status
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    if (!$update_stmt) {
        die("Error preparing update query: " . $conn->error);
    }
    $update_stmt->bind_param("si", $new_status, $order_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Order updated successfully!'); window.location='view-orders.php';</script>";
    } else {
        echo "Error updating order: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Order</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; text-align: center; background: #f5f5f5; margin: 50px; }
        .container { padding: 20px; border-radius: 10px; background: white; display: inline-block; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        .btn { padding: 10px 20px; background: #388E3C; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Order Status</h2>
        <form method="POST">
            <label>Order ID:</label>
            <strong><?php echo $order_id; ?></strong><br><br>
            
            <label>Status:</label>
            <select name="status">
                <option value="pending" <?php if ($order['status'] == "pending") echo "selected"; ?>>Pending</option>
                <option value="shipped" <?php if ($order['status'] == "shipped") echo "selected"; ?>>Shipped</option>
                <option value="delivered" <?php if ($order['status'] == "delivered") echo "selected"; ?>>Delivered</option>
                <option value="cancelled" <?php if ($order['status'] == "cancelled") echo "selected"; ?>>Cancelled</option>
            </select>
            <br><br>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
</body>
</html>
