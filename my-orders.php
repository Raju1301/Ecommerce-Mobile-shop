<?php
session_start();
include ("db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user orders
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0; 
            background-image: url('bg.jpg'); 
            color: #fff; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            justify-content: center; 
            gap: 20px; 
            padding: 15px; 
            background-color: #388E3C; 
        }
        .menu a { 
            color: #fff; 
            text-decoration: none; 
            font-size: 1.2rem; 
            font-weight: bold; 
            padding: 10px 20px; 
            border-radius: 5px; 
            transition: 0.3s; 
        }
        .menu a:hover { 
            background-color: #4CAF50; 
        }
        .container { 
            width: 80%; 
            margin: 50px auto; 
            padding: 30px; 
            background-color: #fff; 
            color: #333; 
            border-radius: 15px; 
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); 
            text-align: center; 
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #2E7D32;
            color: white;
        }
        .footer { 
            background-color: #2E7D32; 
            color: white; 
            text-align: center; 
            padding: 15px; 
            margin-top: auto; 
            width: 100%; 
        }
    </style>
</head>
<body>
    <div class="header">My Orders</div>
    <div class="menu">
        <a href="view-products.php">View Products</a>
        <a href="my-orders.php">My Orders</a>
        <a href="profile-settings.php">Profile Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>! Here are your orders:</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Delivery Status</th> <!-- New Column -->
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>&#8377;<?php echo $row['total_price']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td><?php echo ucfirst($row['delivery_status'] ?? 'Pending'); ?></td> <!-- Display Delivery Status -->
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="footer">&copy; <?php echo date('Y'); ?> Mobile Trolley. All rights reserved.</div>
</body>
</html>
