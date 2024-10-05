document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const contactMessage = document.getElementById('contact-message');

    // Function to show messages
    function showMessage(msg) {
        const messageBox = document.createElement('div');
        messageBox.className = 'message-box';
        messageBox.textContent = msg;
        document.body.appendChild(messageBox);

        // Fade out effect
        setTimeout(() => {
            messageBox.classList.add('fade-out');
            setTimeout(() => {
                messageBox.remove(); // Remove from DOM after fade out
            }, 500); // Match this with CSS transition duration
        }, 3000); // Show for 3 seconds
    }

    form.addEventListener('submit', function(event) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();

        if (!name || !email || !subject || !message) {
            // Show error message using the showMessage function
            showMessage('Please fill in all fields.');
            event.preventDefault(); // Prevent form submission
        } else {
            // Optionally, show success message when the form is submitted correctly
            showMessage('Thank you for your message! We will get back to you soon.');
        }
    });
});
