<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM services WHERE id = $id");
    header("Location: services.php");
    exit();
}

// Handle Add
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    
    mysqli_query($conn, "INSERT INTO services (title, description, icon) VALUES ('$title', '$description', '$icon')");
    header("Location: services.php");
    exit();
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_service'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    
    mysqli_query($conn, "UPDATE services SET title='$title', description='$description', icon='$icon' WHERE id=$id");
    header("Location: services.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Services</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add New Service</button>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Icon</th>
                <th>Service Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $services = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
            while($srv = mysqli_fetch_assoc($services)) {
                $srv_data = htmlspecialchars(json_encode($srv));
                echo '
                <tr>
                    <td><i class="fas '.$srv['icon'].'" style="font-size: 20px; color: var(--secondary);"></i></td>
                    <td><strong>'.$srv['title'].'</strong></td>
                    <td style="max-width: 300px; font-size: 14px; color: #666;">'.$srv['description'].'</td>
                    <td>
                        <button onclick=\'openEditModal('.$srv_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Edit"><i class="fas fa-edit"></i></button>
                        <a href="?delete='.$srv['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger" style="border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 100px auto; padding: 30px; border-radius: 20px;">
        <h3>Add New Service</h3>
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Service Title</label>
                <input type="text" name="title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Description</label>
                <textarea name="description" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 100px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px;">FontAwesome Icon Class (e.g. fa-code)</label>
                <input type="text" name="icon" placeholder="fa-laptop-code" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_service" class="btn btn-primary">Save Service</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 100px auto; padding: 30px; border-radius: 20px;">
        <h3>Edit Service</h3>
        <form method="POST" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Service Title</label>
                <input type="text" name="title" id="edit_title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Description</label>
                <textarea name="description" id="edit_description" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 100px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px;">FontAwesome Icon Class</label>
                <input type="text" name="icon" id="edit_icon" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_service" class="btn btn-primary">Update Service</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_title').value = data.title;
    document.getElementById('edit_description').value = data.description;
    document.getElementById('edit_icon').value = data.icon;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>

