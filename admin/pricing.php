<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM pricing_plans WHERE id = $id");
    header("Location: pricing.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $offer_price = mysqli_real_escape_string($conn, $_POST['offer_price']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $badge = mysqli_real_escape_string($conn, $_POST['badge']);
    $offer_ends = mysqli_real_escape_string($conn, $_POST['offer_ends']);
    
    if (isset($_POST['add_plan'])) {
        mysqli_query($conn, "INSERT INTO pricing_plans (name, price, offer_price, features, badge, offer_ends) VALUES ('$name', '$price', '$offer_price', '$features', '$badge', '$offer_ends')");
    } elseif (isset($_POST['update_plan'])) {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "UPDATE pricing_plans SET name='$name', price='$price', offer_price='$offer_price', features='$features', badge='$badge', offer_ends='$offer_ends' WHERE id=$id");
    }
    header("Location: pricing.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Manage Pricing Plans</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add New Plan</button>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Plan Name</th>
                <th>Price</th>
                <th>Offer Price</th>
                <th>Features</th>
                <th>Offer Ends</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $plans = mysqli_query($conn, "SELECT * FROM pricing_plans ORDER BY id ASC");
            while($p = mysqli_fetch_assoc($plans)) {
                $p_data = htmlspecialchars(json_encode($p));
                echo '
                <tr>
                    <td><strong>'.$p['name'].'</strong><br><small class="badge" style="background:#fff0f7; color:var(--primary);">'.$p['badge'].'</small></td>
                    <td><del>₹'.$p['price'].'</del></td>
                    <td style="color:var(--secondary); font-weight:700;">₹'.$p['offer_price'].'</td>
                    <td style="max-width: 200px; font-size: 13px;">'.nl2br($p['features']).'</td>
                    <td>'.($p['offer_ends'] ? date('d M Y, h:i A', strtotime($p['offer_ends'])) : 'N/A').'</td>
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
    <div style="background:#fff; width:500px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Add New Pricing Plan</h3>
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Plan Name</label>
                <input type="text" name="name" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Regular Price</label>
                    <input type="number" name="price" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Offer Price (Optional)</label>
                    <input type="number" name="offer_price" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>

            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Features (One per line)</label>
                <textarea name="features" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 100px;" placeholder="Free Domain
Hosting Included
24/7 Support"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Badge (Optional)</label>
                    <input type="text" name="badge" placeholder="Most Popular" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Offer Ends (Optional)</label>
                    <input type="datetime-local" name="offer_ends" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_plan" class="btn btn-primary">Save Plan</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Edit Pricing Plan</h3>
        <form method="POST" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Plan Name</label>
                <input type="text" name="name" id="edit_name" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Regular Price</label>
                    <input type="number" name="price" id="edit_price" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Offer Price (Optional)</label>
                    <input type="number" name="offer_price" id="edit_offer_price" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>

            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Features (One per line)</label>
                <textarea name="features" id="edit_features" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 100px;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Badge (Optional)</label>
                    <input type="text" name="badge" id="edit_badge" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Offer Ends (Optional)</label>
                    <input type="datetime-local" name="offer_ends" id="edit_offer_ends" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_plan" class="btn btn-primary">Update Plan</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_price').value = data.price;
    document.getElementById('edit_offer_price').value = data.offer_price;
    document.getElementById('edit_features').value = data.features;
    document.getElementById('edit_badge').value = data.badge;
    if(data.offer_ends) {
        // Format for datetime-local: YYYY-MM-DDThh:mm
        let date = new Date(data.offer_ends);
        let formattedDate = date.getFullYear() + '-' + 
            String(date.getMonth() + 1).padStart(2, '0') + '-' + 
            String(date.getDate()).padStart(2, '0') + 'T' + 
            String(date.getHours()).padStart(2, '0') + ':' + 
            String(date.getMinutes()).padStart(2, '0');
        document.getElementById('edit_offer_ends').value = formattedDate;
    }
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>
