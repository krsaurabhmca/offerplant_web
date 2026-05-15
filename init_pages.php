<?php
include 'includes/db.php';

$q1 = "CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $q1);

$pages = [
    ['Terms & Condition', 'terms-and-condition'],
    ['Privacy Policy', 'privacy-policy'],
    ['Refund Policy', 'refund-policy'],
    ['Credit Policy', 'credit-policy']
];

foreach ($pages as $p) {
    $title = $p[0];
    $slug = $p[1];
    $content = "<h2>$title</h2><p>Default content for $title...</p>";
    $check = mysqli_query($conn, "SELECT id FROM pages WHERE slug = '$slug'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO pages (title, slug, content, status) VALUES ('$title', '$slug', '$content', 1)");
    }
}

echo "Pages table and default content verified.";
?>
