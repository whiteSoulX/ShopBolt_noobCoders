<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/easy.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
    <!-- <link rel="stylesheet" href="/Projrct1/assets/css/login.css"> -->
</head>
<body>

    <!-- New Nav-->
    <header>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.html">About</a>
            <a href="shop.html">Shop</a>
            <a href="contact.html">Contact</a>
            <a href="login.php">Sign In</a>
            <a href="account.php">Account</a>
            <a href="cart.php">Cart</a>
        </nav>
        <a href="index.html" class="nav_logo">
          <img src="assets/images/logo.png" alt="Logo">
        </a>
    </header>



    <!--Checkout Page-->
    <section class="my-5 py-5">
        <div class="mx-auto container mt-5">
            <h4>Order Summary</h4>
            <div class="border p-3 rounded">
              <table class="table">
                <tr>
                  <td>Subtotal:</td>
                  <td id="cart-subtotal">$0.00</td>
                </tr>
                <tr>
                  <td>Shipping:</td>
                  <td id="shipping-fee">$10.00</td>
                </tr>
                <tr>
                  <td>
                    Coupon Code:
                    <input type="text" id="coupon-code" class="form-control mt-2" placeholder="Enter coupon (e.g. Shopbolt)">
                  </td>
                  <td>
                    <button type="button" id="apply-coupon" class="btn btn-sm btn-primary mt-4">Apply</button>
                    <div id="coupon-message" class="mt-2"></div>
                  </td>
                </tr>
                <tr class="fw-bold">
                  <td>Total:</td>
                  <td id="cart-total">$0.00</td>
                </tr>
              </table>
            </div>
          </div>
          
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weigth-bold">Check out</h2>
            <hr class="mx-auto">
        </div>

        <div class="mx-auto container">
            <form id="checkout-form">
                <div class="form-group checkout-small-element">
                    <label>Name</label>
                    <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Email</label>
                    <input type="text" class="form-control" id="checkout-email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Phone</label>
                    <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Phone" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>City</label>
                    <input type="text" class="form-control" id="checkout-city" name="city" placeholder="City" required>
                </div>
                <div class="form-group checkout-large-element">
                    <label>Address</label>
                    <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required>
                </div>
                <div class="form-group checkout-btn-container">
                    <input type="submit" class="btn" id="checkout-btn" value="Checkout">
                </div>
            </form>
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
                <input type="email" required placeholder="Enter  your email">
                <button type="submit" class="btn_hover1 ">Get Started</button>

            </form>
        </div>
        <div class="footer-menu">
            <div class="container">
                <div class="flex flex-start footer-center">
                    <div class="w-33 mt-2 flex-col gap-2 flex-start">
                        <a href="/"><img src="assets/images/logo.png" alt="footer-logo"></a>
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
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Portfolio</a></li>
                        <li><a href="#">Blogs</a></li>
                    </ul>
                </div>    
                <div class="w-16 mt-45 flex-end">
                    
                    <ul class="flex flex-col gap-20 flex-start">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
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
                            <a href="#" >+123-456-7890</a>
                        </li>
                        <li>
                            <a href="#"><i class="bi bi-clock"></i></a>
                            <a href="#">Office-Hours : 8AM - 11PM
                                        Sunday - Weekend Day
                            </a>
                        </li>
                    </ul>
                </div>    
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container flex flex-sb gap-20 flex-warp">
                <h6>Copyright &copy; 2024 Coded by <a href="/" class="p-0">NoobCoders</a></h6>
                <h6>Powerd By <b>Prisom</b></h6>
            </div>
        </div>
    </footer>


      <!-- Ensure Bootstrap Icons and Font Awesome are included -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-whGJZ8Iq3uPB4VyCMFSTOKR3pJ5ZFL9XKFlHyLtvEYpD6c3dM6spJot49LMB0WTR" crossorigin="anonymous"></script>
      <script src="/assets/js/script.js"></script> 
      <script>
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        const shippingFee = 10.00;
        const validCouponCode = "SHOPBOLT";
        const discountRate = 0.10;
        let isCouponApplied = false;
      
        function calculateTotals(applyDiscount = false) {
          let subtotal = 0;
          cart.forEach(item => {
            const price = parseFloat(item.price.replace('$', '')) || 0;
            subtotal += price * item.quantity;
          });
      
          const discount = applyDiscount ? subtotal * discountRate : 0;
          const total = subtotal - discount + shippingFee;
      
          document.getElementById("cart-subtotal").textContent = $${subtotal.toFixed(2)};
          document.getElementById("shipping-fee").textContent = $${shippingFee.toFixed(2)};
          document.getElementById("cart-total").textContent = $${total.toFixed(2)};
        }
      
        document.getElementById("apply-coupon").addEventListener("click", () => {
          const code = document.getElementById("coupon-code").value.trim().toUpperCase();
          const messageEl = document.getElementById("coupon-message");
      
          if (code === validCouponCode) {
            isCouponApplied = true;
            calculateTotals(true);
            messageEl.textContent = "Coupon applied! You saved 10%.";
            messageEl.className = "text-success";
          } else {
            isCouponApplied = false;
            calculateTotals(false);
            messageEl.textContent = "Invalid coupon code.";
            messageEl.className = "text-danger";
          }
        });
      
        // Initial calculation on page load
        calculateTotals(false);
      
        // Handle Checkout Form Submission
        document.getElementById("checkout-form").addEventListener("submit", function(e) {
          e.preventDefault(); // Prevent default form submission
      
          // Show thank you popup
          const popup = document.createElement("div");
          popup.id = "thank-you-popup";
          popup.innerHTML = `
            <div class="popup-content text-center">
              <h3>🎉 Thank you for your order!</h3>
              <p>Redirecting to Home page...</p>
            </div>
          `;
          document.body.appendChild(popup);
      
          // Add some style
          document.head.insertAdjacentHTML("beforeend", `
            <style>
              #thank-you-popup {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
              }
              .popup-content {
                background: white;
                padding: 40px 30px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
              }
            </style>
          `);
      
          // Clear cart
          localStorage.removeItem("cart");
    
          setTimeout(() => {
            window.location.href = "index.html";
          }, 1000);
        });
      </script>
           
    
</body>
</html>