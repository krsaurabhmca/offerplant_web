<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM albums WHERE id = $id");
    header("Location: albums.php");
    exit();
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
}

// Handle Add
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_album'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = slugify($name);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $cover_photo = "";

    if (!empty($_POST['webp_image'])) {
        $data = $_POST['webp_image'];
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $data = base64_decode($data);
            $cover_photo = time() . '_album.webp';
            file_put_contents("../uploads/" . $cover_photo, $data);
        }
    }
    
    mysqli_query($conn, "INSERT INTO albums (name, slug, description, cover_photo) VALUES ('$name', '$slug', '$description', '$cover_photo')");
    header("Location: albums.php");
    exit();
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_album'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = slugify($name);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $update_sql = "UPDATE albums SET name='$name', slug='$slug', description='$description'";


    if (!empty($_POST['webp_image'])) {
        $data = $_POST['webp_image'];
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $data = base64_decode($data);
            $cover_photo = time() . '_album.webp';
            file_put_contents("../uploads/" . $cover_photo, $data);
            $update_sql .= ", cover_photo='$cover_photo'";
        }
    }
    
    $update_sql .= " WHERE id=$id";
    mysqli_query($conn, $update_sql);
    header("Location: albums.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h2 style="margin-bottom: 5px;">Media Gallery Albums</h2>
        <p style="color: #64748b; font-size: 14px;">Manage photo albums and video collections</p>
    </div>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create New Album
    </button>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table class="datatable">
        <thead>
            <tr>
                <th style="padding-left: 20px;">Cover</th>
                <th>Album Name</th>
                <th>Description</th>
                <th>Media Count</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $albums = mysqli_query($conn, "SELECT a.*, (SELECT COUNT(*) FROM gallery WHERE album_id = a.id) as media_count FROM albums a ORDER BY id DESC");
            while($alb = mysqli_fetch_assoc($albums)) {
                $alb_data = htmlspecialchars(json_encode($alb));
                echo '
                <tr>
                    <td style="padding-left: 20px;">
                        <img src="../uploads/'.($alb['cover_photo'] ?: 'placeholder.png').'" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;">
                    </td>
                    <td>
                        <a href="gallery.php?album_id='.$alb['id'].'" style="font-weight: 700; color: var(--primary);">'.$alb['name'].'</a>
                    </td>
                    <td style="max-width: 300px; font-size: 13px; color: #64748b;">'.$alb['description'].'</td>
                    <td>
                        <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">'.$alb['media_count'].' Items</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="gallery.php?album_id='.$alb['id'].'" class="btn btn-sm" style="background: #f0fdf4; color: #16a34a;"><i class="fas fa-images"></i> Manage Media</a>
                            <button onclick=\'openEditModal('.$alb_data.')\' class="btn btn-sm" style="background: #f0f7ff; color: #007bff;"><i class="fas fa-edit"></i> Edit</button>
                            <a href="?delete='.$alb['id'].'" onclick="return confirm(\'Deleting this album will remove all media inside it. Continue?\')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="admin-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; backdrop-filter: blur(5px);">
    <div style="background:#fff; width:500px; margin: 50px auto; padding: 30px; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">Create New Album</h3>
        <form method="POST" id="addAlbumForm">
            <input type="hidden" name="webp_image" id="add_webp_image">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Album Name</label>
                <input type="text" name="name" required placeholder="e.g., Annual Function 2024" style="width:100%; padding: 12px; border:1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Description</label>
                <textarea name="description" placeholder="A short description of this album..." style="width:100%; padding: 12px; border:1.5px solid #e2e8f0; border-radius: 12px; height: 80px; font-size: 14px;"></textarea>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Cover Photo (Auto WebP Optimization)</label>
                <input type="file" accept="image/*" onchange="handleImageUpload(this, 'add_webp_image', 'add_preview')" style="width:100%; font-size: 13px;">
                <div id="add_preview" style="margin-top: 10px; display: none;">
                    <img src="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 2px solid var(--primary);">
                    <p style="font-size: 11px; color: #16a34a; font-weight: 700; margin-top: 5px;"><i class="fas fa-check-circle"></i> Optimized & Ready</p>
                </div>
            </div>
            <div style="display:flex; gap: 12px;">
                <button type="submit" name="add_album" class="btn btn-primary" style="flex: 1;">Create Album</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#f1f5f9; flex: 1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="admin-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; backdrop-filter: blur(5px);">
    <div style="background:#fff; width:500px; margin: 50px auto; padding: 30px; border-radius: 24px;">
        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">Edit Album</h3>
        <form method="POST" id="editAlbumForm">
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="webp_image" id="edit_webp_image">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Album Name</label>
                <input type="text" name="name" id="edit_name" required style="width:100%; padding: 12px; border:1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Description</label>
                <textarea name="description" id="edit_description" style="width:100%; padding: 12px; border:1.5px solid #e2e8f0; border-radius: 12px; height: 80px; font-size: 14px;"></textarea>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Change Cover Photo</label>
                <input type="file" accept="image/*" onchange="handleImageUpload(this, 'edit_webp_image', 'edit_preview')" style="width:100%; font-size: 13px;">
                <div id="edit_preview" style="margin-top: 10px;">
                    <img id="edit_cover_preview" src="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 2px solid #e2e8f0;">
                </div>
            </div>
            <div style="display:flex; gap: 12px;">
                <button type="submit" name="update_album" class="btn btn-primary" style="flex: 1;">Update Album</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#f1f5f9; flex: 1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function handleImageUpload(input, hiddenId, previewId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const max = 1200;
                
                if (width > max || height > max) {
                    if (width > height) {
                        height = Math.round((height * max) / width);
                        width = max;
                    } else {
                        width = Math.round((width * max) / height);
                        height = max;
                    }
                }
                
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                const webpData = canvas.toDataURL('image/webp', 0.8);
                document.getElementById(hiddenId).value = webpData;
                
                const preview = document.getElementById(previewId);
                preview.style.display = 'block';
                preview.querySelector('img').src = webpData;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_description').value = data.description;
    document.getElementById('edit_cover_preview').src = '../uploads/' + (data.cover_photo || 'placeholder.png');
    document.getElementById('editModal').style.display = 'block';
}

// Close modals on click outside
window.onclick = function(event) {
    if (event.target.classList.contains('admin-modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<?php include 'includes/admin_footer.php'; ?>
