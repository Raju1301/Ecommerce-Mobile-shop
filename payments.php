<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Fetch user address
$address_query = "SELECT address FROM users WHERE id = '$user_id'";
$address_result = mysqli_query($conn, $address_query);
$user_address = mysqli_fetch_assoc($address_result)['address'] ?? 'No address found';

$phone_query = "SELECT phone FROM users WHERE id = '$user_id'";
$phone_result = mysqli_query($conn, $phone_query);
$user_phone = mysqli_fetch_assoc($phone_result)['phone'] ?? 'No phone number found';

$email_query = "SELECT email FROM users WHERE id = '$user_id'";
$email_result = mysqli_query($conn, $email_query);
$user_email = mysqli_fetch_assoc($email_result)['email'] ?? 'No email found';

// Fetch product details
$product_query = "SELECT * FROM products WHERE id = '$product_id'";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Razorpay API keys
$razorpay_key = "rzp_test_HWuGA01SX54mFz";
$razorpay_secret = "bFYX0Y4Rpos6US4hFPx93EyJ";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f5f5f5; color: #333; text-align: center; }
        .container { width: 50%; margin: 50px auto; padding: 30px; background-color: #fff; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); }
        .btn-pay { padding: 10px 20px; background-color: #388E3C; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirm Your Order</h2>
        <p><strong>Product:</strong> <?php echo $product['product_name']; ?></p>
        <p><strong>Price:</strong> ₹<?php echo $product['price']; ?></p>
        <p><strong>Shipping Address:</strong> <?php echo $user_address; ?></p>
        <button class="btn-pay" id="pay-btn">Proceed to Pay</button>
    </div>

    <script>
        document.getElementById('pay-btn').onclick = function () {
            var options = {
                "key": "<?php echo $razorpay_key; ?>",
                "amount": "<?php echo $product['price'] * 100; ?>",
                "currency": "INR",
                "name": "Mobile Trolley",
                "description": "Payment for <?php echo $product['product_name']; ?>",
                "handler": function (response) {
                    alert('Payment Successful! Payment ID: ' + response.razorpay_payment_id);
                    window.location.href = "order-success.php?payment_id=" + response.razorpay_payment_id;
                },
                "prefill": {
                    "name": "<?php echo $_SESSION['username']; ?>",
                    "email": "<?php echo $user_email; ?>",
                    "contact": "<?php echo $user_phone; ?>"
                },
                "theme": { "color": "#388E3C" }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        };
    </script>
</body>
</html>
