<?php
require_once '../../config.php';


$conn = getDBConnection();

$heroStmt = $conn->prepare("SELECT title, body FROM site_content WHERE slug = 'home_hero' LIMIT 1");
$heroStmt->execute();
$heroContent = $heroStmt->get_result()->fetch_assoc();
$heroStmt->close();

$aboutStmt = $conn->prepare("SELECT title, body FROM site_content WHERE slug = 'about_text' LIMIT 1");
$aboutStmt->execute();
$aboutContent = $aboutStmt->get_result()->fetch_assoc();
$aboutStmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outdoor Planner</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
</head>
<body>

<nav>
    <div class="nav-links">
        <a href="#home">Home</a>
        <a href="#map">Map</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </div>

    <div class="auth">
        <?php if (isLoggedIn()): ?>
            <button class="auth-btn"><?php echo htmlspecialchars(getCurrentUsername()); ?> ▾</button>
            <div class="auth-dropdown">
                <a href="../../api/logout.php">Logout</a>
            </div>
        <?php else: ?>
            <button class="auth-btn">Account ▾</button>
            <div class="auth-dropdown">
                <a href="../../Login/login.php">Login</a>
                <a href="../../Login/register.php">Register</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<section class="hero">
    <div class="hero-txt">
        <?php echo htmlspecialchars($heroContent['title'] ?? 'WELCOME TO OUR WORLD'); ?>
    </div>
</section>

<section class="hero-slider">
    <div class="slider-window">
        <div class="slider-track" id="heroSliderTrack">
            <div class="slide">
                <h3>Explore Interactive Map</h3>
                <p>Drag, zoom, and discover unique outdoor locations.</p>
            </div>
            <div class="slide">
                <h3>Book Tickets Instantly</h3>
                <p>Choose a place, pick a date, and download your ticket as PDF.</p>
            </div>
            <div class="slide">
                <h3>Plan With Friends</h3>
                <p>Share locations and plan unforgettable outdoor adventures.</p>
            </div>
        </div>
    </div>
</section>

<div class="section" id="map">
   <div class="map-container">
    <div class="section" id="map">
        <h2>MAP</h2>
        <div class="map-hint">Drag the map to pan and use the maximize button to view the full landscape.</div>
        
        <div class="map-box" id="interactiveMap">
            
            <button class="maximize-btn" id="maximizeButton">
                Maximize
            </button>
            
            <div class="map-box-content" id="mapContent">
                <div class="map-pointers-container" id="mapPointersContainer">
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>

