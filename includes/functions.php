<?php
// OfferPlant Helper Functions

/**
 * Sanitize input data
 */
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

/**
 * Get service by ID
 */
function get_service($conn, $id) {
    $id = (int)$id;
    $query = "SELECT * FROM services WHERE id = $id AND status = 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

/**
 * Get all products
 */
function get_all_products($conn) {
    $query = "SELECT * FROM products WHERE status = 1 ORDER BY name ASC";
    return mysqli_query($conn, $query);
}
?>
