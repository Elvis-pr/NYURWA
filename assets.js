const API_BASE_URL = 'http://localhost/nyurwashop/api';

// DOM Elements
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const logoutBtn = document.getElementById('logoutBtn');

// Login function
async function login(email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Login failed');
        }
        
        // Save token to localStorage
        localStorage.setItem('token', data.token);
        
        return data;
    } catch (err) {
        console.error('Login error:', err);
        throw err;
    }
}

// Register function
async function register(name, email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/auth/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email, password })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Registration failed');
        }
        
        // Save token to localStorage
        localStorage.setItem('token', data.token);
        
        return data;
    } catch (err) {
        console.error('Registration error:', err);
        throw err;
    }
}

// Logout function
function logout() {
    localStorage.removeItem('token');
    window.location.href = 'login.php';
}

// Check authentication status
function checkAuth() {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.php';
    }
}

// Event listeners
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        try {
            await login(email, password);
            window.location.href = 'index.php';
        } catch (err) {
            alert(err.message);
        }
    });
}

if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
        
        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }
        
        try {
            await register(name, email, password);
            window.location.href = 'index.php';
        } catch (err) {
            alert(err.message);
        }
    });
}

if (logoutBtn) {
    logoutBtn.addEventListener('click', logout);
}