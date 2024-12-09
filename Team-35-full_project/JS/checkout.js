/********************************
Developer: Hussain Alwazan
University ID: 230049123
Function: This page is to display items and purchase it.
********************************/

// Select elements for the cart functionality
let cartIcon = document.querySelector('#cart-button');
let cart = document.querySelector('.cart');
let closecart = document.querySelector('#close-cart');

// Show the cart when the cart icon is clicked
cartIcon.onclick = () => {
    cart.classList.add("active");
};

// Hide the cart when the close icon is clicked
closecart.onclick = () => {
    cart.classList.remove("active");
};

// Select elements for the navigation menu
const menuIcon = document.getElementById('menu-icon');
const navMenu = document.getElementById('nav-menu');

// Toggle menu visibility on hamburger icon click
menuIcon.addEventListener('click', () => {
    navMenu.classList.toggle('active');
});




if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", ready);
} else {
    ready();
}

// Function to set up event listeners and load cart items
function ready() {
    // Get all remove buttons and add click event listeners
    const removeCartButtons = document.getElementsByClassName('cart-remove');
    for (let button of removeCartButtons) {
        button.addEventListener('click', removeCartItem);
    }

    // Get all quantity inputs and add change event listeners
    const quantityInputs = document.getElementsByClassName('cart-quantity');
    for (let input of quantityInputs) {
        input.addEventListener('change', quantityChanged);
    }

    // Load saved cart items from local storage
    const addCart = document.getElementsByClassName("add-cart");
    for (let button of addCart) {
        button.addEventListener('click', addCartClicked);
    }
    document.querySelector(".search-form").addEventListener("submit", searchProducts);




    loadCartItems();
}

// Remove an item from the cart
function removeCartItem(event) {
    const buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updatetotal();
    saveCartItems();
    updateCartIcon();
}

// Update the quantity of an item
function quantityChanged(event) {
    const input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1;
    }
    updatetotal();
    saveCartItems();
    updateCartIcon();
}

// Handle adding a product to the cart
function addCartClicked(event) {
    const button = event.target;
    const shopProducts = button.closest(".product-info");

    const title = shopProducts.getElementsByClassName("product-title")[0].innerText;
    const price = shopProducts.getElementsByClassName("price")[0].innerText;
    const productImg = document.getElementById("product-image").src;
    const quantity = document.getElementById("quantity").value;

    addProductToCart(title, price, productImg, quantity);
    updatetotal();
    saveCartItems();
    updateCartIcon();
}

// Add a product to the cart
function addProductToCart(title, price, productImg, quantity) {
    const cartShopBox = document.createElement("div");
    cartShopBox.classList.add("cart-box");

    const cartItems = document.getElementsByClassName("cart-content")[0];
    const cartBoxContent = `
        <img src="${productImg}" alt="" class="cart-img" />
        <div class="detail-box">
            <div class="cart-product-title">${title}</div>
            <div class="cart-price">${price}</div>
            <input type="number" class="cart-quantity" value="${quantity}" min="1" />
        </div> 
        <i class="bx bx-trash cart-remove"></i>
    `;

    cartShopBox.innerHTML = cartBoxContent;
    cartItems.append(cartShopBox);

    // Add event listeners
    cartShopBox.querySelector(".cart-remove").addEventListener("click", removeCartItem);
    cartShopBox.querySelector(".cart-quantity").addEventListener("change", quantityChanged);

    saveCartItems();
    updateCartIcon();
}

// Update the total price of items in the cart
function updatetotal() {
    const cartContent = document.getElementsByClassName("cart-content")[0];
    const cartBoxes = cartContent.getElementsByClassName("cart-box");
    let total = 0;

    // Calculate total price
    for (let cartBox of cartBoxes) {
        const priceElement = cartBox.getElementsByClassName("cart-price")[0];
        const quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
        const price = parseFloat(priceElement.innerText.replace("£", ""));
        const quantity = quantityElement.value;
        total += price * quantity;
    }

    total = Math.round(total * 100) / 100;
    document.getElementsByClassName("total-price")[0].innerText = "£" + total;
    localStorage.setItem("cartTotal", total);
}

// Save cart items to local storage
function saveCartItems() {
    const cartContent = document.getElementsByClassName("cart-content")[0];
    const cartBoxes = cartContent.getElementsByClassName("cart-box");
    const cartItems = [];

    for (let cartBox of cartBoxes) {
        const titleElement = cartBox.getElementsByClassName("cart-product-title")[0];
        const priceElement = cartBox.getElementsByClassName("cart-price")[0];
        const quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
        const productImg = cartBox.getElementsByClassName("cart-img")[0].src;

        const item = {
            title: titleElement.innerText,
            price: priceElement.innerText,
            quantity: quantityElement.value,
            productImg: productImg,
        };

        cartItems.push(item);
    }

    localStorage.setItem("cartItems", JSON.stringify(cartItems));
}

// Load cart items from local storage
function loadCartItems() {
    const cartItems = localStorage.getItem("cartItems");
    if (cartItems) {
        const items = JSON.parse(cartItems);
        for (let item of items) {
            addProductToCart(item.title, item.price, item.productImg, item.quantity);
        }
    }
    const cartTotal = localStorage.getItem("cartTotal");
    if (cartTotal) {
        document.getElementsByClassName("total-price")[0].innerText = "£" + cartTotal;
    }
    updateCartIcon();
}

// Update the cart icon's quantity indicator
function updateCartIcon() {
    const cartBoxes = document.getElementsByClassName("cart-box");
    let quantity = 0;

    for (let cartBox of cartBoxes) {
        const quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
        quantity += parseInt(quantityElement.value);
    }

    const cartIcon = document.querySelector("#cart-icon");
    cartIcon.setAttribute("data-quantity", quantity);
}

function searchProducts(event) {
    const query = document.getElementById("search-query").value.toLowerCase();
    const products = document.querySelectorAll(".product-card");

    // Filter products dynamically
    products.forEach(product => {
        const productName = product.getAttribute("data-name").toLowerCase();
        if (productName.includes(query)) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    });
}



// Check if cart is empty before checkout
function checkCartBeforeCheckout() {
    const cartItems = document.getElementsByClassName("cart-box");
    if (cartItems.length === 0) {
        alert("Your cart is empty. Please add at least one product before proceeding to checkout.");
    } else {
        window.location.href = 'BasketPage.php'; 
    }
}

