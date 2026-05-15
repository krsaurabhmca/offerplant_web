<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM menus WHERE id = $id");
    header("Location: menus.php");
    exit();
}

// Handle Toggle Status
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    mysqli_query($conn, "UPDATE menus SET status = NOT status WHERE id = $id");
    header("Location: menus.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_auto_add'])) {
        $val = isset($_POST['auto_add_pages']) ? '1' : '0';
        mysqli_query($conn, "UPDATE site_settings SET meta_value = '$val' WHERE meta_key = 'auto_add_pages'");
        header("Location: menus.php?success=1");
        exit();
    }

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $order_no = (int)$_POST['order_no'];
    $status = (int)$_POST['status'];
    $parent_id = (int)$_POST['parent_id'];
    
    if (isset($_POST['add_menu'])) {
        mysqli_query($conn, "INSERT INTO menus (title, url, order_no, status, parent_id) VALUES ('$title', '$url', '$order_no', '$status', '$parent_id')");
    } elseif (isset($_POST['update_menu'])) {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "UPDATE menus SET title='$title', url='$url', order_no='$order_no', status='$status', parent_id='$parent_id' WHERE id=$id");
    }
    header("Location: menus.php");
    exit();
}


include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<style>
/* Toggle Switch Styles */
.switch { position: relative; display: inline-block; width: 40px; height: 22px; margin: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #16a34a; }
input:checked + .slider:before { transform: translateX(18px); }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2>Manage Header Menu</h2>
    <div style="display: flex; gap: 15px; align-items: center;">
        <form method="POST" class="card" style="margin: 0; padding: 10px 20px; display: flex; align-items: center; gap: 10px; border: 1px solid var(--primary);">
            <label style="font-size: 13px; font-weight: 700;">Auto Add Pages?</label>
            <input type="checkbox" name="auto_add_pages" <?php echo ($site['auto_add_pages'] ?? '0') == '1' ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
            <button type="submit" name="update_auto_add" class="btn btn-sm btn-primary">Save</button>
        </form>
        <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add Menu Item</button>
    </div>
</div>

<div class="card">
    <table class="datatable">
        <thead>
            <tr>
                <th>Order</th>
                <th>Menu Item</th>
                <th>URL</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $menus = mysqli_query($conn, "SELECT * FROM menus WHERE parent_id = 0 ORDER BY order_no ASC");
            while($m = mysqli_fetch_assoc($menus)) {
                $m_data = htmlspecialchars(json_encode($m));
                echo '
                <tr>
                    <td><span class="badge" style="background:#eee; color:#333;">'.$m['order_no'].'</span></td>
                    <td><strong style="color:var(--primary);"><i class="fas fa-folder-open" style="margin-right:8px;"></i> '.$m['title'].'</strong></td>
                    <td><small style="color:#888;">'.$m['url'].'</small></td>
                    <td>
                        <label class="switch" title="Toggle Status">
                            <input type="checkbox" '.($m['status'] == 1 ? 'checked' : '').' onclick="window.location.href=\'?toggle_status='.$m['id'].'\'">
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td>
                        <button onclick=\'openEditModal('.$m_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Edit"><i class="fas fa-edit"></i></button>
                        <a href="?delete='.$m['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger" style="border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';

                // Fetch Submenus
                $subs = mysqli_query($conn, "SELECT * FROM menus WHERE parent_id = ".$m['id']." ORDER BY order_no ASC");
                while($s = mysqli_fetch_assoc($subs)) {
                    $s_data = htmlspecialchars(json_encode($s));
                    echo '
                    <tr style="background: #fafafa;">
                        <td><span class="badge" style="background:#eee; color:#333; margin-left: 20px;">'.$s['order_no'].'</span></td>
                        <td><span style="margin-left: 30px; color:#64748b;"><i class="fas fa-level-up-alt fa-rotate-90" style="margin-right:8px; opacity:0.5;"></i> '.$s['title'].'</span></td>
                        <td><small style="color:#888;">'.$s['url'].'</small></td>
                        <td>
                            <label class="switch" title="Toggle Status">
                                <input type="checkbox" '.($s['status'] == 1 ? 'checked' : '').' onclick="window.location.href=\'?toggle_status='.$s['id'].'\'">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button onclick=\'openEditModal('.$s_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Edit"><i class="fas fa-edit"></i></button>
                            <a href="?delete='.$s['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger" style="border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000;">
    <div style="background:#fff; width:500px; margin: 30px auto; padding: 30px; border-radius: 20px;">
        <h3>Add New Menu Item</h3>
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Menu Title</label>
                <input type="text" name="title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Parent Menu</label>
                <select name="parent_id" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                    <option value="0">None (Top Level)</option>
                    <?php
                    $parents = mysqli_query($conn, "SELECT id, title FROM menus WHERE parent_id = 0");
                    while($p = mysqli_fetch_assoc($parents)) echo "<option value='{$p['id']}'>{$p['title']}</option>";
                    ?>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">URL / Link</label>
                <input type="text" name="url" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;" placeholder="e.g. services.php or #contact">
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
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Parent Menu</label>
                <select name="parent_id" id="edit_parent_id" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                    <option value="0">None (Top Level)</option>
                    <?php
                    $parents = mysqli_query($conn, "SELECT id, title FROM menus WHERE parent_id = 0");
                    while($p = mysqli_fetch_assoc($parents)) echo "<option value='{$p['id']}'>{$p['title']}</option>";
                    ?>
                </select>
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
    document.getElementById('edit_parent_id').value = data.parent_id;
    document.getElementById('editModal').style.display = 'block';
}

</script>

<?php include 'includes/admin_footer.php'; ?>
