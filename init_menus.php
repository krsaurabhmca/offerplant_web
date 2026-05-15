<?php
include 'includes/db.php';

$q1 = "CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    order_no INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1
)";
mysqli_query($conn, $q1);

$check = mysqli_query($conn, "SELECT id FROM menus");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO menus (title, url, order_no) VALUES 
    ('Home', 'index.php', 1),
    ('Services', 'index.php#services', 2),
    ('Products', 'index.php#products', 3),
    ('Pricing', 'index.php#pricing', 4),
    ('Testimonials', 'index.php#testimonials', 5),
    ('Blog', 'blog.php', 6),
    ('Contact', 'index.php#contact', 7)");
    echo "Menus seeded.<br>";
}

echo "Menus table verified.";
?>
