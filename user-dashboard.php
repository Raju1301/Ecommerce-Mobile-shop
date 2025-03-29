<?php
session_start();
include ("db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit;
}

// Get user details
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            width: 60%; 
            margin: 50px auto; 
            padding: 30px; 
            background-color: #fff; 
            color: #333; 
            border-radius: 15px; 
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); 
            text-align: center; 
            flex: 1; /* Pushes the footer down */
        }
        .container h2 { 
            color: #2E7D32; 
        }
        .footer { 
            background-color: #2E7D32; 
            color: white; 
            text-align: center; 
            padding: 15px; 
            margin-top: auto; /* Ensures footer stays at bottom */
            width: 100%; 
        }
    </style>
</head>
<body>
    <div class="header">User Dashboard</div>
    <div class="menu">
        <a href="view-products.php">View Products</a>
        <a href="my-orders.php">My Orders</a>
        <a href="profile-settings.php">Profile Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Explore our products, track your orders, and manage your account settings.</p>
    </div>
    <div class="footer">&copy; <?php echo date('Y'); ?> Mobile Trolley. All rights reserved.</div>
</body>
</html>