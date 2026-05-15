-- OfferPlant Technologies Database Schema
-- Last Updated: 2026-05-15

CREATE DATABASE IF NOT EXISTS offerplant;
USE offerplant;

-- Site Settings (Key-Value Pair Structure)
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meta_key VARCHAR(100) NOT NULL UNIQUE,
    meta_value TEXT
);

-- Seed Site Settings
INSERT INTO site_settings (meta_key, meta_value) VALUES 
('site_name', 'OfferPlant Technologies'),
('email', 'ask@offerplant.com'),
('phone', '+91 9431426600'),
('address', '2nd Floor Godrej Building, Salempur Chapra Bihar 841301'),
('meta_title', 'OfferPlant Technologies - Best IT Company in Bihar'),
('meta_description', 'OfferPlant Technologies deals in Website Design, Web App Development, and Android App Development.'),
('facebook_url', ''),
('twitter_url', ''),
('linkedin_url', ''),
('instagram_url', ''),
('youtube_url', ''),
('whatsapp_number', '919431426600'),
('whatsapp_status', '1'),
('google_map', ''),
('popup_status', '0'),
('popup_image', ''),
('logo', ''),
('site_theme', '')
ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(100), -- FontAwesome class
    status TINYINT(1) DEFAULT 1
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    status TINYINT(1) DEFAULT 1
);

-- Enquiries Table
CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin (Password: admin123)
INSERT INTO admins (username, password, full_name) VALUES 
('admin', '$2y$10$8v/5.1mI2P2q/O5X5Y5o5.Z0Y8u8u8u8u8u8u8u8u8u8u8u8u8u8u', 'OfferPlant Admin')
ON DUPLICATE KEY UPDATE username = username;

-- Sliders Table
CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    subtitle TEXT,
    image VARCHAR(255),
    btn_text VARCHAR(50),
    btn_link VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blogs Table
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    image VARCHAR(255),
    author VARCHAR(100) DEFAULT 'Admin',
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pricing Plans Table
CREATE TABLE IF NOT EXISTS pricing_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2),
    offer_price DECIMAL(10, 2),
    features TEXT,
    badge VARCHAR(50),
    offer_ends DATETIME,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    designation VARCHAR(100),
    company VARCHAR(100),
    feedback TEXT NOT NULL,
    image VARCHAR(255),
    rating TINYINT(1) DEFAULT 5,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Custom Pages Table
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menus Table
CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    order_no INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1
);

-- FAQ Table
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    order_no INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1
);

-- Seed Site Settings (Adding Analytics)
INSERT INTO site_settings (meta_key, meta_value) VALUES 
('google_analytics', '')
ON DUPLICATE KEY UPDATE meta_value = meta_value;


INSERT INTO menus (title, url, order_no) VALUES 
('Home', 'index.php', 1),
('Services', 'index.php#services', 2),
('Products', 'index.php#products', 3),
('Pricing', 'index.php#pricing', 4),
('Testimonials', 'index.php#testimonials', 5),
('Blog', 'blog.php', 6),
('Contact', 'index.php#contact', 7);


-- Visitor Stats Table
CREATE TABLE IF NOT EXISTS visitor_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_url VARCHAR(255),
    views INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Active Users Table (Simple Session Tracking)
CREATE TABLE IF NOT EXISTS active_users (
    session_id VARCHAR(100) PRIMARY KEY,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO services (title, description, icon) VALUES 
('Website Design', 'Professional and responsive website designs tailored to your business needs.', 'fa-laptop-code'),
('Web App Development', 'Scalable and secure web applications using modern technologies.', 'fa-globe'),
('Android App Development', 'Custom Android applications with seamless performance and UI.', 'fa-android');

INSERT INTO products (name, url) VALUES 
('Bine', 'https://bine.offerplant.com'),
('Apprise', 'https://apprise.offerplant.com'),
('Billow', 'https://billow.in');

INSERT INTO pages (title, slug, content, status) VALUES 
('Terms & Condition', 'terms-and-condition', '<h2>Terms & Condition</h2><p>Default content for Terms & Condition...</p>', 1),
('Privacy Policy', 'privacy-policy', '<h2>Privacy Policy</h2><p>Default content for Privacy Policy...</p>', 1),
('Refund Policy', 'refund-policy', '<h2>Refund Policy</h2><p>Default content for Refund Policy...</p>', 1),
('Credit Policy', 'credit-policy', '<h2>Credit Policy</h2><p>Default content for Credit Policy...</p>', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);
