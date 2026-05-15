<?php
include 'includes/db.php';

$queries = [
    "CREATE TABLE IF NOT EXISTS faqs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        order_no INT DEFAULT 0,
        status TINYINT(1) DEFAULT 1
    )",
    "CREATE TABLE IF NOT EXISTS visitor_stats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_url VARCHAR(255),
        views INT DEFAULT 0,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS active_users (
        session_id VARCHAR(100) PRIMARY KEY,
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "INSERT INTO site_settings (meta_key, meta_value) VALUES ('google_analytics', '') ON DUPLICATE KEY UPDATE meta_key = meta_key"
];

foreach ($queries as $q) {
    mysqli_query($conn, $q);
}

echo "Extra modules initialized.";
?>
