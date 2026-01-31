<?php require_once '../../../config.php'; ?>
<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <title>Place Info</title> 
        <link rel="stylesheet" href="style.css"> 
    </head> 
    <body> 
        <nav> 
            <a href="../home.php">← Back</a> 
        </nav> 
        <div class="place-wrap"> 
            <div class="place-image"> 
                <img id="placeImg" alt="Place Image"> 
                <div class="image-frame"></div> 
            </div> 
            <section class="section place-info"> 
                <h2 id="placeTitle">Loading...</h2> 
                <p id="placeDesc"></p> 
                <div class="place-meta" id="placeExtra"></div> 
            </section> 
        </div> 
        <script>
            async function loadPlace() {
                const urlParams = new URLSearchParams(window.location.search);
                const placeName = urlParams.get('place');
                
                if (!placeName) {
                    document.getElementById('placeTitle').textContent = 'Place not found';
                    return;
                }
                
                try {
                    const response = await fetch(`../../api/places.php?place=${encodeURIComponent(placeName)}`);
                    const place = await response.json();
                    
                    if (place.error) {
                        document.getElementById('placeTitle').textContent = 'Place not found';
                        return;
                    }
                    
                    document.getElementById('placeTitle').textContent = place.title;
                    document.getElementById('placeDesc').textContent = place.description;
                    document.getElementById('placeImg').src = place.image;
                    
                    const extraContainer = document.getElementById('placeExtra');
                    extraContainer.innerHTML = '';
                    
                    if (place.details) {
                        for (const [key, value] of Object.entries(place.details)) {
                            const item = document.createElement('div');
                            item.classList.add('detail-item');
                            
                            const label = document.createElement('strong');
                            label.textContent = key.replace(/([A-Z])/g, ' $1') + ': ';
                            
                            item.appendChild(label);
                            
                            if (Array.isArray(value)) {
                                const ul = document.createElement('ul');
                                value.forEach(val => {
                                    const li = document.createElement('li');
                                    li.textContent = val;
                                    ul.appendChild(li);
                                });
                                item.appendChild(ul);
                            } else {
                                const span = document.createElement('span');
                                span.textContent = value;
                                item.appendChild(span);
                            }
                            
                            extraContainer.appendChild(item);
                        }
                    }
                } catch (error) {
                    console.error('Error loading place:', error);
                    document.getElementById('placeTitle').textContent = 'Error loading place';
                }
            }
            
            
            loadPlace();
        </script>
    </body> 
</html>
