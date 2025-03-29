<?php
// Include the database connection
include("db.php");

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Fetch admin details
$admin_id = $_SESSION['user_id'];
$query = "SELECT * FROM admins WHERE id = '$admin_id'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Update admin details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Verify current password
    if (password_verify($current_password, $admin['password'])) {
        $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_BCRYPT) : $admin['password'];

        $update_query = "UPDATE admins SET username = '$username', email = '$email', password = '$hashed_password' WHERE id = '$admin_id'";
        if (mysqli_query($conn, $update_query)) {
            $success = "Settings updated successfully!";
            $_SESSION['username'] = $username;
        } else {
            $error = "Failed to update settings. Please try again.";
        }
    } else {
        $error = "Invalid current password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #8E24AA, #4A148C);
            color: #fff;
        }

        /* Header Styles */
        .header {
            background-color: #6A1B9A;
            padding: 20px;
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Form Section */
        .container {
            margin: 50px auto;
            max-width: 700px;
            padding: 30px;
            background: #fff;
            color: #333;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4A148C;
            font-size: 2rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input[type="text"], input[type="email"], input[type="password"], button {
            display: block;
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #6A1B9A;
            box-shadow: 0 0 5px rgba(106, 27, 154, 0.5);
        }

        button {
            background-color: #8E24AA;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #6A1B9A;
            transform: translateY(-3px);
        }

        .error, .success {
            text-align: center;
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            font-size: 1.2rem;
            color: #6A1B9A;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #8E24AA;
        }
    </style>
</head>
<body>
    <div class="header">
        Admin Settings
    </div>

    <div class="container">
        <h1>Update Your Settings</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" placeholder="Enter Username" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" placeholder="Enter Email" required>
            <input type="password" name="current_password" placeholder="Enter Current Password" required>
            <input type="password" name="new_password" placeholder="Enter New Password (Optional)">
            <button type="submit">Save Changes</button>
        </form>
        <div class="back-link">
            <a href="admin-dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>