<script>
    
    async function loadPlaces() {
        try {
            const response = await fetch('../../api/places.php');
            const places = await response.json();
            
            const container = document.getElementById('mapPointersContainer');
            container.innerHTML = '';
            
           
            places.forEach((place, index) => {
                const pointer = document.createElement('a');
                pointer.className = 'map-pointer';
                pointer.href = `Locations/place.php?place=${encodeURIComponent(place.name)}`;
                pointer.setAttribute('data-place', place.name);
                pointer.textContent = place.name;
                pointer.title = place.description;
               
                const cols = 5;
                const row = Math.floor(index / cols);
                const col = index % cols;
                pointer.style.left = (col * 200 + 100) + 'px';
                pointer.style.top = (row * 80 + 100) + 'px';
                container.appendChild(pointer);
            });
        } catch (error) {
            console.error('Error loading places:', error);
        }
    }
    
   
    loadPlaces();
    
    const mapBox = document.getElementById('interactiveMap');
    const mapContent = document.getElementById('mapContent');
    const maximizeButton = document.getElementById('maximizeButton');
    
    let isMaximized = false;
    let isDragging = false;
    let startX, startY, initialX, initialY;
    
    let currentTranslateX = 0; 
    let currentTranslateY = 0; 

    let preMaximizeTranslateX = 0;
    let preMaximizeTranslateY = 0;

    function updateMapPosition() {
        mapContent.style.transform = `translate3d(${currentTranslateX}px, ${currentTranslateY}px, 0)`;
    }

    function setInitialMapPosition() {
        const centerX = (mapBox.clientWidth - 2400) / 2;
        const centerY = (mapBox.clientHeight - 1200) / 2;

        currentTranslateX = centerX + 400; 
        currentTranslateY = centerY + 100;
        updateMapPosition();
    }

    window.addEventListener('load', setInitialMapPosition);
    window.addEventListener('resize', () => {
        if (!isMaximized) {
            recalculateBoundaries();
        } else {
             const boxWidth = mapBox.clientWidth;
             const boxHeight = mapBox.clientHeight;
             currentTranslateX = (boxWidth - 2400) / 2;
             currentTranslateY = (boxHeight - 1200) / 2;
             updateMapPosition();
        }
    });

    mapBox.addEventListener('mousedown', startDrag);
    mapBox.addEventListener('touchstart', startDrag);

    mapBox.addEventListener('mouseup', endDrag);
    mapBox.addEventListener('touchend', endDrag);

    mapBox.addEventListener('mousemove', drag);
    mapBox.addEventListener('touchmove', drag);

    function startDrag(e) {
        if (e.target.id === 'maximizeButton' || e.target.classList.contains('map-pointer')) return;

        isDragging = true;
        mapBox.classList.add('grabbing');
        e.preventDefault(); 
        
        const client = e.touches ? e.touches[0] : e;
        startX = client.clientX;
        startY = client.clientY;
        initialX = currentTranslateX;
        initialY = currentTranslateY;
    }

    function endDrag() {
        isDragging = false;
        mapBox.classList.remove('grabbing');
    }

    function drag(e) {
        if (!isDragging) return;
        
        const client = e.touches ? e.touches[0] : e;
        const dx = client.clientX - startX;
        const dy = client.clientY - startY;

        currentTranslateX = initialX + dx;
        currentTranslateY = initialY + dy;

        recalculateBoundaries(); 
    }

    function recalculateBoundaries() {
        if (isMaximized) {
             updateMapPosition();
             return;
        }

        const maxTranslateX = 0;
        const maxTranslateY = 0;
        
        const minTranslateX = mapBox.clientWidth - 2400;
        const minTranslateY = mapBox.clientHeight - 1200;
        
        currentTranslateX = Math.max(Math.min(currentTranslateX, maxTranslateX), minTranslateX);
        currentTranslateY = Math.max(Math.min(currentTranslateY, maxTranslateY), minTranslateY);

        updateMapPosition();
    }

    maximizeButton.addEventListener('click', toggleMaximize);

    function toggleMaximize() {
        isMaximized = !isMaximized;
        
        if (isMaximized) {
            preMaximizeTranslateX = currentTranslateX;
            preMaximizeTranslateY = currentTranslateY;

            mapBox.classList.add('maximized');
            maximizeButton.textContent = 'Minimize';
            document.body.style.overflow = 'hidden'; 

            setTimeout(() => {
                const boxWidth = mapBox.clientWidth;
                const boxHeight = mapBox.clientHeight;
                const contentWidth = 2400; 
                const contentHeight = 1200;
                currentTranslateX = (boxWidth - contentWidth) / 2;
                currentTranslateY = (boxHeight - contentHeight) / 2;

                updateMapPosition();
            }, 50);

        } else {
            mapBox.classList.remove('maximized');
            maximizeButton.textContent = 'Maximize';
            document.body.style.overflow = '';
            currentTranslateX = preMaximizeTranslateX;
            currentTranslateY = preMaximizeTranslateY;
            
            updateMapPosition();

            setTimeout(recalculateBoundaries, 350); 
        }
    }

    
    const heroSliderTrack = document.getElementById('heroSliderTrack');
    const heroSlides = heroSliderTrack ? heroSliderTrack.children : [];
    let heroIndex = 0;

    function updateHeroSlider() {
        if (!heroSliderTrack || heroSlides.length === 0) return;
        const offset = -heroIndex * 100;
        heroSliderTrack.style.transform = `translateX(${offset}%)`;
    }

    function nextHeroSlide() {
        if (!heroSlides.length) return;
        heroIndex = (heroIndex + 1) % heroSlides.length;
        updateHeroSlider();
    }

    setInterval(nextHeroSlide, 5000);

</script>

<div class="section" id="about">
    <h2><?php echo htmlspecialchars($aboutContent['title'] ?? 'ABOUT US'); ?></h2>
    <div class="info-card">
        <p>
            <?php echo nl2br(htmlspecialchars($aboutContent['body'] ?? '')); ?>
        </p>
    </div>
</div>

<div class="section" id="contact">
    <h2>CONTACT US</h2>
    <form class="contact-form" id="contactForm">
        <label>Your Name</label>
        <input type="text" id="contactName" required>

        <label>Your Email</label>
        <input type="email" id="contactEmail" required>

        <label>Your Message</label>
        <textarea id="contactMessage" required></textarea>

        <button type="submit">SEND</button>
        <div id="contactMessageResult" style="margin-top: 10px; font-size: 10px;"></div>
    </form>
</div>

<script>
   
    document.getElementById('contactForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const name = document.getElementById('contactName').value;
        const email = document.getElementById('contactEmail').value;
        const message = document.getElementById('contactMessage').value;
        const resultDiv = document.getElementById('contactMessageResult');
        
        try {
            const response = await fetch('../../api/contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name, email, message })
            });
            
            const data = await response.json();
            
            if (data.success) {
                resultDiv.textContent = 'Message sent successfully!';
                resultDiv.style.color = '#9be7c4';
                document.getElementById('contactForm').reset();
            } else {
                resultDiv.textContent = data.message || 'Failed to send message';
                resultDiv.style.color = '#ff8c8c';
            }
        } catch (error) {
            resultDiv.textContent = 'Error: Could not connect to server.';
            resultDiv.style.color = '#ff8c8c';
            console.error('Error:', error);
        }
    });
</script>

</body>
</html>
