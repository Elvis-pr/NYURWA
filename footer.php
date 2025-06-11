        <footer>
            <div class="footer-content">
                <div class="footer-column">
                    <h3><?php echo APP_NAME; ?></h3>
                    <p>Your one-stop shop for all the latest products and trends.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Shop</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo APP_URL; ?>/pages/products.php">All Products</a></li>
                        <li class="footer-link"><a href="<?php echo APP_URL; ?>/pages/products.php">Featured</a></li>
                        <li class="footer-link"><a href="<?php echo APP_URL; ?>/pages/products.php">New Arrivals</a></li>
                        <li class="footer-link"><a href="<?php echo APP_URL; ?>/pages/products.php">Sale Items</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo APP_URL; ?>/pages/contact.php">Contact Us</a></li>
                        <li class="footer-link"><a href="#">FAQs</a></li>
                        <li class="footer-link"><a href="#">Shipping</a></li>
                        <li class="footer-link"><a href="#">Returns</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo APP_URL; ?>/pages/about.php">About Us</a></li>
                        <li class="footer-link"><a href="#">Blog</a></li>
                        <li class="footer-link"><a href="#">Careers</a></li>
                        <li class="footer-link"><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-overlay" id="cartOverlay"></div>
    
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3 class="cart-title">Your Cart</h3>
            <button class="close-cart" id="closeCart">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="cart-items" id="cartItems">
            <!-- Cart items will be loaded here -->
        </div>
        
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotal">0 RWF</span>
            </div>
            <a href="<?php echo APP_URL; ?>/pages/checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
    </div>

    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/auth.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/cart.js"></script>
</body>
</html>