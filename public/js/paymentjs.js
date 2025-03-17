/********************************
Developer: Hussain Alwazan
University ID: 230049123
Function: This page is to display items and purchase it.
********************************/

// cardnumber: remove any non-digit, maxium 16 digit and format numbers into group of 4 separated by space.
document.getElementById('cardNumber').addEventListener('input', function (e) {
    let cardNumber = e.target.value.replace(/\D/g, '');
    cardNumber = cardNumber.substring(0, 16);
    e.target.value = cardNumber.replace(/(.{4})/g, '$1 ').trim();
});

// expiry date: check if the date valid or not then alert the user.
function validateExpiryDate() {
    const expiryDate = document.getElementById("expiryDate").value;
    const today = new Date();
    const selectedDate = new Date(expiryDate + "-01");

    if (selectedDate < today) {
        alert("The expiry date cannot be in the past. Please enter a valid expiry date.");
        return false;
    }
    return true;
}

// cardnumbervalidate: remove all non-digit charactors and check if it 16 digit.
function validateCreditCardNumber() {
    const cardNumber = document.getElementById("cardNumber").value;
    const strippedCardNumber = cardNumber.replace(/\D/g, "");

    if (strippedCardNumber.length !== 16 || isNaN(strippedCardNumber)) {
        alert("The credit card number must be exactly 16 digits.");
        return false;
    }
    return true;
}

// ValidateCVV: remove all non-digit charachtors and check if it 3 digit.
function validateCVV() {
    const cvv = document.getElementById("CVV").value;
    const strippedCVV = cvv.replace(/\D/g, ""); 

    if (strippedCVV.length !== 3 || isNaN(strippedCVV)) {
        alert("The CVV must be exactly 3 digits.");
        return false;
    }
    return true;
}

// Submission: check if all the form is valid it will move to next page, else error alert will appear. 
function submitForm() {
    const form = document.getElementById("paymentForm");

    if (form.checkValidity()) {
        if (validateCreditCardNumber() && validateExpiryDate() && validateCVV()) {
            window.location.href = "success.html";
        }
    } else {
        form.reportValidity();
        document.getElementById("result");
    }
}