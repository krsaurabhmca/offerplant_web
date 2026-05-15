<?php 
include 'auth_check.php'; 

if (!isset($_GET['album_id'])) {
    header("Location: albums.php");
    exit();
}

$album_id = (int)$_GET['album_id'];
$album_query = mysqli_query($conn, "SELECT * FROM albums WHERE id = $album_id");
$album = mysqli_fetch_assoc($album_query);

if (!$album) {
    header("Location: albums.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM gallery WHERE id = $id");
    header("Location: gallery.php?album_id=$album_id");
    exit();
}

// Handle Photo Add (AJAX/Single)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_photo'])) {
    if (!empty($_POST['webp_image'])) {
        $data = $_POST['webp_image'];
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $data = base64_decode($data);
            $filename = time() . '_' . rand(100, 999) . '.webp';
            file_put_contents("../uploads/" . $filename, $data);
            
            mysqli_query($conn, "INSERT INTO gallery (album_id, media_type, media_url) VALUES ($album_id, 'photo', '$filename')");
        }
    }
    header("Location: gallery.php?album_id=$album_id");
    exit();
}

// Handle Video Add
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_video'])) {
    $url = mysqli_real_escape_string($conn, $_POST['video_url']);
    
    // Extract YouTube ID
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    $video_id = $match[1] ?? null;
    
    if ($video_id) {
        mysqli_query($conn, "INSERT INTO gallery (album_id, media_type, media_url, video_id) VALUES ($album_id, 'video', '$url', '$video_id')");
    }
    header("Location: gallery.php?album_id=$album_id");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
            <a href="albums.php" style="color: #64748b; font-size: 18px;"><i class="fas fa-arrow-left"></i></a>
            <h2 style="margin: 0;"><?php echo $album['name']; ?></h2>
        </div>
        <p style="color: #64748b; font-size: 14px;">Manage photos and videos in this album</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button onclick="openModal('photoModal')" class="btn btn-primary"><i class="fas fa-camera"></i> Add Photo</button>
        <button onclick="openModal('videoModal')" class="btn btn-dark"><i class="fab fa-youtube"></i> Add Video</button>
    </div>
</div>

<div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
    <?php
    $media = mysqli_query($conn, "SELECT * FROM gallery WHERE album_id = $album_id ORDER BY id DESC");
    while($item = mysqli_fetch_assoc($media)) {
        echo '
        <div class="card" style="padding: 10px; position: relative;">
            <div style="aspect-ratio: 1/1; overflow: hidden; border-radius: 12px; margin-bottom: 10px; background: #f1f5f9;">';
                if ($item['media_type'] == 'photo') {
                    echo '<img src="../uploads/'.$item['media_url'].'" style="width: 100%; height: 100%; object-fit: cover;">';
                } else {
                    echo '<img src="https://img.youtube.com/vi/'.$item['video_id'].'/hqdefault.jpg" style="width: 100%; height: 100%; object-fit: cover;">';
                    echo '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-size: 30px; opacity: 0.8;"><i class="fab fa-youtube"></i></div>';
                }
            echo '
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8;">'.strtoupper($item['media_type']).'</span>
                <a href="?album_id='.$album_id.'&delete='.$item['id'].'" onclick="return confirm(\'Remove this item?\')" style="color: #ef4444; font-size: 14px;"><i class="fas fa-trash-alt"></i></a>
            </div>
        </div>';
    }
    ?>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="admin-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; backdrop-filter: blur(5px);">
    <div style="background:#fff; width:500px; margin: 50px auto; padding: 30px; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 20px; font-weight: 800;">Upload Multiple Photos</h3>
        <div id="upload-status" style="display:none; margin-bottom: 20px; padding: 15px; background: #f0fdf4; border-radius: 12px; border: 1px solid #dcfce7;">
            <p id="status-text" style="font-size: 14px; font-weight: 700; color: #16a34a; margin-bottom: 10px;">Processing...</p>
            <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                <div id="progress-bar" style="width: 0%; height: 100%; background: #16a34a; transition: 0.3s;"></div>
            </div>
        </div>

        <div id="drop-area" style="border: 2px dashed #cbd5e1; border-radius: 20px; padding: 40px; text-align: center; cursor: pointer; transition: 0.3s; background: #f8fafc;">
            <i class="fas fa-images" style="font-size: 40px; color: #94a3b8; margin-bottom: 15px;"></i>
            <h4 style="color: #475569; margin-bottom: 8px;">Select or Drag Photos</h4>
            <p style="font-size: 13px; color: #64748b;">All images will be auto-optimized to WebP</p>
            <input type="file" id="fileElem" accept="image/*" multiple onchange="handleBatchUpload(this)" style="display:none">
        </div>

        <div style="margin-top: 25px; text-align: right;">
            <button type="button" onclick="closeModal('photoModal')" id="closeBtn" class="btn" style="background:#f1f5f9;">Cancel</button>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="admin-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; backdrop-filter: blur(5px);">
    <div style="background:#fff; width:450px; margin: 100px auto; padding: 30px; border-radius: 24px;">
        <h3 style="margin-bottom: 20px; font-weight: 800;">Add YouTube Video</h3>
        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600;">YouTube URL</label>
                <input type="url" name="video_url" required placeholder="https://www.youtube.com/watch?v=..." style="width:100%; padding: 12px; border:1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_video" class="btn btn-primary" style="flex: 1;">Save Video</button>
                <button type="button" onclick="closeModal('videoModal')" class="btn" style="background:#f1f5f9; flex: 1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).style.display = 'block'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.getElementById('drop-area').addEventListener('click', () => {
    document.getElementById('fileElem').click();
});

async function handleBatchUpload(input) {
    if (!input.files || input.files.length === 0) return;
    
    const files = Array.from(input.files);
    const total = files.length;
    const albumId = <?php echo $album_id; ?>;
    
    document.getElementById('drop-area').style.display = 'none';
    document.getElementById('upload-status').style.display = 'block';
    document.getElementById('closeBtn').style.display = 'none';
    
    for (let i = 0; i < total; i++) {
        const file = files[i];
        document.getElementById('status-text').innerText = `Optimizing & Uploading: ${i + 1} of ${total}`;
        
        try {
            const webpData = await convertToWebP(file);
            await uploadImage(webpData, albumId);
            
            const percent = ((i + 1) / total) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
        } catch (err) {
            console.error('Upload failed:', err);
        }
    }
    
    document.getElementById('status-text').innerText = 'Upload Complete! Reloading...';
    setTimeout(() => { location.reload(); }, 1000);
}

function convertToWebP(file) {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const max = 1600;
                if (width > max || height > max) {
                    if (width > height) { height = Math.round((height * max) / width); width = max; }
                    else { width = Math.round((width * max) / height); height = max; }
                }
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                resolve(canvas.toDataURL('image/webp', 0.85));
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

function uploadImage(base64Data, albumId) {
    const formData = new FormData();
    formData.append('webp_image', base64Data);
    formData.append('album_id', albumId);
    
    return fetch('ajax_upload_gallery.php', {
        method: 'POST',
        body: formData
    }).then(res => res.json());
}

// Close modals on click outside
window.onclick = function(event) {
    if (event.target.classList.contains('admin-modal')) {
        event.target.style.display = 'none';
    }
}
</script>


<?php include 'includes/admin_footer.php'; ?>
