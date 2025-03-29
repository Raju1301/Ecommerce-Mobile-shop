<?php
include("db.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO admins (username, email, PASSWORD) VALUES ('$username', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration successful!'); window.location='admin-login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    
<body>
    <div class="form-container">
        <h1>Admin Registration</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form><br>
        <a href="admin-login.php">Already Have an account?<br>Login</a>
    </div>
</body>
</html>