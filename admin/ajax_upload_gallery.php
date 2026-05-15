<?php
include 'auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['webp_image'])) {
    $album_id = (int)$_POST['album_id'];
    $data = $_POST['webp_image'];
    
    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
        $data = substr($data, strpos($data, ',') + 1);
        $data = base64_decode($data);
        $filename = time() . '_' . rand(100, 999) . '.webp';
        
        if (file_put_contents("../uploads/" . $filename, $data)) {
            $sql = "INSERT INTO gallery (album_id, media_type, media_url) VALUES ($album_id, 'photo', '$filename')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['status' => 'success', 'filename' => $filename]);
            } else {
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image data']);
    }
}
?>
