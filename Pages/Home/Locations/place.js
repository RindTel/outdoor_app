const places = {
    Restaurant: {
        title: "Retro Diner",
        img: "Balcony1.png",
        description: "Step into a fully immersive 1950s diner with neon signs, jukeboxes, checkered floors, and classic diner booths. Enjoy comfort food crafted from fresh ingredients and relive the golden era of American diners.",
        details: {
            style: "Retro",
            cuisine: "American Classics",
            popularDishes: ["Burgers", "Fries", "Milkshakes", "Pancakes", "Meatloaf", "Root Beer Floats"],
            ambiance: ["Lively", "Nostalgic", "Cozy", "Family-Friendly"],
            seating: ["Booths", "Counter", "Outdoor Patio"],
            entertainment: ["Jukebox Music", "Weekly Trivia Nights", "Monthly Themed Events"],
            services: ["Takeout", "Delivery", "Catering"],
            tips: ["Try the weekly special", "Best time for photos is evening with neon lights"],
            accessibility: ["Wheelchair Accessible", "High Chairs Available"],
            signatureFeature: "Vintage Car Photo Booth"
        }
    },
    Yacht: {
        title: "Yacht Tour",
        img: "yacht.png",
        description: "Luxury yacht tours along the sparkling coastline offering stunning sea views, fresh ocean breeze, and a relaxing atmosphere perfect for celebrations or romantic outings.",
        details: {
            hours: "6am-10pm",
            duration: "2-3 hours per trip",
            tourTypes: ["Sunset Cruises", "Daytime Excursions", "Private Charters"],
            amenities: ["Snacks", "Drinks", "Sun Deck", "Restrooms", "Lounge Seating"],
            activities: ["Sightseeing", "Swimming Stops", "Photography", "Bird Watching"],
            tips: ["Bring sunscreen", "Book in advance for weekends", "Best views at sunset"],
            accessibility: ["Crew Assistance Available", "Safety Gear Provided"],
            special: ["Onboard Live Music", "Themed Cruises"],
            nearbyAttractions: ["Coastal Beaches", "Marina Restaurants"],
            signatureFeature: "Glass-bottom viewing deck"
        }
    },
    Museum: {
        title: "History Museum",
        img: "kinezi.png",
        description: "A sprawling museum that houses artifacts spanning centuries—from ancient civilizations to modern history. Perfect for history enthusiasts, families, and students.",
        details: {
            tickets: "$12",
            hours: "9am-6pm",
            exhibits: ["Ancient Weapons", "Medieval Costumes", "Fossils", "Local History", "Interactive Science Sections"],
            facilities: ["Cafeteria", "Gift Shop", "Wheelchair Accessible", "Restrooms", "Lockers"],
            tours: ["Guided Tours", "Audio Guides", "School Group Tours"],
            activities: ["Workshops", "Storytelling", "Historical Reenactments", "Special Exhibits Monthly"],
            tips: ["Visit early to avoid crowds", "Photography allowed in most areas"],
            signatureFeature: "Life-size Dinosaur Skeleton in Main Hall",
            nearby: ["Cafes", "Public Parks", "Historical Landmarks"]
        }
    },
    ShoppingMall: {
        title: "Shopping Mall",
        img: "ShoppingMall.png",
        description: "A bustling modern mall with retail stores, entertainment zones, and dining options. Perfect for family outings, social hangouts, and shopping sprees.",
        details: {
            seats: 500,
            shops: ["Fashion", "Electronics", "Toys", "Books", "Jewelry", "Sportswear", "Beauty & Cosmetics"],
            entertainment: ["Movie Theater", "Arcade", "Live Performances", "Seasonal Events"],
            facilities: ["Free Wi-Fi", "Parking", "Baby Care Rooms", "ATMs", "Restrooms"],
            services: ["Personal Shopper", "Lost & Found", "Info Desk"],
            dining: ["Cafes", "Fast Food", "Fine Dining", "Food Court"],
            tips: ["Best shopping deals on weekends", "Join loyalty program for discounts"],
            signatureFeature: "Rooftop Garden with Fountain",
            accessibility: ["Wheelchair Accessible", "Elevator Access"],
            nearby: ["Public Transport", "Hotels"]
        }
    },
    Stadium: {
        title: "City Stadium",
        img: "Stadium.png",
        description: "A large-scale stadium hosting football matches, concerts, festivals, and cultural events. With modern facilities and ample seating, it’s a hub for excitement.",
        details: {
            capacity: 20000,
            events: ["Football", "Rugby", "Concerts", "Festivals", "Community Events"],
            facilities: ["VIP Boxes", "Food Stalls", "Restrooms", "Parking", "Merchandise Shops"],
            amenities: ["Big Screens", "Live Commentary", "First Aid", "Security Checkpoints"],
            activities: ["Sports Training Camps", "Fan Zones", "Guided Tours"],
            tips: ["Arrive early for best seats", "Check schedule for event timings"],
            signatureFeature: "Night Lighting Shows",
            accessibility: ["Wheelchair Accessible", "Ramps", "Reserved Seating"],
            nearby: ["Public Transport", "Hotels", "Restaurants"]
        }
    },
    Library: {
        title: "Central Library",
        img: "shteti.png",
        description: "A haven for book lovers, researchers, and students. Thousands of books, quiet study zones, and modern facilities make it ideal for learning or relaxing.",
        details: {
            freeEntry: true,
            sections: ["Fiction", "Non-fiction", "Research", "Rare Collections", "Magazines", "Digital Media"],
            facilities: ["Wi-Fi", "Study Rooms", "Computers", "Printing", "Lockers", "Restrooms"],
            activities: ["Reading Clubs", "Workshops", "Storytelling", "Author Visits"],
            tips: ["Bring your library card for borrowing", "Check out digital archives online"],
            ambiance: ["Quiet", "Well-Lit", "Peaceful"],
            accessibility: ["Elevator Access", "Wheelchair Friendly"],
            signatureFeature: "Historical Manuscript Room",
            nearby: ["Cafes", "Public Transport", "Parks"]
        }
    },
    Market: {
        title: "Open Market",
        img: "StreetMall.png",
        description: "A lively market brimming with fresh produce, handmade crafts, and local delicacies. Perfect for foodies, shoppers, and explorers.",
        details: {
            hours: "7am-5pm",
            specialty: ["Fresh Fruits", "Vegetables", "Baked Goods", "Local Handicrafts", "Spices", "Flowers"],
            activities: ["Food Tasting", "Street Performances", "Seasonal Festivals", "Workshops"],
            facilities: ["Rest Areas", "Public Restrooms", "Trash Bins"],
            tips: ["Best early morning for fresh produce", "Bargain politely", "Bring cash"],
            ambiance: ["Colorful", "Bustling", "Social"],
            accessibility: ["Wide Aisles", "Ramps"],
            signatureFeature: "Artisan Corner",
            nearby: ["Cafes", "Parks", "Transportation"]
        }
    },
    Lake: {
        title: "Blue Lake",
        img: "Kabin.png",
        description: "A scenic lake perfect for boating, fishing, walking, or relaxing by the water. Ideal for nature lovers and families.",
        details: {
            freeEntry: true,
            activities: ["Boating", "Kayaking", "Fishing", "Walking", "Bird Watching", "Picnicking"],
            facilities: ["Boat Rentals", "Restrooms", "Picnic Areas", "Benches", "Walking Trails"],
            tips: ["Bring picnic supplies", "Sunscreen", "Binoculars for bird watching"],
            ambiance: ["Calm", "Serene", "Family-Friendly", "Photogenic"],
            accessibility: ["Trails Suitable for All Ages", "Some Wheelchair Access"],
            signatureFeature: "Crystal Clear Waters Reflecting the Sky",
            nearby: ["Cafes", "Parks", "Hiking Trails"]
        }
    },
  Estate: {
    title: "Real Estate",
    img: "realestate.png",
    description: "A modern real estate building featuring contemporary design, functional spaces, and a prime location for residential or commercial use.",
    details: {
        hours: "9am-5pm",
        highlights: ["Modern Design", "Spacious Units", "Secure Access", "Prime Location"],
        activities: ["Property Tours", "Open House Visits", "Agent Meetings"],
        facilities: ["Parking", "Elevator", "Security"],
        tips: ["Schedule visits early", "Ask about pricing", "Check amenities"],
        ambiance: ["Modern", "Professional", "Comfortable"],
        accessibility: ["Wheelchair Access", "Ramps"],
        signatureFeature: "City View from Upper Floors",
        nearby: ["Shops", "Public Transport", "Restaurants"]
    }
},

    Mountain: {
        title: "Mountain Peak",
        img: "mountain.png",
        description: "A breathtaking mountain offering hiking trails, panoramic views, and historic landmarks. Perfect for adventurers and photographers.",
        details: {
            tickets: "$15",
            activities: ["Hiking", "Sightseeing", "Photography", "Camping", "Bird Watching", "Rock Climbing"],
            facilities: ["Rest Areas", "Viewpoints", "Signage", "Toilets", "First Aid"],
            tips: ["Carry water", "Wear proper footwear", "Best sunrise/sunset viewpoints"],
            ambiance: ["Scenic", "Fresh Air", "Adventurous", "Peaceful"],
            accessibility: ["Moderate Trails", "Some Wheelchair Accessible Spots"],
            signatureFeature: "Medieval Ruins on Summit",
            nearby: ["Hiking Lodges", "Restaurants", "Transportation"]
        }
    }
};


