document.addEventListener("DOMContentLoaded", function() {
    // Toggle between login and register forms
    const registerBtn = document.querySelector('.register-btn');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');
    const toggleRegister = document.querySelector('.toggle-register');
    const toggleLogin = document.querySelector('.toggle-login');
    const container = document.querySelector('.container');
    
    if (registerBtn) {
        registerBtn.addEventListener('click', function() {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            container.classList.add('active');
        });
    }
    
    if (toggleRegister) {
        toggleRegister.addEventListener('click', function(e) {
            e.preventDefault();
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            container.classList.add('active');
        });
    }
    
    if (toggleLogin) {
        toggleLogin.addEventListener('click', function(e) {
            e.preventDefault();
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
            container.classList.remove('active');
        });
    }
    
    // Toggle password visibility
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        });
    });
    
    // Add form validation
    const loginFormElement = document.querySelector('.login-form form');
    const registerFormElement = document.querySelector('.register-form form');
    
    if (loginFormElement) {
        loginFormElement.addEventListener('submit', function(e) {
            const emailInput = this.querySelector('input[type="email"]');
            const passwordInput = this.querySelector('input[type="password"]');
            let isValid = true;
            
            // Simple validation
            if (!emailInput.value.trim() || !isValidEmail(emailInput.value)) {
                isValid = false;
                highlightInvalidField(emailInput);
            } else {
                removeInvalidHighlight(emailInput);
            }
            
            if (!passwordInput.value.trim()) {
                isValid = false;
                highlightInvalidField(passwordInput);
            } else {
                removeInvalidHighlight(passwordInput);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    if (registerFormElement) {
        registerFormElement.addEventListener('submit', function(e) {
            const nameInput = this.querySelector('input[name="name"]');
            const emailInput = this.querySelector('input[name="email"]');
            const passwordInput = this.querySelector('input[name="password"]');
            const confirmPasswordInput = this.querySelector('input[name="password_confirmation"]');
            let isValid = true;
            
            // Name validation
            if (!nameInput.value.trim()) {
                isValid = false;
                highlightInvalidField(nameInput);
            } else {
                removeInvalidHighlight(nameInput);
            }
            
            // Email validation
            if (!emailInput.value.trim() || !isValidEmail(emailInput.value)) {
                isValid = false;
                highlightInvalidField(emailInput);
            } else {
                removeInvalidHighlight(emailInput);
            }
            
            // Password validation
            if (!passwordInput.value.trim() || passwordInput.value.length < 8) {
                isValid = false;
                highlightInvalidField(passwordInput);
            } else {
                removeInvalidHighlight(passwordInput);
            }
            
            // Confirm password validation
            if (passwordInput.value !== confirmPasswordInput.value) {
                isValid = false;
                highlightInvalidField(confirmPasswordInput);
            } else {
                removeInvalidHighlight(confirmPasswordInput);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Helper functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function highlightInvalidField(field) {
        field.style.borderColor = '#e74c3c';
        field.style.backgroundColor = 'rgba(231, 76, 60, 0.05)';
    }
    
    function removeInvalidHighlight(field) {
        field.style.borderColor = '';
        field.style.backgroundColor = '';
    }
    
    // Profile dropdown toggle function
    function toggleProfileMenu() {
        const menu = document.getElementById('profile-menu');
        if (menu) {
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('profile-menu');
        const icon = document.getElementById('profile-icon');
        
        if (menu && icon && event.target !== icon && !menu.contains(event.target)) {
            menu.style.display = 'none';
        }
    });
    
    // Auto redirect to homepage after successful login/registration
    // This will be handled by Laravel's authentication system redirects
});