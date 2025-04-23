<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users_shopbolt";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT ci.*, p.name, p.image_url FROM cart_items ci JOIN products p ON ci.product_id = p.product_id WHERE ci.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['subtotal'];
}
$shipping_fee = 10.00;
$total = $subtotal + $shipping_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h2, h4 {
            text-align: center;
        }
        .cart {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .cart table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart th, .cart td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .cart th {
            background-color: #f8f9fa;
        }
        .cart .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .cart .remove-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .order-summary {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-summary table {
            width: 100%;
        }
        .order-summary td {
            padding: 5px;
        }
        .checkout-btn {
            display: block;
            width: 200px;
            margin: 0 auto;
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }
        .navbar {
            text-align: center;
            margin-bottom: 20px;
        }
        .navbar a {
            margin: 0 10px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="about.html">About</a>
        <a href="shop.php">Shop</a>
        <a href="contact.html">Contact</a>
        <a href="login.php">Sign In</a>
        <a href="account.php">Account</a>
        <a href="cart.php">Cart</a>
    </div>

    <div class="container">
        <h2>Your Cart</h2>
        <div class="cart">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Start Date/Time</th>
                        <th>End Date/Time</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cart_items)): ?>
                        <tr>
                            <td colspan="6">Your cart is empty.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="product-image" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['start_date'] . ' ' . $item['start_time']); ?></td>
                                <td><?php echo htmlspecialchars($item['end_date'] . ' ' . $item['end_time']); ?></td>
                                <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                <td>
                                    <form method="POST" action="remove_from_cart.php">
                                        <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                        <button type="submit" class="remove-btn">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h4>Order Summary</h4>
        <div class="order-summary">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td>Shipping:</td>
                    <td>$<?php echo number_format($shipping_fee, 2); ?></td>
                </tr>
                <tr>
                    <td><b>Total:</b></td>
                    <td><b>$<?php echo number_format($total, 2); ?></b></td>
                </tr>
            </table>
        </div>

        <?php if (!empty($cart_items)): ?>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php endif; ?>
    </div>
</body>
</html>