<?php
session_start();
include("db.php");

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f5f5f5; color: #333; }
        .header { background-color: #2E7D32; padding: 20px; text-align: center; font-size: 2rem; font-weight: bold; color: #fff; }
        .container { width: 80%; margin: 20px auto; display: flex; flex-wrap: wrap; justify-content: space-around; }
        .product-card { width: 300px; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin: 15px; padding: 15px; text-align: center; }
        .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; }
        .product-card h3 { color: #2E7D32; }
        .product-card p { font-size: 1rem; color: #555; }
        .button { display: block; width: 100%; padding: 10px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; margin-top: 10px; }
        .buy-now { background-color: #ff5722; color: white; }
        .buy-now:hover { background-color: #e64a19; }
        .add-to-cart { background-color: #007BFF; color: white; }
        .add-to-cart:hover { background-color: #0056b3; }
        .footer { background-color: #2E7D32; color: white; text-align: center; padding: 15px; margin-top: 20px; width: 100%; position: relative; }
    </style>
</head>
<body>
    <div class="header">View Products</div>
    <div class="container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
                <h3><?php echo $row['product_name']; ?></h3>
                <p>Price: ₹<?php echo $row['price']; ?></p>
                <p><?php echo substr($row['description'], 0, 50); ?>...</p>
                <button class="button buy-now" onclick="location.href='payments.php?product_id=<?php echo $row['id']; ?>'">Buy Now</button>
                <button class="button add-to-cart" onclick="location.href='add-to-cart.php?product_id=<?php echo $row['id']; ?>'">Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Mobile Trolley. All rights reserved.
    </div>
</body>
</html>
