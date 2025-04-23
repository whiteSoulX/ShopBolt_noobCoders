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

// Create a temporary user if not logged in
if (!isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, city, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(["Guest", "guest@example.com", "1234567890", "Guest City", "Guest Address"]);
    $_SESSION['user_id'] = $conn->lastInsertId();
}

$user_id = $_SESSION['user_id'];

// Handle form submission to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = $_POST['full-name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cart_data = json_decode($_POST['cart-data'], true);

    // Update user information
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?");
    $stmt->execute([$name, $email, $phone, $address, $user_id]);

    // Add cart items to database
    foreach ($cart_data as $item) {
        $product_name = $item['product'];
        $start_date = $item['startDate'];
        $start_time = $item['startTime'];
        $end_date = $item['endDate'];
        $end_time = $item['endTime'];
        $price = $item['price'];
        $quantity = 1;
        $subtotal = $price * $quantity;

        // Find or insert product into products table
        $stmt = $conn->prepare("SELECT product_id FROM products WHERE name = ?");
        $stmt->execute([$product_name]);
        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, rental_price, image_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_name, "Rental Product", $price, $item['image']]);
            $product_id = $conn->lastInsertId();
        } else {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_id = $product['product_id'];
        }

        // Insert into cart_items
        $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, start_date, start_time, end_date, end_time, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $start_date, $start_time, $end_date, $end_time, $quantity, $subtotal]);
    }

    // Redirect to cart.php
    header("Location: cart.php");
    exit;
}

// Define products
$products = [
    [
        'name' => 'Camera Full Set',
        'description' => 'Camera + Headphone FOR 5 People',
        'price' => 300,
        'image' => 'https://images.unsplash.com/photo-1673196649519-aa73d9079e5b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        'offer' => 'Free for First 5 People'
    ],
    [
        'name' => 'Drone AX RoX Series 1',
        'description' => 'Full Set with Camera',
        'price' => 990,
        'image' => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e',
        'offer' => ''
    ],
    [
        'name' => 'Home Theater 2:1',
        'description' => 'JBL 500 dB Home Setup',
        'price' => 490,
        'image' => 'https://images.unsplash.com/photo-1633182780109-d2f8ff5dc997?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        'offer' => ''
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Digital Products</title>
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
        h1 {
            text-align: center;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 10px;
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .product-card p {
            font-size: 14px;
            color: #666;
        }
        .price {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        .add-to-cart button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            text-align: center;
        }
        .form-group {
            margin: 10px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .modal-content button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .cart, .user-info {
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
        .user-info form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 500px;
            margin: 0 auto;
        }
        .user-info input, .user-info button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .user-info button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
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
        <h1>Rent Digital Products</h1>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    <div class="add-to-cart">
                        <button onclick="openModal('<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo htmlspecialchars($product['image']); ?>')">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal for Date/Time Selection -->
        <div class="modal" id="rental-modal">
            <div class="modal-content">
                <h3>Rent <span id="modal-product-name"></span></h3>
                <input type="hidden" id="modal-product-price">
                <input type="hidden" id="modal-product-image">
                <div class="form-group">
                    <label for="start-date">Start Date</label>
                    <input type="date" id="start-date" required>
                </div>
                <div class="form-group">
                    <label for="start-time">Start Time</label>
                    <input type="time" id="start-time" required>
                </div>
                <div class="form-group">
                    <label for="end-date">End Date</label>
                    <input type="date" id="end-date" required>
                </div>
                <div class="form-group">
                    <label for="end-time">End Time</label>
                    <input type="time" id="end-time" required>
                </div>
                <button onclick="addToCartFromModal()">Add to Cart</button>
            </div>
        </div>

        <!-- Cart Display -->
        <div class="cart">
            <h2>Your Cart</h2>
            <table id="cart-table">
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
                <tbody id="cart-body"></tbody>
            </table>
        </div>

        <!-- User Information -->
        <div class="user-info">
            <h2>Your Information</h2>
            <form id="order-form" method="POST" action="">
                <div class="form-group">
                    <label for="full-name">Full Name</label>
                    <input type="text" id="full-name" name="full-name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Enter your address" required>
                </div>
                <input type="hidden" id="cart-data" name="cart-data">
                <input type="hidden" name="place_order" value="1">
                <button type="submit">Place Order</button>
            </form>
        </div>
    </div>

    <script>
        let cart = [];

        function openModal(productName, price, image) {
            document.getElementById('modal-product-name').textContent = productName;
            document.getElementById('modal-product-price').value = price;
            document.getElementById('modal-product-image').value = image;
            document.getElementById('rental-modal').style.display = 'flex';
        }

        function addToCartFromModal() {
            const productName = document.getElementById('modal-product-name').textContent;
            const price = document.getElementById('modal-product-price').value;
            const image = document.getElementById('modal-product-image').value;
            const startDate = document.getElementById('start-date').value;
            const startTime = document.getElementById('start-time').value;
            const endDate = document.getElementById('end-date').value;
            const endTime = document.getElementById('end-time').value;

            if (!startDate || !startTime || !endDate || !endTime) {
                alert('Please fill in all fields.');
                return;
            }

            cart.push({ product: productName, startDate, startTime, endDate, endTime, price, image });
            updateCart();
            document.getElementById('rental-modal').style.display = 'none';
            document.getElementById('start-date').value = '';
            document.getElementById('start-time').value = '';
            document.getElementById('end-date').value = '';
            document.getElementById('end-time').value = '';
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function updateCart() {
            const cartBody = document.getElementById('cart-body');
            cartBody.innerHTML = '';

            cart.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><img src="${item.image}" class="product-image" alt="${item.product}"></td>
                    <td>${item.product}</td>
                    <td>${item.startDate} ${item.startTime}</td>
                    <td>${item.endDate} ${item.endTime}</td>
                    <td>$${item.price}</td>
                    <td><button class="remove-btn" onclick="removeFromCart(${index})">Remove</button></td>
                `;
                cartBody.appendChild(row);
            });

            document.getElementById('cart-data').value = JSON.stringify(cart);
        }

        document.getElementById('order-form').addEventListener('submit', (e) => {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Your cart is empty. Please add at least one product.');
            }
        });
    </script>
</body>
</html>