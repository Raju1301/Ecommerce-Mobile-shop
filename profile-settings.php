<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($new_username && $new_email && $new_phone) {
        $update_query = "UPDATE users SET username = ?, email = ?, phone = ?";
        if ($new_password) {
            $update_query .= ", password = ?";
        }
        $update_query .= " WHERE id = ?";

        $stmt = $conn->prepare($update_query);
        if ($new_password) {
            $stmt->bind_param("ssssi", $new_username, $new_email, $new_phone, $new_password, $user_id);
        } else {
            $stmt->bind_param("sssi", $new_username, $new_email, $new_phone, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; // Update session data
            echo "<script>alert('Profile updated successfully!'); window.location.href='profile-settings.php';</script>";
        } else {
            echo "<script>alert('Error updating profile. Try again.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
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
            width: 50%; 
            margin: 50px auto; 
            padding: 30px; 
            background-color: #fff; 
            color: #333; 
            border-radius: 15px; 
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); 
            text-align: center; 
            flex: 1; 
        }
        .container h2 { 
            color: #2E7D32; 
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #2E7D32;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #388E3C;
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
    <div class="header">Profile Settings</div>
    <div class="menu">
        <a href="view-products.php">View Products</a>
        <a href="my-orders.php">My Orders</a>
        <a href="profile-settings.php">Profile Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>New Password (leave blank to keep current password):</label>
                <input type="password" name="password">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
    <div class="footer">&copy; <?php echo date('Y'); ?> Mobile Trolley. All rights reserved.</div>
</body>
</html>
