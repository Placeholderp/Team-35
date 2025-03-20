const registerBtn = document.querySelector(".register-btn");
const toggleRegister = document.querySelector(".toggle-register");
const toggleLogin = document.querySelector(".toggle-login");
const formContainer = document.querySelector(".form-container");

function showRegisterForm() {
    formContainer.classList.add("active");
}


function showLoginForm() {
    formContainer.classList.remove("active");
}

if (registerBtn) {
    registerBtn.addEventListener("click", showRegisterForm);
}

if (toggleRegister) {
    toggleRegister.addEventListener("click", showRegisterForm);
}

if (toggleLogin) {
    toggleLogin.addEventListener("click", showLoginForm);
}


document.querySelectorAll(".toggle-password").forEach((toggle) => {
    toggle.addEventListener("click", function () {
        const passwordField = document.getElementById(this.dataset.target);
        const icon = this.querySelector("i");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.replace("bx-hide", "bx-show"); 
        } else {
            passwordField.type = "password";
            icon.classList.replace("bx-show", "bx-hide"); 
        }
    });
});
