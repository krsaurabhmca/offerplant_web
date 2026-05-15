<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM pages WHERE id = $id");
    header("Location: pages.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $status = (int)$_POST['status'];
    
    // Slug generation
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    if (isset($_POST['add_page'])) {
        mysqli_query($conn, "INSERT INTO pages (title, slug, content, status) VALUES ('$title', '$slug', '$content', '$status')");
    } elseif (isset($_POST['update_page'])) {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "UPDATE pages SET title='$title', slug='$slug', content='$content', status='$status' WHERE id=$id");
    }
    header("Location: pages.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Custom Pages</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Create New Page</button>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Title</th>
                <th>URL Slug</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pages = mysqli_query($conn, "SELECT * FROM pages ORDER BY id ASC");
            while($p = mysqli_fetch_assoc($pages)) {
                $p_data = htmlspecialchars(json_encode($p));
                echo '
                <tr>
                    <td><strong>'.$p['title'].'</strong></td>
                    <td><small style="color:#888;">page.php?slug='.$p['slug'].'</small></td>
                    <td>'.($p['status'] == 1 ? '<span style="color:var(--secondary); font-weight:700;">Visible</span>' : '<span style="color:#dc3545;">Hidden</span>').'</td>
                    <td>
                        <button onclick=\'openEditModal('.$p_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; margin-right:5px;">Edit</button>
                        <a href="?delete='.$p['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:800px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Create New Page</h3>
        <form method="POST" onsubmit="add_content.value = addEditor.getData()" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Page Title</label>
                <input type="text" name="title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Content</label>
                <textarea name="content" id="add_content" style="width:100%; height: 300px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Visibility</label>
                <select name="status" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                    <option value="1">Visible (Show in footer)</option>
                    <option value="0">Hidden</option>
                </select>
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_page" class="btn btn-primary">Save Page</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:800px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Edit Page</h3>
        <form method="POST" onsubmit="edit_content.value = editEditor.getData()" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Page Title</label>
                <input type="text" name="title" id="edit_title" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Content</label>
                <textarea name="content" id="edit_content" style="width:100%; height: 300px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Visibility</label>
                <select name="status" id="edit_status" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                    <option value="1">Visible (Show in footer)</option>
                    <option value="0">Hidden</option>
                </select>
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_page" class="btn btn-primary">Update Page</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
let addEditor, editEditor;

ClassicEditor
    .create(document.querySelector('#add_content'))
    .then(editor => { addEditor = editor; })
    .catch(error => { console.error(error); });

ClassicEditor
    .create(document.querySelector('#edit_content'))
    .then(editor => { editEditor = editor; })
    .catch(error => { console.error(error); });

function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_title').value = data.title;
    editEditor.setData(data.content);
    document.getElementById('edit_status').value = data.status;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>
