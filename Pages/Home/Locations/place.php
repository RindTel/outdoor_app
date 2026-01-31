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

                <div id="ticketSection" style="margin-top: 30px; border-top: 4px solid #000; padding-top: 20px; display:none;">
                    <h3 style="font-size: 14px; margin-bottom: 15px;">GET TICKETS</h3>
                    <form action="../../Tickets/process_order.php" method="POST" class="ticket-form" style="max-width: 300px; margin: 0 auto; text-align: left;">
                        <input type="hidden" name="place_id" id="formPlaceId">
                        <input type="hidden" id="rawPrice" value="0">
                        
                        <div style="margin-bottom: 15px; text-align: center;">
                            <span style="font-size: 10px; color: #aaa;">PRICE PER TICKET</span><br>
                            <span id="ticketPriceDisplay" style="font-size: 16px; color: #9be7c4;">$0.00</span>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label for="visit_date" style="font-size:10px; display:block; margin-bottom: 5px;">VISIT DATE</label>
                            <input type="date" name="visit_date" required style="padding: 10px; width: 100%; border: 3px solid #000; background: #fff; font-family: inherit; font-size: 10px; box-sizing: border-box;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="quantity" style="font-size:10px; display:block; margin-bottom: 5px;">QUANTITY</label>
                            <input type="number" name="quantity" id="ticketQty" value="1" min="1" required style="padding: 10px; width: 100%; border: 3px solid #000; font-family: inherit; font-size: 10px; box-sizing: border-box;">
                        </div>

                        <button type="submit" style="width: 100%; padding: 15px; background: #00ff88; border: 3px solid #000; color: #000; font-family: inherit; cursor: pointer; font-size: 12px; box-shadow: 4px 4px 0 #000; transition: transform 0.1s;">
                            BUY NOW <span id="totalPriceDisplay">($0.00)</span>
                        </button>
                        <p style="font-size: 8px; margin-top: 8px; color: #888; text-align: center;">*View Receipt Instantly</p>
                    </form>
                </div>
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
                    const response = await fetch(`../../../api/places.php?place=${encodeURIComponent(placeName)}`);
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

                    const ticketSection = document.getElementById('ticketSection');
                    const formPlaceId = document.getElementById('formPlaceId');
                    const priceDisplay = document.getElementById('ticketPriceDisplay');
                    const rawPriceInput = document.getElementById('rawPrice');
                    const qtyInput = document.getElementById('ticketQty');
                    const totalDisplay = document.getElementById('totalPriceDisplay');

                    if (place.ticket_price !== undefined && place.ticket_price !== null) {
                        const price = parseFloat(place.ticket_price);
                        
                        ticketSection.style.display = 'block';
                        formPlaceId.value = place.id;
                        rawPriceInput.value = price;

                        if (price === 0) {
                             priceDisplay.textContent = 'FREE';
                             totalDisplay.textContent = '(FREE)';
                        } else {
                             priceDisplay.textContent = '$' + price.toFixed(2);
                        }

                        const updateTotal = () => {
                            if (price === 0) {
                                totalDisplay.textContent = '(FREE)';
                                return;
                            }
                            
                            let qty = parseInt(qtyInput.value);
                            if (isNaN(qty) || qty < 1) qty = 1;
                            
                            const total = price * qty;
                            totalDisplay.textContent = '($' + total.toFixed(2) + ')';
                        };

                        qtyInput.addEventListener('input', updateTotal);
                        qtyInput.addEventListener('change', updateTotal);

                        updateTotal();
                        
                    } else {
                         ticketSection.style.display = 'none';
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
