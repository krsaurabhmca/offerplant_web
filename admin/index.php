<?php 
include 'auth_check.php'; 
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

// Fetch Counts
$services_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM services"))['count'];
$products_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$enquiries_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM enquiries"))['count'];
$total_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(views) as count FROM visitor_stats"))['count'] ?? 0;
$active_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM active_users"))['count'];
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-eye"></i></div>
        <div>
            <h3><?php echo number_format($total_views); ?></h3>
            <p>Total Page Views</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon secondary"><i class="fas fa-users"></i></div>
        <div>
            <h3><?php echo $active_users; ?></h3>
            <p>Live Visitors</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="fas fa-envelope"></i></div>
        <div>
            <h3><?php echo $enquiries_count; ?></h3>
            <p>Total Enquiries</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line" style="margin-right: 8px; color: var(--primary);"></i> Top Performing Pages</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Page URL</th>
                    <th>Views</th>
                    <th>Last Visit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $top_pages = mysqli_query($conn, "SELECT * FROM visitor_stats ORDER BY views DESC LIMIT 6");
                while($p = mysqli_fetch_assoc($top_pages)) {
                    echo '
                    <tr>
                        <td><code style="background:#f8fafc; padding:4px 10px; border-radius:6px; font-size:11px; color:#475569; border: 1px solid #e2e8f0;">/'.$p['page_url'].'</code></td>
                        <td><strong>'.$p['views'].'</strong></td>
                        <td><span style="color:#64748b; font-size:11px;"><i class="far fa-clock"></i> '.date('d M, H:i', strtotime($p['last_updated'])).'</span></td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Enquiries</h3>
            <a href="enquiries.php" style="color: var(--primary); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">View All</a>
        </div>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php
            $recent_enq = mysqli_query($conn, "SELECT * FROM enquiries ORDER BY id DESC LIMIT 5");
            while($re = mysqli_fetch_assoc($recent_enq)) {
                echo '
                <div style="padding: 10px; border-radius: 10px; background: #fbfbfb; border: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px;">
                        <h4 style="font-size: 13px; font-weight: 700; color:#1e293b;">'.$re['name'].'</h4>
                        <small style="font-size: 10px; color: #94a3b8; font-weight: 600;">'.date('d M', strtotime($re['created_at'])).'</small>
                    </div>
                    <p style="font-size: 11px; color: #64748b; line-height: 1.5; margin-bottom: 8px;">'.substr($re['message'], 0, 85).'...</p>
                    <a href="enquiries.php" style="font-size: 10px; color: var(--primary); font-weight: 700; text-transform: uppercase;">View Message</a>
                </div>';
            }
            ?>
        </div>
    </div>
</div>



<?php include 'includes/admin_footer.php'; ?>
