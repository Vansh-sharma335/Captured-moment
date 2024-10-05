<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captured Moments - Home</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Portfolio grid */
        .portfolio-grid {
            display: flex;
            flex-wrap: wrap; /* Allows items to wrap onto the next line */
            justify-content: center; /* Center items in the grid */
            margin: 20px 0;
        }

        .category-item {
            flex: 0 1 calc(25% - 2px); /* Adjusted width calculation */
            margin: 1px; /* Minimal margin to bring items closer */
            text-align: center;
        }

        .category-item a img {
            width: 100%; /* Ensure image fits within the category item */
            height: auto; /* Maintain aspect ratio */
            border-radius: 5px; /* Optional: Add rounded corners */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add transition for smooth effect */
        }

        .category-item a:hover img {
            transform: scale(1.1); /* Scale image on hover */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
        }

        /* Home content styles */
        .home-content-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center items in the grid */
            margin: 20px 0;
        }

        .home-content-item {
            flex: 0 1 calc(33.33% - 2px); /* Adjusted width calculation */
            margin: 1px; /* Minimal margin to bring items closer */
            text-align: center;
        }

        .home-content-item a img {
            width: 100%; /* Ensure image fits */
            height: auto; /* Maintain aspect ratio */
            border-radius: 5px; /* Optional: Add rounded corners */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add transition for smooth effect */
        }

        .home-content-item a:hover img {
            transform: scale(1.1); /* Scale image on hover */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
        }

        /* Social media icons */
        .social-media {
            margin: 20px 0;
            text-align: center;
        }

        .social-media a {
            margin: 0 10px; /* Space between icons */
            color: #333; /* Default icon color */
            font-size: 24px; /* Size of icons */
            transition: color 0.3s ease; /* Transition for color change */
        }

        .social-media a:hover {
            color: #007bff; /* Change color on hover */
        }

    </style>
    <script>
        function editPhoto(item) {
            const img = item.querySelector('img');
            const newSrc = prompt("Enter new image URL or upload an image:", img.src);

            if (newSrc) {
                // Update the image source
                img.src = newSrc;

                // Optionally, you can update the database via AJAX
                fetch('update_photo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'action': 'update_photo',
                        'old_src': img.src,
                        'new_src': newSrc,
                        'category': item.querySelector('h3').innerText // Pass category name if needed
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</head>
<body>

<header>
    <h1>Captured Moments</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact</a>
        <a href="portfolios.php">Portfolios</a>
        <a href="login.php">Login</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> 
        <?php endif; ?>
    </nav>
</header>


<section id="showcase" style="background-image: url('showcase-background.jpg'); background-size: cover;">
    <h2>Welcome to Captured Moments</h2>
    <h3>Latest Photos</h3>
    <div class="home-content-grid">
        <div class="home-content-item">
            <a href="sunset-over-the-mountains-landscape-in-taiwan-large.jpg" class="image-link" data-large="sunset-over-the-mountains-landscape-in-taiwan-large.jpg">
                <img src="sunset-over-the-mountains-landscape-in-taiwan.jpg" alt="Photo 1">
            </a>
            <p>A beautiful sunset over the mountains.</p>
        </div>
        <div class="home-content-item">
            <a href="./child.jpg" class="image-link" data-large="./child.jpg">
                <img src="./child.jpg" alt="Photo 2">
            </a>
            <p>A portrait of a smiling child.</p>
        </div>
        <div class="home-content-item">
            <a href="calm-lake-reflecting-the-clear-blue-sky-large.jpg" class="image-link" data-large="calm-lake-reflecting-the-clear-blue-sky-large.jpg">
                <img src="calm-lake-reflecting-the-clear-blue-sky.jpg" alt="Photo 3">
            </a>
            <p>Serene lake reflecting the sky.</p>
        </div>
        <div class="home-content-item">
            <a href="./nightlife.jpg" class="image-link" data-large="./nightlife.jpg">
                <img src="./nightlife.jpg" alt="Photo 4">
            </a>
            <p>A stunning cityscape at night.</p>
        </div>
        <div class="home-content-item">
            <a href="./tree.jpg" class="image-link" data-large="./tree.jpg">
                <img src="./tree.jpg" alt="Photo 5">
            </a>
            <p>Bare branches stretch skyward, revealing the tree's skeletal beauty in its leafless form.</p>
        </div>
    </div>
</section>

<section class="categories-section">
    <h2>Photography Categories</h2>
    <div class="portfolio-grid">
        <div class="category-item">
            <h3>Portrait Photography</h3>
            <p>Capturing the essence of individuals and families.</p>
            <a href="photo-1544124094-8aea0374da93.jpeg" class="image-link" data-large="photo-1544124094-8aea0374da93.jpeg">
                <img src="photo-1544124094-8aea0374da93.jpeg" alt="Portrait" ondblclick="editPhoto(this.parentElement.parentElement)">
            </a>
        </div>
        <div class="category-item">
            <h3>Landscape Photography</h3>
            <p>Showcasing the beauty of nature and urban environments.</p>
            <a href="land.jpg" class="image-link" data-large="land.jpg">
                <img src="land.jpg" alt="Landscape" ondblclick="editPhoto(this.parentElement.parentElement)">
            </a>
        </div>
        <div class="category-item">
            <h3>Event Photography</h3>
            <p>Documenting your special moments, from weddings to corporate events.</p>
            <a href="./even.jpg" class="image-link" data-large="./even.jpg">
                <img src="./even.jpg" alt="Event" ondblclick="editPhoto(this.parentElement.parentElement)">
            </a>
        </div>
        <div class="category-item">
            <h3>Wildlife Photography</h3>
            <p>Capturing the beauty and majesty of animals in their natural habitats.</p>
            <a href="wild.jpg" class="image-link" data-large="wild.jpg">
                <img src="wild.jpg" alt="Wildlife" ondblclick="editPhoto(this.parentElement.parentElement)">
            </a>
        </div>
        <div class="category-item">
            <h3>Street Photography</h3>
            <p>Capturing everyday life in urban environments, showcasing real moments.</p>
            <a href="street.jpg" class="image-link" data-large="street.jpg">
                <img src="street.jpg" alt="Street" ondblclick="editPhoto(this.parentElement.parentElement)">
            </a>
        </div>
        <div class="category-item">
            <h3>Under Water</h3>
            <p>Exploring the magical underwater world with stunning underwater photography.</p>
            <a href="./under.jpg" class="image-link" data-large="./under.jpg">
                <img src="./under.jpg" alt="Under Water" ondblclick="editPhoto(this.parentElement.parentElement)">
            </a>
        </div>
    </div>
</section>

<!-- Social Media Icons -->
<div class="social-media">
    <h2>Follow Us</h2>
    <a href="https://www.facebook.com" target="_blank" class="fab fa-facebook-f"></a>
    <a href="https://www.instagram.com" target="_blank" class="fab fa-instagram"></a>
    <a href="https://www.twitter.com" target="_blank" class="fab fa-twitter"></a>
    <a href="https://www.linkedin.com" target="_blank" class="fab fa-linkedin-in"></a>
</div>

<footer>
    <p>&copy; 2024 Captured Moments</p>
</footer>
</body>
</html>
<script>
    // Add event listeners to all draggable items
    const draggables = document.querySelectorAll('.category-item');
    const container = document.querySelector('.portfolio-grid');

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging');
        });

        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
        });
    });

    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        const dragging = document.querySelector('.dragging');
        const afterElement = getDragAfterElement(container, e.clientY);
        if (afterElement == null) {
            container.appendChild(dragging);
        } else {
            container.insertBefore(dragging, afterElement);
        }
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.category-item:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
</script>
