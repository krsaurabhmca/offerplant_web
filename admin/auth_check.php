<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include_once '../includes/db.php';
include_once '../includes/functions.php';
?>
