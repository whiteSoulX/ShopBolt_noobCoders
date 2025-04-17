<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href=assets/css/easy.css>
</head>
<body>
    <!-- Header -->
    <header>
    <a href="index.php" class="nav_logo">
        <img src="assets/images/logo.png" alt="Shop Bolt Logo">
    </a>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="about.html">About</a>
        <a href="shop.html">Shop</a>
        <a href="contact.html">Contact</a>
        <a href="account.php">Account</a>
        <a href="cart.php">Cart</a>
    </nav>
</header>

    <!-- Cart Section -->
    <section class="cart">
        <h2 class="font-weight-bold mb-4">Your Cart</h2>
        <hr>

        <table class="cart-table table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <!-- JS Injected Cart Items -->
            </tbody>
        </table>

        <!-- Totals -->
        <div class="cart-total">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td><span id="subtotal">$0.00</span></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><span id="total">$0.00</span></td>
                </tr>
            </table>
        </div>

        <!-- Checkout Button -->
        <div class="checkout-container">
            <a href="checkout.html"><button class="btn checkout-btn">CHECKOUT</button></a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-hr flex flex-col">
                <div class="flex gap-1">
                    <hr>
                    <h6>Newsletter</h6>
                </div>
                <h3>Join Our Mailing List</h3>
                <p class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum nam iure quis ea iste suscipit.</p>
            </div>
            <form action="#" id="footer-form" class="flex flex-sb gap-2">
                <div id="footer-message"></div>
                <input type="email" required placeholder="Enter your email">
                <button type="submit" class="btn_hover1">Get Started</button>
            </form>
        </div>
        <div class="footer-menu">
            <div class="container">
                <div class="flex flex-start footer-center">
                    <div class="w-33 mt-2 flex-col gap-2 flex-start">
                        <a href="index.php"><img src="assets/images/logo.png" alt="footer-logo"></a>
                        <p class="mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, adipisci?</p>
                        <span class="flex gap-20 mt-15">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </span>
                    </div>
                    <div class="w-16 mt-1">
                        <h4>Quick Links</h4>
                        <ul class="flex flex-col gap-20 flex-start">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="#">Portfolio</a></li>
                            <li><a href="#">Blogs</a></li>
                        </ul>
                    </div>
                    <div class="w-16 mt-45 flex-end">
                        <ul class="flex flex-col gap-20 flex-start">
                            <li><a href="#">FAQ</a></li>
                            <li><a href="contact.html">Contact</a></li>
                            <li><a href="#">Team</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="w-33 mt-1 flex flex-col flex-start">
                        <h4>Get Connected</h4>
                        <ul class="flex flex-col gap-2 flex-start">
                            <li>
                                <a href="#"><i class="bi bi-envelope"></i></a>
                                <a href="#" class="text-lowercase">youname@gmail.com</a>
                            </li>
                            <li>
                                <a href="#"><i class="bi bi-telephone"></i></a>
                                <a href="#">+123-456-7890</a>
                            </li>
                            <li>
                                <a href="#"><i class="bi bi-clock"></i></a>
                                <a href="#">Office-Hours: 8AM - 11PM Sunday - Weekend Day</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container flex flex-sb gap-20 flex-warp">
                <h6>Copyright © 2024 Coded by <a href="index.php" class="p-0">NoobCoders</a></h6>
                <h6>Powered By <b>Prisom</b></h6>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-whGJZ8Iq3uPB4VyCMFSTOKR3pJ5ZFL9XKFlHyLtvEYpD6c3dM6spJot49LMB0WTR" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        function renderCart() {
            const cartBody = document.getElementById("cart-body");
            cartBody.innerHTML = "";

            if (cart.length === 0) {
                cartBody.innerHTML = '<tr><td colspan="4">Your cart is empty.</td></tr>';
                document.getElementById("subtotal").textContent = "$0.00";
                document.getElementById("total").textContent = "$0.00";
                return;
            }

            cart.forEach((item, index) => {
                const numericPrice = parseFloat(item.price.replace('$', '')) || 0;
                const subtotal = numericPrice * item.quantity;

                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>
                        <div class="product-info">
                            <img src="${item.img}" alt="${item.title}">
                            <div>
                                <p>${item.title}</p>
                                <small>${item.price}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="number" min="1" value="${item.quantity}" data-index="${index}" class="product-quantity">
                    </td>
                    <td>
                        <span class="product-subtotal">$${subtotal.toFixed(2)}</span>
                    </td>
                    <td>
                        <a href="#" class="remove-btn" data-index="${index}">Remove</a>
                    </td>
                `;
                cartBody.appendChild(row);
            });

            attachEvents();
            updateTotals();
        }

        function attachEvents() {
            document.querySelectorAll('.product-quantity').forEach(input => {
                input.addEventListener('input', (e) => {
                    const index = e.target.dataset.index;
                    const newQty = parseInt(e.target.value);
                    cart[index].quantity = newQty > 0 ? newQty : 1;
                    saveAndRender();
                });
            });

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const index = btn.dataset.index;
                    cart.splice(index, 1);
                    saveAndRender();
                });
            });
        }

        function updateTotals() {
            let subtotal = 0;
            cart.forEach(item => {
                const numericPrice = parseFloat(item.price.replace('$', '')) || 0;
                subtotal += numericPrice * item.quantity;
            });

            document.getElementById("subtotal").textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById("total").textContent = `$${subtotal.toFixed(2)}`;
        }

        function saveAndRender() {
            localStorage.setItem("cart", JSON.stringify(cart));
            renderCart();
        }

        renderCart();
    </script>
</body>
</html>