<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['email']);
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        header('Location: login.php');
        exit;
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    include("db_connect/db_connect.php");

    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'All fields are required!'
                });
              </script>";
    } elseif ($newPassword !== $confirmPassword) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'New passwords do not match!'
                });
              </script>";
    } elseif (strlen($newPassword) < 6) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'New password must be at least 6 characters long!'
                });
              </script>";
    } else {
        // Verify current password
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Database error: " . addslashes($conn->error) . "'
                    });
                  </script>";
            exit;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($currentPassword, $row['password'])) {
                // Current password is correct, proceed to update new password
                $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);

                if (!$update_stmt) {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Database error: " . addslashes($conn->error) . "'
                            });
                          </script>";
                    exit;
                }

                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Password Change Successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                          </script>";
                } else {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to change password: " . addslashes($update_stmt->error) . "'
                            });
                          </script>";
                }

                $update_stmt->close();
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Current password is incorrect!'
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'User not found!'
                    });
                  </script>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Account</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/easy.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- New Nav -->
    <header>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.html">About</a>
            <a href="shop.html">Shop</a>
            <a href="contact.html">Contact</a>
            <a href="account.php">Account</a>
            <a href="cart.php">Cart</a>
        </nav>
        <a href="index.php" class="nav_logo">
            <img src="assets/images/logo.png" alt="Logo">
        </a>
    </header>

    <!-- Account Info -->
    <section class="my-5 py-5">
        <div class="row container mx-auto">
            <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                <h3 class="font-weight-bold">Account Info</h3>
                <hr class="mx-auto">
                <div class="account-info">
                    <p><span><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
                    <p><span><?php echo htmlspecialchars($_SESSION['email']); ?></span></p>
                    <p><a href="#orders" id="order-btn">Your Orders</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12">
                <form action="account.php" method="post" id="account-form">
                    <h3>Change Password</h3>
                    <hr class="mx-auto">
                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <input type="password" class="form-control" id="current-password" name="current_password" placeholder="Current Password" required>
                    </div>
                    <div class="form-group">
                        <label for="account-password">New Password</label>
                        <input type="password" class="form-control" id="account-password" name="password" placeholder="New Password" required>
                    </div>
                    <div class="form-group">
                        <label for="account-password-confirm">Confirm New Password</label>
                        <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Confirm New Password" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="change_password" value="1">
                        <input type="submit" value="Change Password" class="btn" id="change-pass-btn">
                    </div>
                </form>
            </div>
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

    <!-- Ensure Bootstrap Icons and Font Awesome are included -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-whGJZ8Iq3uPB4VyCMFSTOKR3pJ5ZFL9XKFlHyLtvEYpD6c3dM6spJot49LMB0WTR" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Client-side validation for password match and length
        document.getElementById('account-form').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('account-password').value;
            const confirmPassword = document.getElementById('account-password-confirm').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'All fields are required!'
                });
            } else if (newPassword !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'New passwords do not match!'
                });
            } else if (newPassword.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'New password must be at least 6 characters long!'
                });
            }
        });
    </script>
</body>
</html>