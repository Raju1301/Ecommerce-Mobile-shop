<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to admin-login.php if not logged in
    header("Location: admin-login.php");
    exit;
}

// Get admin details from the session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-image: url('bg.jpg');
            color: #fff;
        }

        /* Header Styles */
        .header {
            background-color: #2E7D32;
            padding: 20px;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Welcome Section */
        .welcome {
            text-align: center;
            margin: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: capitalize;
        }

        /* Navigation Menu */
        .menu {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 20px auto;
            padding: 10px;
            background-color: #388E3C;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }

        .menu a {
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.3s ease;
            border-radius: 8px;
            text-align: center;
        }

        .menu a:hover {
            background-color: #4CAF50;
            transform: scale(1.1);
        }

        /* Content Section */
        .content {
            margin: 20px;
            padding: 30px;
            background-color: #ffffff;
            color: #333;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .content h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #2E7D32;
        }

        .content p {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Logout Button */
        .logout {
            margin-top: 20px;
            text-align: center;
        }

        .logout a {
            text-decoration: none;
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 10px 25px;
            background-color: #D32F2F;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .logout a:hover {
            background-color: #F44336;
            transform: scale(1.1);
        }

        /* Footer Styles */
        .footer {
            background-color: #2E7D32;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        Admin Dashboard
    </div>

    <div class="welcome">
        Welcome, <?php echo htmlspecialchars($username); ?>!
    </div>

    <div class="menu">
        <a href="upload-products.php">Upload Products</a>
        <a href="view-orders.php">View Orders</a>
        <a href="manage-users.php">Manage Users</a>
        <a href="verify-payments.php">Verify Payments</a>
        <a href="admin-settings.php">Settings</a>
    </div>

    <div class="content">
        <h2>Dashboard Overview</h2>
        <p>Welcome to your admin dashboard! Here, you can manage products, view customer orders, verify payments, manage users, and configure your website settings efficiently.</p>
        <p>Use the navigation menu above to access different sections of the dashboard.</p>
    </div>

    <div class="logout">
        <a href="admin-logout.php">Logout</a>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Mobile Trolley. All rights reserved.
    </div>
</body>
</html>