function showPlace(placeKey) {
    const place = places[placeKey];
    if (!place) return console.error("Place not found:", placeKey);

    document.getElementById("placeTitle").textContent = place.title;
    document.getElementById("placeDesc").textContent = place.description;
    document.getElementById("placeImg").src = place.img;

    const extraContainer = document.getElementById("placeExtra");
    extraContainer.innerHTML = "";

    for (const [key, value] of Object.entries(place.details)) {
        const item = document.createElement("div");
        item.classList.add("detail-item");

        const label = document.createElement("strong");
        label.textContent = key.replace(/([A-Z])/g, " $1") + ": ";

        item.appendChild(label);

        if (Array.isArray(value)) {
            const ul = document.createElement("ul");
            value.forEach(val => {
                const li = document.createElement("li");
                li.textContent = val;
                ul.appendChild(li);
            });
            item.appendChild(ul);
        } else {
            const span = document.createElement("span");
            span.textContent = value;
            item.appendChild(span);
        }

        extraContainer.appendChild(item);
    }
}


const urlParams = new URLSearchParams(window.location.search);
const placeFromURL = urlParams.get("place");


const defaultPlace = placeFromURL || Object.keys(places)[0];
showPlace(defaultPlace);


document.querySelectorAll(".map-pointer").forEach(pointer => {
    pointer.addEventListener("click", () => {
        const placeKey = pointer.getAttribute("data-place");
        if (placeKey) showPlace(placeKey);
    });
});