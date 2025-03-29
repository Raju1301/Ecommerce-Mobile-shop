<?php
session_start();
include("db.php");

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Handle payment verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];

    // Ensure status updates correctly
    $updated_status = ($new_status === "Verified") ? "Paid" : (($new_status === "Failed") ? "Failed" : $new_status);

    // Update only the status column
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    if (!$update_stmt) {
        die("Error preparing update query: " . $conn->error);
    }
    $update_stmt->bind_param("si", $updated_status, $order_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Payment status updated successfully!'); window.location='verify-payments.php';</script>";
    } else {
        echo "Error updating payment status: " . $conn->error;
    }
}

// Fetch all orders with payment details
$stmt = $conn->prepare("SELECT order_id, user_name, product_name, total_price, payment_method, transaction_id, status FROM orders ORDER BY order_date DESC");
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Payments</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; text-align: center; background: #f5f5f5; margin: 50px; }
        .container { padding: 20px; border-radius: 10px; background: white; display: inline-block; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 80%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: center; }
        th { background: #2E7D32; color: white; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; margin: 2px; color: white; font-size: 0.9rem; }
        .paid { background: green; }
        .failed { background: red; }
        .pending { background: orange; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify Payments</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Transaction ID</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($order = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                    <td><?php echo ucfirst($order['payment_method']); ?></td>
                    <td><?php echo !empty($order['transaction_id']) ? $order['transaction_id'] : 'N/A'; ?></td>
                    <td class="<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit" name="new_status" value="Verified" class="btn paid">Mark as Paid</button>
                            <button type="submit" name="new_status" value="Failed" class="btn failed">Mark as Failed</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
