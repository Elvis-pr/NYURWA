<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <header>
            <nav class="navbar">
                <a href="<?php echo APP_URL; ?>" class="logo">
                    <i class="fas fa-bolt logo-icon"></i>
                    <?php echo APP_NAME; ?>
                </a>
                
                <div class="nav-links">
                    <a href="<?php echo APP_URL; ?>" class="nav-link">Home</a>
                    <a href="<?php echo APP_URL; ?>/pages/products.php" class="nav-link">Shop Now</a>
                    <a href="<?php echo APP_URL; ?>/pages/products.php" class="nav-link">Categories</a>
                    <a href="<?php echo APP_URL; ?>/pages/about.php" class="nav-link">About</a>
                    <a href="<?php echo APP_URL; ?>/pages/contact.php" class="nav-link">Contact</a>
                </div>
                
                <div class="nav-actions">
                    <?php if (isset($_SESSION['user'])): ?>
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                        <button id="logoutBtn" class="logout-btn">Logout</button>
                    <?php else: ?>
                        <a href="<?php echo APP_URL; ?>/pages/login.php" class="login-btn">Login</a>
                        <a href="<?php echo APP_URL; ?>/pages/register.php" class="register-btn">Register</a>
                    <?php endif; ?>
                    
                    <button class="cart-btn" id="cartBtn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </button>
                </div>
            </nav>
        </header>