function submitForm() {
    // Simulate form submission for demonstration purposes.
    // Normally, you would send the data to a server here.

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
    window.location.href= 'SendMessage.html';
}
// JavaScript for Responsive Menu
const menuIcon = document.getElementById("menu-icon");
const navMenu = document.getElementById("nav-menu");

menuIcon.addEventListener("click", () => {
    navMenu.classList.toggle("active");
});




   