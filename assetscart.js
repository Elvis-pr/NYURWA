const API_BASE_URL = 'http://localhost/nyurwashop/api';

// Add to cart
async function addToCart(productId, quantity = 1) {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.php';
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/cart/add_to_cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ product_id: productId, quantity })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Failed to add to cart');
        }
        
        return data;
    } catch (err) {
        console.error('Add to cart error:', err);
        throw err;
    }
}

// Get cart
async function getCart() {
    const token = localStorage.getItem('token');
    if (!token) {
        return { items: [], total: 0 };
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/cart/get_cart.php`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Failed to get cart');
        }
        
        return data.data;
    } catch (err) {
        console.error('Get cart error:', err);
        throw err;
    }
}

// Update cart display
async function updateCartDisplay() {
    try {
        const cart = await getCart();
        const cartCount = document.getElementById('cartCount');
        const cartItemsContainer = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        
        // Update cart count
        if (cartCount) {
            const totalItems = cart.items.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
        }
        
        // Update cart items in sidebar
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = '';
            
            if (cart.items.length === 0) {
                cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
                if (cartTotal) cartTotal.textContent = '0 RWF';
                return;
            }
            
            cart.items.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <img src="${item.image || 'assets/images/placeholder.jpg'}" alt="${item.name}" class="cart-item-image">
                    <div class="cart-item-details">
                        <h4 class="cart-item-title">${item.name}</h4>
                        <div class="cart-item-price">${formatPrice(item.price)}</div>
                        <div class="cart-item-quantity">
                            <button class="quantity-btn decrease" data-id="${item.product_id}">-</button>
                            <span>${item.quantity}</span>
                            <button class="quantity-btn increase" data-id="${item.product_id}">+</button>
                        </div>
                    </div>
                    <button class="remove-item" data-id="${item.product_id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                cartItemsContainer.appendChild(cartItem);
            });
            
            if (cartTotal) cartTotal.textContent = formatPrice(cart.total);
        }
    } catch (err) {
        console.error('Update cart display error:', err);
    }
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('en-RW', {
        style: 'currency',
        currency: 'RWF'
    }).format(price);
}

// Initialize cart display
document.addEventListener('DOMContentLoaded', updateCartDisplay);