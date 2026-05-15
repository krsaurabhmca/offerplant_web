<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = mysqli_query($conn, "SELECT image FROM testimonials WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row && $row['image'] && file_exists("../uploads/".$row['image'])) {
        unlink("../uploads/".$row['image']);
    }
    mysqli_query($conn, "DELETE FROM testimonials WHERE id = $id");
    header("Location: testimonials.php");
    exit();
}

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $rating = (int)$_POST['rating'];
    
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = 'testi_'.time().'.'.$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
    }

    if (isset($_POST['add_testi'])) {
        mysqli_query($conn, "INSERT INTO testimonials (name, designation, company, feedback, image, rating) VALUES ('$name', '$designation', '$company', '$feedback', '$image', '$rating')");
    } elseif (isset($_POST['update_testi'])) {
        $id = (int)$_POST['id'];
        $update_img = $image ? ", image='$image'" : "";
        if ($image) {
            $res = mysqli_query($conn, "SELECT image FROM testimonials WHERE id = $id");
            $row = mysqli_fetch_assoc($res);
            if ($row && $row['image'] && file_exists("../uploads/".$row['image'])) {
                unlink("../uploads/".$row['image']);
            }
        }
        mysqli_query($conn, "UPDATE testimonials SET name='$name', designation='$designation', company='$company', feedback='$feedback', rating='$rating' $update_img WHERE id=$id");
    }
    header("Location: testimonials.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2>Client Testimonials</h2>
    <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">Add Testimonial</button>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Client</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $testis = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC");
            while($t = mysqli_fetch_assoc($testis)) {
                $t_data = htmlspecialchars(json_encode($t));
                echo '
                <tr>
                    <td style="display:flex; align-items:center; gap:10px;">
                        <img src="../uploads/'.$t['image'].'" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                        <div>
                            <strong>'.$t['name'].'</strong><br>
                            <small style="color:#888;">'.$t['designation'].' at '.$t['company'].'</small>
                        </div>
                    </td>
                    <td style="max-width: 300px; font-size: 13px;">'.substr($t['feedback'], 0, 100).'...</td>
                    <td style="color:#ffc107;">';
                        for($i=1; $i<=5; $i++) {
                            echo $i <= $t['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                    echo '</td>
                    <td>
                        <button onclick=\'openEditModal('.$t_data.')\' class="btn btn-sm" style="background:#f0f7ff; color:#007bff; margin-right:5px;">Edit</button>
                        <a href="?delete='.$t['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger">Delete</a>
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
        <h3>Add New Testimonial</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Client Name</label>
                <input type="text" name="name" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Designation</label>
                    <input type="text" name="designation" placeholder="CEO / Founder" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Company</label>
                    <input type="text" name="company" placeholder="Example Inc." style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Rating (1-5)</label>
                <select name="rating" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Feedback</label>
                <textarea name="feedback" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 100px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Client Photo</label>
                <input type="file" name="image" required style="width:100%;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="add_testi" class="btn btn-primary">Save Testimonial</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:#fff; width:500px; margin: 30px auto; padding: 30px; border-radius: 20px; max-height: 90vh; overflow-y: auto;">
        <h3>Edit Testimonial</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Client Name</label>
                <input type="text" name="name" id="edit_name" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Designation</label>
                    <input type="text" name="designation" id="edit_designation" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
                <div>
                    <label style="display:block; margin-bottom: 5px; font-weight:600;">Company</label>
                    <input type="text" name="company" id="edit_company" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Rating (1-5)</label>
                <select name="rating" id="edit_rating" style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px;">
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Feedback</label>
                <textarea name="feedback" id="edit_feedback" required style="width:100%; padding: 10px; border:1px solid #ddd; border-radius: 8px; height: 100px;"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight:600;">Client Photo (Leave blank to keep current)</label>
                <input type="file" name="image" style="width:100%;">
            </div>
            <div style="display:flex; gap: 10px;">
                <button type="submit" name="update_testi" class="btn btn-primary">Update Testimonial</button>
                <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn" style="background:#eee;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_designation').value = data.designation;
    document.getElementById('edit_company').value = data.company;
    document.getElementById('edit_rating').value = data.rating;
    document.getElementById('edit_feedback').value = data.feedback;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'includes/admin_footer.php'; ?>
