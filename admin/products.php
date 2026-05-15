<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: products.php");
    exit();
}

// Handle Add
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    
    mysqli_query($conn, "INSERT INTO products (name, url) VALUES ('$name', '$url')");
    header("Location: products.php");
    exit();
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    
    mysqli_query($conn, "UPDATE products SET name='$name', url='$url' WHERE id=$id");
    header("Location: products.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Products</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add New Product</button>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
            while($prod = mysqli_fetch_assoc($products)) {
                $prod_data = htmlspecialchars(json_encode($prod));
                echo '
                <tr>
                    <td>'.$prod['id'].'</td>
                    <td><strong>'.$prod['name'].'</strong></td>
                    <td><a href="'.$prod['url'].'" target="_blank">'.$prod['url'].'</a></td>
                    <td>
                        <button onclick=\'openEditModal('.$prod_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; margin-right:5px;">Edit</button>
                        <a href="?delete='.$prod['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:400px; margin: 100px auto; padding: 30px; border-radius: 20px;">
        <h3>Add New Product</h3>
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Product Name</label>
                <input type="text" name="name" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px;">Product URL</label>
                <input type="url" name="url" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_product" class="btn btn-primary">Save Product</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:400px; margin: 100px auto; padding: 30px; border-radius: 20px;">
        <h3>Edit Product</h3>
        <form method="POST" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Product Name</label>
                <input type="text" name="name" id="edit_name" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px;">Product URL</label>
                <input type="url" name="url" id="edit_url" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_url').value = data.url;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>

