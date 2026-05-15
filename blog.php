<?php 
include 'includes/header.php'; 
$breadcrumb_current = "Our Blog";
include 'includes/breadcrumb.php';
?>

<section style="padding: 60px 0 80px; background: #f8f9fa;">
    <div class="container">
        <div class="section-title">
            <h1>Our Blog</h1>
            <div class="underline" style="margin: 0;"></div>
            <p style="margin-top: 15px; color: var(--gray);">Insights, news, and updates from the world of technology.</p>
        </div>
    </div>
</section>

<section style="padding: 80px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px;">
            <?php
            $blogs_query = mysqli_query($conn, "SELECT * FROM blogs WHERE status = 1 ORDER BY created_at DESC");
            if (mysqli_num_rows($blogs_query) > 0) {
                while($blog = mysqli_fetch_assoc($blogs_query)) {
                    echo '
                    <div class="blog-card" style="background: #fff; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow);">
                        <img src="uploads/'.$blog['image'].'" style="width: 100%; height: 230px; object-fit: cover;">
                        <div style="padding: 30px;">
                            <p style="color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 12px;">'.date('F d, Y', strtotime($blog['created_at'])).'</p>
                            <h3 style="margin-bottom: 18px; font-size: 1.4rem;">'.$blog['title'].'</h3>
                            <p style="color: #666; font-size: 15px; margin-bottom: 25px; line-height: 1.7;">'.substr(strip_tags($blog['content']), 0, 150).'...</p>
                            <a href="blog-details.php?slug='.$blog['slug'].'" class="btn-contact" style="padding: 10px 25px; font-size: 14px;">Read Full Article</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<div style="text-align: center; grid-column: 1/-1; padding: 100px 0;">
                        <i class="fas fa-blog" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                        <h2 style="color: #bbb;">No blog posts available yet.</h2>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
