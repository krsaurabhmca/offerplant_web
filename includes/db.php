<?php
// Database Configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'offerplant';

// Establish Connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set Charset
mysqli_set_charset($conn, "utf8");

// Fetch Site Settings (Key-Value Structure)
$site = [];
$settings_query = "SELECT * FROM site_settings";
$settings_result = mysqli_query($conn, $settings_query);
if ($settings_result) {
    while ($row = mysqli_fetch_assoc($settings_result)) {
        $site[$row['meta_key']] = $row['meta_value'];
    }
}

// Default fallback if DB is empty or fails
if (empty($site)) {
    $site = [
        'site_name' => 'OfferPlant Technologies',
        'email' => 'ask@offerplant.com',
        'phone' => '+91 9431426600',
        'address' => '2nd Floor Godrej Building, Salempur Chapra Bihar 841301',
        'meta_title' => 'OfferPlant Technologies - IT Solutions',
        'meta_description' => 'Professional Website Design and App Development',
        'whatsapp_status' => '0',
        'popup_status' => '0'
    ];
}
?>

