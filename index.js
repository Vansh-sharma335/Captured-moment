const express = require('express');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const app = express();
const port = 3000;

// Middleware
app.use(express.json());
app.use(express.static('frontend')); // Serve static files from the frontend directory

// Temporary in-memory storage for admin users (Replace with database in production)
let adminUsers = [];

// JWT Secret (Should be stored in environment variables in production)
const JWT_SECRET = 'your_admin_jwt_secret';

// Admin login route
app.post('/admin/login', async (req, res) => {
    try {
        const { username, password } = req.body;

        // Validate input
        if (!username || !password) {
            return res.status(400).send('Username and password are required');
        }

        // Find admin user
        const admin = adminUsers.find(a => a.username === username);
        if (admin && await bcrypt.compare(password, admin.password)) {
            const token = jwt.sign({ username }, JWT_SECRET, { expiresIn: '1h' }); // Token expires in 1 hour
            res.json({ token });
        } else {
            res.status(401).send('Invalid credentials');
        }
    } catch (error) {
        console.error('Login error:', error);
        res.status(500).send('Internal server error');
    }
});

// Admin signup route (for adding admin users)
app.post('/admin/signup', async (req, res) => {
    try {
        const { username, email, password } = req.body;

        // Validate input
        if (!username || !email || !password) {
            return res.status(400).send('All fields are required');
        }

        // Check if user already exists
        const existingUser = adminUsers.find(a => a.username === username);
        if (existingUser) {
            return res.status(409).send('Username already exists');
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        adminUsers.push({ username, email, password: hashedPassword });
        res.status(201).send('Admin user created');
    } catch (error) {
        console.error('Signup error:', error);
        res.status(500).send('Internal server error');
    }
});

// Example route for file uploads (you'll need to add actual implementation)
app.post('/upload', (req, res) => {
    // Handle file upload here
    res.send('File uploaded');
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
// Function to handle form submission
function handleContact(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get form data
    const form = document.getElementById('contact-form');
    const formData = new FormData(form);

    // Send form data using fetch API
    fetch(form.action, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('contact-message').textContent = data;
        form.reset(); // Reset the form after submission
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('contact-message').textContent = 'An error occurred while submitting the form.';
    });
}
