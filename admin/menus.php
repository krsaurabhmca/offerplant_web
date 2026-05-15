<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM menus WHERE id = $id");
    header("Location: menus.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $order_no = (int)$_POST['order_no'];
    $status = (int)$_POST['status'];
    
    if (isset($_POST['add_menu'])) {
        mysqli_query($conn, "INSERT INTO menus (title, url, order_no, status) VALUES ('$title', '$url', '$order_no', '$status')");
    } elseif (isset($_POST['update_menu'])) {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "UPDATE menus SET title='$title', url='$url', order_no='$order_no', status='$status' WHERE id=$id");
    }
    header("Location: menus.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Header Menu</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add Menu Item</button>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Order</th>
                <th>Title</th>
                <th>URL</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $menus = mysqli_query($conn, "SELECT * FROM menus ORDER BY order_no ASC");
            while($m = mysqli_fetch_assoc($menus)) {
                $m_data = htmlspecialchars(json_encode($m));
                echo '
                <tr>
                    <td><span class="badge" style="background:#eee; color:#333;">'.$m['order_no'].'</span></td>
                    <td><strong>'.$m['title'].'</strong></td>
                    <td><small style="color:#888;">'.$m['url'].'</small></td>
                    <td>'.($m['status'] == 1 ? '<span style="color:var(--secondary); font-weight:700;">Active</span>' : '<span style="color:#dc3545;">Hidden</span>').'</td>
                    <td>
                        <button onclick=\'openEditModal('.$m_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; margin-right:5px;">Edit</button>
                        <a href="?delete='.$m['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 30px auto; padding: 30px; border-radius: 20px;">
        <h3>Add New Menu Item</h3>
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Menu Title</label>
                <input type="text" name="title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">URL / Link</label>
                <input type="text" name="url" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;" placeholder="e.g. index.php#services or about.php">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Order No.</label>
                    <input type="number" name="order_no" value="0" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Status</label>
                    <select name="status" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                        <option value="1">Active</option>
                        <option value="0">Hidden</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_menu" class="btn btn-primary">Save Item</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 30px auto; padding: 30px; border-radius: 20px;">
        <h3>Edit Menu Item</h3>
        <form method="POST" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Menu Title</label>
                <input type="text" name="title" id="edit_title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">URL / Link</label>
                <input type="text" name="url" id="edit_url" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Order No.</label>
                    <input type="number" name="order_no" id="edit_order_no" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Status</label>
                    <select name="status" id="edit_status" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                        <option value="1">Active</option>
                        <option value="0">Hidden</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_menu" class="btn btn-primary">Update Item</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_title').value = data.title;
    document.getElementById('edit_url').value = data.url;
    document.getElementById('edit_order_no').value = data.order_no;
    document.getElementById('edit_status').value = data.status;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>
