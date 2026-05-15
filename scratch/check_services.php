<?php
include 'includes/db.php';
$res = mysqli_query($conn, 'SELECT title, icon FROM services');
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
