<?php
session_start();
include("db.php");

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Initialize error and success messages
$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);

    // Validate inputs
    if (empty($product_name) || empty($price) || empty($description)) {
        $error = "All fields are required!";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        $error = "Please upload a valid image!";
    } else {
        $upload_dir = "uploads/";

        // Create the directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($_FILES['image']['name']);
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_exts = array("jpg", "jpeg", "png", "gif");

        // Validate file type
        if (!in_array($image_ext, $allowed_exts)) {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed!";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) { // Limit file size to 2MB
            $error = "Image size should not exceed 2MB!";
        } else {
            $new_file_name = time() . "_" . uniqid() . "." . $image_ext; // Rename file
            $target_file = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert product details into the database
                $stmt = $conn->prepare("INSERT INTO products (product_name, price, description, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sdss", $product_name, $price, $description, $target_file);

                if ($stmt->execute()) {
                    $success = "Product uploaded successfully!";
                } else {
                    $error = "Error inserting data: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Error moving uploaded file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-image: url('bg.jpg'); color: #fff; }
        .header { background-color: #2E7D32; padding: 20px; text-align: center; font-size: 2rem; font-weight: bold; color: #fff; }
        .menu { display: flex; justify-content: space-around; margin: 20px auto; padding: 10px; background-color: #388E3C; border-radius: 10px; }
        .menu a { color: #fff; text-decoration: none; padding: 15px 20px; font-size: 1.2rem; transition: 0.3s; }
        .menu a:hover { background-color: #4CAF50; transform: scale(1.1); }
        .content { margin: 20px; padding: 30px; background-color: #ffffff; color: #333; border-radius: 15px; text-align: center; }
        .form-container { width: 50%; margin: auto; padding: 20px; background: white; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        input, textarea { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px 20px; background: #388E3C; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #4CAF50; }
        .error { color: red; }
        .success { color: green; }
        .footer { text-align: center; padding: 20px; background-color: #2E7D32; margin-top: 20px; color: #fff; font-size: 1.1rem; border-radius: 0 0 10px 10px; }
    </style>
</head>
<body>
    <div class="header">Upload Product</div>

    <div class="menu">
        <a href="admin-dashboard.php">Home</a>
        <a href="upload-products.php">Upload Products</a>
        <a href="view-orders.php">View Orders</a>
        <a href="manage-users.php">Manage Users</a>
        <a href="verify-payments.php">Verify Payments</a>
        <a href="admin-settings.php">Settings</a>
    </div>

    <div class="content">
        <div class="form-container">
            <h2>Upload New Product</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="product_name" placeholder="Product Name" required><br>
                <input type="number" name="price" placeholder="Price" step="0.01" required><br>
                <textarea name="description" placeholder="Product Description" required></textarea><br>
                <input type="file" name="image" accept="image/*" required><br>
                <button type="submit">Upload Product</button>
            </form>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Admin Panel - All Rights Reserved
    </div>
</body>
</html>

<?php $conn->close(); ?>
