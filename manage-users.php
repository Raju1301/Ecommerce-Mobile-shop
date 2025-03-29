<?php
session_start();
include("db.php");

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Get admin details from the session
$username = $_SESSION['username'];

// Fetch all users
$user_query = "SELECT * FROM users";
$user_result = $conn->query($user_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-image: url('bg.jpg'); color: #fff; }
        .header { background-color: #2E7D32; padding: 20px; text-align: center; font-size: 2rem; font-weight: bold; color: #fff; }
        .menu { display: flex; justify-content: space-around; margin: 20px auto; padding: 10px; background-color: #388E3C; border-radius: 10px; }
        .menu a { color: #fff; text-decoration: none; padding: 15px 20px; font-size: 1.2rem; transition: 0.3s; }
        .menu a:hover { background-color: #4CAF50; transform: scale(1.1); }
        .content { margin: 20px; padding: 30px; background-color: #ffffff; color: #333; border-radius: 15px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #2E7D32; color: #fff; }
        .logout { text-align: center; margin-top: 20px; }
        .logout a { padding: 10px 25px; background-color: #D32F2F; color: #fff; text-decoration: none; border-radius: 8px; transition: 0.3s; }
        .logout a:hover { background-color: #F44336; transform: scale(1.1); }
    </style>
</head>
<body>
    <div class="header">Manage Users</div>

    <div class="menu">
        <a href="admin-dashboard.php">Home</a>
        <a href="upload-products.php">Upload Products</a>
        <a href="view-orders.php">View Orders</a>
        <a href="manage-users.php">Manage Users</a>
        <a href="verify-payments.php">Verify Payments</a>
        <a href="admin-settings.php">Settings</a>
    </div>

    <div class="content">
        <h2>All Registered Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $user_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="delete-user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="logout">
        <a href="admin-logout.php">Logout</a>
    </div>
</body>
</html>
