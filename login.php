<?php
session_start();
include("db_connect/db_connect.php");

// Redirect logged-in users to account.php
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: account.php');
    exit();
}

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'All fields are required!'
                });
              </script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'SQL Error: " . addslashes($conn->error) . "'
                });
              </script>";
        exit();
    }

    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Registration Successful! Please log in.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'login.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: " . addslashes($stmt->error) . "'
                });
              </script>";
    }

    $stmt->close();
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'All fields are required!'
                });
              </script>";
        exit();
    }

    // Fetch username along with id and password
    $sql = "SELECT id, username, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'SQL Error: " . addslashes($conn->error) . "'
                });
              </script>";
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set all necessary session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Redirect to account.php
            header("Location: account.php");
            exit();
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Invalid Password!'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No user found with this email!'
                });
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>

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
    <link rel="stylesheet" href="assets/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- New Nav-->
    <header>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.html">About</a>
            <a href="shop.html">Shop</a>
            <a href="contact.html">Contact</a>
            <a href="account.php">Account</a>
        </nav>
        <a href="index.php" class="nav_logo">
          <img src="assets/images/logo.png" alt="Logo">
        </a>
    </header>

    <div class="loginframe">
        <!-- Login Form -->
        <div class="form login" id="login-section">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <div class="logininput">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" placeholder=" " required>
                    <label>Email</label>
                </div>
                <div class="logininput">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" placeholder=" " required>
                    <label>Password</label>
                </div>
                <div class="rememberme-forgot">
                    <label><input type="checkbox">Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" name="login" class="btnsubmit">Login</button>
                <div class="registernewid">
                    <p>Don't have an account? <a href="#register-section" class="registerlink">Register Now</a></p>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="form register" id="register-section">
            <h2>Registration</h2>
            <form action="login.php" method="post">
                <div class="logininput">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="username" placeholder=" " required>
                    <label>Username</label>
                </div>
                <div class="logininput">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" placeholder=" " required>
                    <label>Email</label>
                </div>
                <div class="logininput">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" placeholder=" " required>
                    <label>Password</label>
                </div>
                <div class="rememberme-forgot">
                    <label>
                        <input type="checkbox"> Agree to the terms and conditions
                    </label>
                </div>
                <button type="submit" name="register" class="btnsubmit">Register</button>
                <div class="registernewid">
                    <p>Already have an account? <a href="#login-section" class="loginlink">Login Now</a></p>
                </div>
            </form>
        </div>
    </div>

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
                <h6>Copyright &copy; 2024 Coded by <a href="/" class="p-0">NoobCoders</a></h6>
                <h6>Powered By <b>Prisom</b></h6>
            </div>
        </div>
    </footer>

    <!-- Ensure Bootstrap Icons and Font Awesome are included -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-whGJZ8Iq3uPB4VyCMFSTOKR3pJ5ZFL9XKFlHyLtvEYpD6c3dM6spJot49LMB0WTR" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>