<?php
session_start();
include("db.php");

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Fetch all orders
$stmt = $conn->prepare("SELECT order_id, user_name, product_name, quantity, total_price, status, delivery_status, order_date FROM orders ORDER BY order_date DESC");
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

// Handle delivery_status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['new_delivery_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_delivery_status = $_POST['new_delivery_status'];

    // Update only 'delivery_status' in the database
    $update_stmt = $conn->prepare("UPDATE orders SET delivery_status = ? WHERE order_id = ?");
    if (!$update_stmt) {
        die("Error preparing update query: " . $conn->error);
    }
    $update_stmt->bind_param("si", $new_delivery_status, $order_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Delivery status updated successfully!'); window.location='view-orders.php';</script>";
    } else {
        echo "Error updating delivery status: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            text-align: center; 
            margin: 0; 
            background: url('bg.jpg') no-repeat center center fixed; 
            background-size: cover; 
            color: #fff; 
        }
        .header { 
            background-color: #2E7D32; 
            padding: 20px; 
            text-align: center; 
            font-size: 2rem; 
            font-weight: bold; 
            color: #fff; 
        }
        .menu { 
            display: flex; 
            justify-content: space-around; 
            padding: 10px; 
            background-color: #388E3C; 
            border-radius: 10px; 
        }
        .menu a { 
            color: #fff; 
            text-decoration: none; 
            padding: 15px 20px; 
            font-size: 1.2rem; 
            transition: 0.3s; 
        }
        .menu a:hover { 
            background-color: #4CAF50; 
            transform: scale(1.1); 
        }
        .container { 
            padding: 20px; 
            border-radius: 10px; 
            background: white; 
            display: inline-block; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            margin-top: 20px; 
            width: 90%; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: white; 
            color: #333; 
        }
        th, td { 
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center; 
        }
        th { 
            background: #388E3C; 
            color: white; 
        }
        .btn { 
            padding: 8px 12px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            margin: 2px; 
            color: white; 
            font-size: 0.9rem; 
        }
        .pending { background: orange; }
        .shipped { background: blue; }
        .delivered { background: green; }
        .cancelled { background: red; }
        .footer { 
            text-align: center; 
            padding: 20px; 
            background-color: #2E7D32; 
            color: #fff; 
            font-size: 1.1rem; 
            border-radius: 0 0 10px 10px; 
        }
    </style>
</head>
<body>
    <div class="header">Manage Orders</div>

    <div class="menu">
        <a href="admin-dashboard.php">Home</a>
        <a href="upload-products.php">Upload Products</a>
        <a href="view-orders.php">View Orders</a>
        <a href="manage-users.php">Manage Users</a>
        <a href="verify-payments.php">Verify Payments</a>
        <a href="admin-settings.php">Settings</a>
    </div>

    <div class="container">
        <h2>Manage Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Tracking Update</th>
                <th>Actions</th>
            </tr>
            <?php while ($order = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                    <td class="<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </td>
                    <td><?php echo !empty($order['delivery_status']) ? ucfirst($order['delivery_status']) : "No updates"; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit" name="new_delivery_status" value="pending" class="btn pending">Set Pending</button>
                            <button type="submit" name="new_delivery_status" value="shipped" class="btn shipped">Set Shipped</button>
                            <button type="submit" name="new_delivery_status" value="delivered" class="btn delivered">Set Delivered</button>
                            <button type="submit" name="new_delivery_status" value="cancelled" class="btn cancelled">Set Cancelled</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Admin Panel - All Rights Reserved
    </div>
</body>
</html>

<?php $conn->close(); ?>
