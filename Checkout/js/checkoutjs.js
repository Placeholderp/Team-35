/********************************
Developer: Hussain Alwazan
University ID: 230049123
Function: This page is to display items and purchase it.
********************************/

// Select elements for the cart functionality
let cartIcon = document.querySelector('#cart-icon')
let cart = document.querySelector('.cart')
let closecart= document.querySelector('#close-cart')


// Show the cart when the cart icon is clicked
cartIcon.onclick = () => {
    cart.classList.add("active");
};

// Hide the cart when the close icon is clicked
closecart.onclick = () => {
    cart.classList.remove("active");
};

if(document.readyState == "loading"){
    document.addEventListener("DOMContentLoaded", ready);
}else{
    ready();
}
// Function to set up event listeners and load cart items
function ready(){
    // Get all remove buttons and add click event listeners
    var removeCartButtons = document.getElementsByClassName('cart-remove');
    for (var i = 0; i< removeCartButtons.length; i++){
        var button = removeCartButtons[i];
        button.addEventListener('click', removeCartItem);
    }
// Get all quantity and add change event listeners
    var quantityInputs = document.getElementsByClassName('cart-quantity');
    for (var i = 0; i< quantityInputs.length; i++){
        var input = quantityInputs[i];
        input.addEventListener('change', quantityChanged);
    }
// Load saved cart items from local storage
    var addCart = document.getElementsByClassName("add-cart");
    for (var i = 0; i< addCart.length; i++){
        var button = addCart[i];
        button.addEventListener('click', addCartClicked);
    }
    loadCartItems();
}
// Remove an item from the cart
function removeCartItem(event){
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updatetotal(); // Update the total price
    saveCartItems(); // Save updated cart items to local storage
    updateCartIcon(); // Update the cart icon's quantity indicator
}
// Update the quantity of an item
function quantityChanged(event){
    var input = event.target;
    if (isNaN(input.value) || input.value <= 0){
        input.value = 1;
    }
    updatetotal();
    saveCartItems();
    updateCartIcon();
}
// Handle adding a product to the cart
function addCartClicked(event){
    var button = event.target;
    var shopProducts = button.parentElement;
    var title = shopProducts.getElementsByClassName("product-title")[0].innerText;
    var price = shopProducts.getElementsByClassName("price")[0].innerText;
    var productImg = shopProducts.getElementsByClassName("product img")[0].src;
    addProductToCart(title,price,productImg);
    updatetotal();
    saveCartItems();
    updateCartIcon();
}
// Add a product to the cart
function addProductToCart(title, price, productImg){
    var cartShopBox = document.createElement("div");
    cartShopBox.classList.add("cart-box");
    var cartItems = document.getElementsByClassName("cart-content")[0];
    var cartItemsNames = cartItems.getElementsByClassName("cart-product-title");
    for (var i=0; i< cartItemsNames.length; i++){
        if(cartItemsNames[i].innerText == title){
            alert("You have already added this item to cart");
            return;
        }
    }
     // Define the content for the cart item
    var cartBoxContent = `
    <img src="${productImg}" alt="" class="cart-img"/>
            <div class="detail-box">
               <div class="cart-product-title">${title}</div>
               <div class="cart-price">${price}</div>
               <input
               type="number"
               name=""
               id=""
               value="1"
               class="cart-quantity"
               />
            </div> 
            <i class="bx bx-trash cart-remove"></i> `;
            cartShopBox.innerHTML = cartBoxContent;
            cartItems.append(cartShopBox);
            cartShopBox
            .getElementsByClassName("cart-remove")[0]
            .addEventListener('click', removeCartItem);
            cartShopBox
            .getElementsByClassName("cart-quantity")[0]
            .addEventListener("change", quantityChanged);
            saveCartItems();
            updateCartIcon();

}
// Update the total price of items in the cart
function updatetotal(){
    var cartContent = document.getElementsByClassName("cart-content")[0];
    var cartBoxes = cartContent.getElementsByClassName("cart-box");
    var total = 0;
    
    // Calculate total price
    for(var i=0; i<cartBoxes.length; i++){
        var cartBox = cartBoxes[i];
        var priceElement = cartBox.getElementsByClassName("cart-price")[0];
        var quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
        var price = parseFloat(priceElement.innerText.replace("£", ""));
        var quantity = quantityElement.value;
        total += price * quantity;
    }
    total = Math.round(total * 100) / 100;
    document.getElementsByClassName("total-price")[0].innerText = "£" + total;
    localStorage.setItem("cartTotal", total);
}
// Save cart items to local storage
function saveCartItems(){
    var cartContent = document.getElementsByClassName("cart-content")[0];
    var cartBoxes = cartContent.getElementsByClassName("cart-box");
    var cartItems = [];

    for (var i=0; i< cartBoxes.length; i++){
       var cartBox = cartBoxes[i];
        var titleElement = cartBox.getElementsByClassName("cart-product-title")[0];
        var priceElement = cartBox.getElementsByClassName("cart-price")[0];
        var quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
        var productImg = cartBox.getElementsByClassName("cart-img")[0].src;

        var item = {
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
function loadCartItems(){
    var cartItems = localStorage.getItem("cartItems");
    if (cartItems){
        cartItems = JSON.parse(cartItems);

        for (var i=0; i< cartItems.length; i++){
            var item = cartItems[i];
            addProductToCart(item.title, item.price, item.productImg);

            var cartBoxes = document.getElementsByClassName("cart-box");
            var cartBox = cartBoxes[cartBoxes.length - 1];
            var quantityElement = cartBox.getElementsByClassName("cart-quantity")[0];
            quantityElement.value = item.quantity;
        }
    }
    var cartTotal = localStorage.getItem("cartTotal");
    if(cartTotal){
        document.getElementsByClassName("total-price")[0].innerText = 
            "£" + cartTotal;

    }
    updateCartIcon();
}
// Update the cart icon's quantity indicator
function updateCartIcon(){
    var cartBoxes = document.getElementsByClassName("cart-box");
    var quantity = 0;

    for(var i=0; i< cartBoxes.length; i++){
        var cartBox = cartBoxes[i];
        var quantityElemnet = cartBox.getElementsByClassName("cart-quantity")[0];
        quantity += parseInt(quantityElemnet.value);

    }
    var cartIcon = document.querySelector("#cart-icon");
    cartIcon.setAttribute("data-quantity", quantity);
}
function checkCartBeforeCheckout() {
    var cartItems = document.getElementsByClassName("cart-box");
    if (cartItems.length === 0) {
        alert("Your cart is empty. Please add at least one product before proceeding to checkout.");
    } else {
        window.location.href = 'Checkout.html';
    }
}
