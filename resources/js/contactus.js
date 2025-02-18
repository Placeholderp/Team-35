function submitForm() {
   

    // Gather form data
    const name = document.getElementById('name').value; 
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    if (!name || !email || !subject || !message) {
        alert('Please fill in all required fields.');
        return;
    }


    // Log form data (for testing purposes)
    console.log('Form Submitted:', { name, email, phone, subject, message });

    // Redirect to contact page after submission
    window.location.href = 'ContactUsConfirm.html'; // Change to your actual contact page URL
    }
// Dropdown Toggle Functionality
document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".dropdown");
    const dropbtn = dropdown.querySelector(".dropbtn");

    dropbtn.addEventListener("click", function () {
        // Toggle the "open" class on the dropdown
        dropdown.classList.toggle("open");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove("open");
        }
    });
});
