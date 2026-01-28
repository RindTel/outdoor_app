
CREATE DATABASE IF NOT EXISTS outdoor_planner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE outdoor_planner;


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    position_x INT NOT NULL,
    position_y INT NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL DEFAULT 10.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,
    updated_by INT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS place_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT NOT NULL,
    detail_key VARCHAR(100) NOT NULL,
    detail_value TEXT,
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS ticket_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT NOT NULL,
    buyer_name VARCHAR(100) NOT NULL,
    buyer_email VARCHAR(100) NOT NULL,
    visit_date DATE NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', 'admin', 'admin'),
('leonbossi', 'leon@gmail.com', 'password123', 'user'),
('rindrit', 'rindrit@gmail.com', 'password123', 'user');


INSERT INTO places (name, title, description, image, position_x, position_y, ticket_price) VALUES
('Restaurant', 'Retro Diner', 'Step into a fully immersive 1950s diner with neon signs, jukeboxes, checkered floors, and classic diner booths.', 'Balcony1.png', 1310, 390, 15.00),
('Yacht', 'Yacht Tour', 'Luxury yacht tours along the sparkling coastline offering stunning sea views, fresh ocean breeze, and a relaxing atmosphere.', 'yacht.png', 1630, 550, 40.00),
('Museum', 'History Museum', 'A sprawling museum housing artifacts spanning centuries—from ancient civilizations to modern history.', 'kinezi.png', 740, 255, 12.00),
('ShoppingMall', 'Shopping Mall', 'A bustling modern mall with retail stores, entertainment zones, and dining options.', 'ShoppingMall.png', 550, 630, 0.00),
('Stadium', 'City Stadium', 'A large stadium hosting football matches, concerts, festivals, and cultural events.', 'Stadium.png', 1087, 650, 25.00),
('Library', 'Central Library', 'A haven for book lovers, researchers, and students.', 'shteti.png', 360, 370, 0.00),
('Market', 'Open Market', 'A lively market with fresh produce, handmade crafts, and local delicacies.', 'StreetMall.png', 760, 840, 0.00),
('Lake', 'Blue Lake', 'A scenic lake perfect for boating, fishing, walking, or relaxing by the water.', 'Kabin.png', 1500, 120, 8.00),
('Estate', 'Real Estate', 'Modern real estate building featuring contemporary design and functional spaces.', 'realestate.png', 500, 180, 0.00),
('Mountain', 'Mountain Peak', 'A breathtaking mountain offering hiking trails, panoramic views, and historic landmarks.', 'mountain.png', 2245, 160, 15.00);


INSERT INTO site_content (slug, title, body) VALUES
('home_hero', 'WELCOME TO OUR WORLD', 'Plan your perfect day outdoors with interactive maps, detailed locations, and ticket booking.'),
('about_text', 'ABOUT US', 'We believe that the best way to learn and grow is by exploring the world around you. Our retro-inspired outdoor planner helps you discover new places, events, and experiences in a playful way.');


INSERT INTO place_details (place_id, detail_key, detail_value) VALUES
(1, 'cuisine', 'American Classics'),
(1, 'ambiance', 'Cozy, Retro'),
(2, 'activities', 'Boating, Fishing, Walking'),
(2, 'accessibility', 'Wheelchair Accessible'),
(3, 'capacity', '20000'),
(3, 'facilities', 'VIP Boxes, Food Stalls, Parking');


INSERT INTO contact_messages (name, email, message, user_id) VALUES
('Greta Ahma', 'greta@gmail.com', 'SHUMM MIR 10she dyt', NULL),
('Pjesa tjt e grupit', 'GPZa@gmail.com', 'Spe prezantojna hiq na', NULL);
