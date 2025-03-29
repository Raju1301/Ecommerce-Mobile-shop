<?php
session_start();
include("db.php");

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit;
}

// Check if user_id is passed via GET
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']); // Ensure it's an integer for security

    // Delete query
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // Redirect to manage-users.php with success message
            header("Location: manage-users.php?msg=User deleted successfully");
        } else {
            // Redirect with an error message
            header("Location: manage-users.php?error=Failed to delete user");
        }
        $stmt->close();
    } else {
        header("Location: manage-users.php?error=Query preparation failed");
    }
} else {
    header("Location: manage-users.php?error=Invalid user ID");
}
exit;
?>
