<?php 
include 'auth_check.php'; 

// Helper function for Slug
function createSlug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = mysqli_query($conn, "SELECT image FROM blogs WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row && $row['image'] && file_exists("../uploads/".$row['image'])) {
        unlink("../uploads/".$row['image']);
    }
    mysqli_query($conn, "DELETE FROM blogs WHERE id = $id");
    header("Location: blogs.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $base_slug = createSlug($title);
    
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = 'blog_'.time().'.'.$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
    }

    if (isset($_POST['add_blog'])) {
        // Handle Duplicate Slug
        $slug = $base_slug;
        $check = mysqli_query($conn, "SELECT id FROM blogs WHERE slug = '$slug'");
        if (mysqli_num_rows($check) > 0) {
            $slug = $base_slug . '-' . time();
        }
        
        if (mysqli_query($conn, "INSERT INTO blogs (title, slug, content, image) VALUES ('$title', '$slug', '$content', '$image')")) {
            header("Location: blogs.php?msg=success");
        } else {
            header("Location: blogs.php?msg=error&err=".urlencode(mysqli_error($conn)));
        }
    } elseif (isset($_POST['update_blog'])) {
        $id = (int)$_POST['id'];
        $update_img = $image ? ", image='$image'" : "";
        if ($image) {
            $res = mysqli_query($conn, "SELECT image FROM blogs WHERE id = $id");
            $row = mysqli_fetch_assoc($res);
            if ($row && $row['image'] && file_exists("../uploads/".$row['image'])) {
                unlink("../uploads/".$row['image']);
            }
        }
        
        // Handle Duplicate Slug for Update
        $slug = $base_slug;
        $check = mysqli_query($conn, "SELECT id FROM blogs WHERE slug = '$slug' AND id != $id");
        if (mysqli_num_rows($check) > 0) {
            $slug = $base_slug . '-' . time();
        }

        if (mysqli_query($conn, "UPDATE blogs SET title='$title', slug='$slug', content='$content' $update_img WHERE id=$id")) {
            header("Location: blogs.php?msg=updated");
        } else {
            header("Location: blogs.php?msg=error&err=".urlencode(mysqli_error($conn)));
        }
    }
    exit();
}


include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Blog Management</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Create New Post</button>
</div>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] == 'success'): ?>
        <div style="background: #f0fff4; color: #28a745; padding: 15px; border-radius: 10px; margin-bottom: 20px;">Post published successfully!</div>
    <?php elseif ($_GET['msg'] == 'updated'): ?>
        <div style="background: #f0fff4; color: #28a745; padding: 15px; border-radius: 10px; margin-bottom: 20px;">Post updated successfully!</div>
    <?php elseif ($_GET['msg'] == 'error'): ?>
        <div style="background: #fff5f5; color: #dc3545; padding: 15px; border-radius: 10px; margin-bottom: 20px;">Error: <?php echo htmlspecialchars($_GET['err']); ?></div>
    <?php endif; ?>
<?php endif; ?>


<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $blogs = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
            while($b = mysqli_fetch_assoc($blogs)) {
                $b_data = htmlspecialchars(json_encode([
                    'id' => $b['id'],
                    'title' => $b['title'],
                    'content' => $b['content']
                ]));
                echo '
                <tr>
                    <td><img src="../uploads/'.$b['image'].'" style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px;"></td>
                    <td><strong>'.$b['title'].'</strong><br><small style="color:#888;">/'.$b['slug'].'</small></td>
                    <td>'.date('d M Y', strtotime($b['created_at'])).'</td>
                    <td>
                        <button onclick=\'openEditModal('.$b_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Edit"><i class="fas fa-edit"></i></button>
                        <a href="?delete='.$b['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger" style="border-radius:8px; width:35px; height:35px; padding:0; display:inline-flex; align-items:center; justify-content:center;" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:700px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Create New Blog Post</h3>
        <form method="POST" enctype="multipart/form-data" onsubmit="add_content.value = addEditor.getData()" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: 600;">Post Title</label>
                <input type="text" name="title" required style="width:100%; padding: 12px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: 600;">Content</label>
                <textarea name="content" id="add_content" style="width:100%; padding: 12px; border:1px solid #ddd; border-radius: 8px; height: 250px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight: 600;">Featured Image</label>
                <input type="file" name="image" required style="width:100%;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_blog" class="btn btn-primary">Publish Post</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:700px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Edit Blog Post</h3>
        <form method="POST" enctype="multipart/form-data" onsubmit="edit_content.value = editEditor.getData()" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: 600;">Post Title</label>
                <input type="text" name="title" id="edit_title" required style="width:100%; padding: 12px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: 600;">Content</label>
                <textarea name="content" id="edit_content" style="width:100%; padding: 12px; border:1px solid #ddd; border-radius: 8px; height: 250px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight: 600;">Featured Image (Leave blank to keep current)</label>
                <input type="file" name="image" style="width:100%;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_blog" class="btn btn-primary">Update Post</button>
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
    document.getElementById('editModal').style.display = 'block';
}
</script>


<?php include 'includes/admin_footer.php'; ?>
