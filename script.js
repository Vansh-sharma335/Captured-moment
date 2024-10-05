// Utility function to handle form submission
function handleFormSubmission(event, formId, url, successMessage, errorMessage, redirectUrl = null) {
    event.preventDefault(); // Prevent default form submission
    const form = document.getElementById(formId);
    const message = document.getElementById(`${formId}-message`);
    const formData = new FormData(form);

    // Display loading state
    message.textContent = 'Processing...';
    message.classList.remove('error', 'success');
    message.classList.add('loading');

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        message.classList.remove('loading');

        if (data.success) {
            message.textContent = successMessage;
            message.classList.add('success');
            form.reset(); // Clear the form

            if (redirectUrl) {
                // Redirect after a brief delay
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 1500);
            }
        } else {
            message.textContent = errorMessage;
            message.classList.add('error');
        }
    })
    .catch(() => {
        message.textContent = 'An error occurred! Please try again.';
        message.classList.remove('loading');
        message.classList.add('error');
    });
}

// Function to handle login form submission
function handleLogin(event) {
    handleFormSubmission(event, 'login-form', 'login.php', 'Login successful!', 'Invalid credentials!', 'dashboard.php');
}

// Function to handle signup form submission
function handleSignup(event) {
    handleFormSubmission(event, 'signup-form', 'register.php', 'Signup successful!', 'Signup failed!');
}

// Function to handle contact form submission
function handleContact(event) {
    handleFormSubmission(event, 'contact-form', 'contact_process.php', 'Message sent successfully!', 'Message failed to send!');
}

// Function to toggle between login and signup forms
function toggleForms() {
    const wrapper = document.querySelector('.wrapper');
    const registerLink = document.querySelector('.register-link');
    const loginLink = document.querySelector('.login-link');

    registerLink.onclick = () => {
        wrapper.classList.add('active');
    };

    loginLink.onclick = () => {
        wrapper.classList.remove('active');
    };
}

// Client-side validation (optional, basic)
function validateForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input');
    for (let input of inputs) {
        if (!input.value) {
            return false; // Incomplete form
        }
    }
    return true;
}

// Attach event listeners when the DOM content is loaded
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const contactForm = document.getElementById('contact-form');

    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            if (validateForm('login-form')) {
                handleLogin(event);
            } else {
                document.getElementById('login-message').textContent = 'Please fill in all fields.';
            }
        });
    }

    if (signupForm) {
        signupForm.addEventListener('submit', (event) => {
            if (validateForm('signup-form')) {
                handleSignup(event);
            } else {
                document.getElementById('signup-message').textContent = 'Please fill in all fields.';
            }
        });
    }

    if (contactForm) {
        contactForm.addEventListener('submit', (event) => {
            if (validateForm('contact-form')) {
                handleContact(event);
            } else {
                document.getElementById('contact-message').textContent = 'Please fill in all fields.';
            }
        });
    }

    toggleForms(); // Initialize form toggling
});
