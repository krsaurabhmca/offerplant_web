<?php 
include 'auth_check.php'; 

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM enquiries WHERE id = $id");
    header("Location: enquiries.php");
    exit();
}

include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<div style="margin-bottom: 30px;">
    <h2>Customer Enquiries</h2>
</div>

<div class="card">
    <table class="datatable">

        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $enquiries = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY created_at DESC");
            if (mysqli_num_rows($enquiries) > 0) {
                while($enq = mysqli_fetch_assoc($enquiries)) {
                    echo '
                    <tr>
                        <td style="white-space: nowrap; font-size: 13px;">'.date('d M Y, h:i A', strtotime($enq['created_at'])).'</td>
                        <td><strong>'.$enq['name'].'</strong></td>
                        <td><a href="mailto:'.$enq['email'].'">'.$enq['email'].'</a></td>
                        <td>'.$enq['subject'].'</td>
                        <td style="font-size: 13px; color: #666;">'.$enq['message'].'</td>
                        <td>
                            <a href="?delete='.$enq['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #888;">No enquiries found yet.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin_footer.php'; ?>

