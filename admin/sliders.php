<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Delete image file if exists
    $res = mysqli_query($conn, "SELECT image FROM sliders WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row && $row['image'] && file_exists("../uploads/".$row['image'])) {
        unlink("../uploads/".$row['image']);
    }
    mysqli_query($conn, "DELETE FROM sliders WHERE id = $id");
    header("Location: sliders.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $btn_text = mysqli_real_escape_string($conn, $_POST['btn_text']);
    $btn_link = mysqli_real_escape_string($conn, $_POST['btn_link']);
    
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = time().'_'.rand(1000,9999).'.'.$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
    }

    if (isset($_POST['add_slider'])) {
        mysqli_query($conn, "INSERT INTO sliders (title, subtitle, image, btn_text, btn_link) VALUES ('$title', '$subtitle', '$image', '$btn_text', '$btn_link')");
    } elseif (isset($_POST['update_slider'])) {
        $id = (int)$_POST['id'];
        $update_img = $image ? ", image='$image'" : "";
        if ($image) {
            // Delete old image
            $res = mysqli_query($conn, "SELECT image FROM sliders WHERE id = $id");
            $row = mysqli_fetch_assoc($res);
            if ($row && $row['image'] && file_exists("../uploads/".$row['image'])) {
                unlink("../uploads/".$row['image']);
            }
        }
        mysqli_query($conn, "UPDATE sliders SET title='$title', subtitle='$subtitle', btn_text='$btn_text', btn_link='$btn_link' $update_img WHERE id=$id");
    }
    header("Location: sliders.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Sliders</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add New Slider</button>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title / Subtitle</th>
                <th>Button</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sliders = mysqli_query($conn, "SELECT * FROM sliders ORDER BY id DESC");
            while($sl = mysqli_fetch_assoc($sliders)) {
                $sl_data = htmlspecialchars(json_encode($sl));
                echo '
                <tr>
                    <td><img src="../uploads/'.$sl['image'].'" style="height: 60px; border-radius: 10px;"></td>
                    <td>
                        <strong>'.$sl['title'].'</strong><br>
                        <small style="color: #888;">'.$sl['subtitle'].'</small>
                    </td>
                    <td><a href="'.$sl['btn_link'].'" target="_blank" class="btn btn-sm" style="background: #eee;">'.$sl['btn_text'].'</a></td>
                    <td>
                        <button onclick=\'openEditModal('.$sl_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; margin-right:5px;">Edit</button>
                        <a href="?delete='.$sl['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 50px auto; padding: 30px; border-radius: 20px;">
        <h3>Add New Slider</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Slider Title</label>
                <input type="text" name="title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Subtitle</label>
                <textarea name="subtitle" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 60px;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px;">Button Text</label>
                    <input type="text" name="btn_text" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px;">Button Link</label>
                    <input type="text" name="btn_link" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px;">Slider Image</label>
                <input type="file" name="image" required style="width:100%;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_slider" class="btn btn-primary">Save Slider</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 50px auto; padding: 30px; border-radius: 20px;">
        <h3>Edit Slider</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Slider Title</label>
                <input type="text" name="title" id="edit_title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Subtitle</label>
                <textarea name="subtitle" id="edit_subtitle" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 60px;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px;">Button Text</label>
                    <input type="text" name="btn_text" id="edit_btn_text" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px;">Button Link</label>
                    <input type="text" name="btn_link" id="edit_btn_link" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px;">Slider Image (Leave blank to keep current)</label>
                <input type="file" name="image" style="width:100%;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_slider" class="btn btn-primary">Update Slider</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_title').value = data.title;
    document.getElementById('edit_subtitle').value = data.subtitle;
    document.getElementById('edit_btn_text').value = data.btn_text;
    document.getElementById('edit_btn_link').value = data.btn_link;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>
