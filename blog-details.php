<?php 
include 'includes/header.php'; 

$slug = mysqli_real_escape_string($conn, $_GET['slug']);
$blog_query = mysqli_query($conn, "SELECT * FROM blogs WHERE slug = '$slug' AND status = 1");
$blog = mysqli_fetch_assoc($blog_query);

if (!$blog) {
    header("Location: blog.php");
    exit();
}

$breadcrumb_parent = "Blog";
$breadcrumb_parent_url = "blog.php";
$breadcrumb_current = $blog['title'];
include 'includes/breadcrumb.php';
?>

<section style="padding: 60px 0 100px; background: #fff;">
    <div class="container">
        <div class="blog-details-grid">

            
            <!-- Main Content -->
            <div class="blog-main">
                <a href="blog.php" style="color: var(--primary); font-weight: 600; display: inline-block; margin-bottom: 20px;"><i class="fas fa-arrow-left"></i> Back to Blog</a>
                <h1 style="font-size: 2.8rem; line-height: 1.2; margin-bottom: 20px; color: var(--dark);"><?php echo $blog['title']; ?></h1>
                
                <div style="display: flex; align-items: center; gap: 20px; color: #888; margin-bottom: 30px; font-size: 14px;">
                    <span><i class="fas fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                    <span><i class="fas fa-user"></i> By <?php echo $blog['author']; ?></span>
                </div>
                
                <img src="uploads/<?php echo $blog['image']; ?>" style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 20px; box-shadow: var(--shadow); margin-bottom: 40px;">
                
                <div class="blog-content" style="line-height: 1.8; font-size: 1.1rem; color: #444;">
                    <?php echo $blog['content']; // Already formatted by CKEditor ?>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="blog-sidebar">
                <div style="position: sticky; top: 130px;">
                    <div style="background: #fdfdfd; padding: 30px; border-radius: 25px; border: 1px solid #eee;">
                        <h3 style="margin-bottom: 25px; font-size: 1.4rem; color: var(--dark); border-left: 5px solid var(--primary); padding-left: 15px;">Related Posts</h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 25px;">
                            <?php
                            $related_query = mysqli_query($conn, "SELECT * FROM blogs WHERE id != {$blog['id']} AND status = 1 ORDER BY id DESC LIMIT 5");
                            while($rel = mysqli_fetch_assoc($related_query)) {
                                echo '
                                <a href="blog-details.php?slug='.$rel['slug'].'" style="display: flex; gap: 15px; text-decoration: none; group;">
                                    <img src="uploads/'.$rel['image'].'" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; flex-shrink: 0;">
                                    <div>
                                        <h4 style="font-size: 14px; line-height: 1.4; color: #333; transition: 0.3s; margin-bottom: 5px;" class="rel-title">'.$rel['title'].'</h4>
                                        <small style="color: #999;"><i class="far fa-calendar-alt"></i> '.date('M d, Y', strtotime($rel['created_at'])).'</small>
                                    </div>
                                </a>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Category/Newsletter Placeholder or Ad -->
                    <div style="margin-top: 30px; background: linear-gradient(135deg, var(--primary), #e01283); padding: 30px; border-radius: 25px; color: #fff; text-align: center;">
                        <h4 style="margin-bottom: 10px;">Need a Project?</h4>
                        <p style="font-size: 14px; margin-bottom: 20px; opacity: 0.9;">We build professional websites and apps for your business.</p>
                        <a href="index.php#contact" class="btn-contact" style="background: #fff; color: var(--primary); display: block; border-radius: 10px;">Contact Us</a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

<style>
.blog-details-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 40px;
}
@media(min-width: 992px) {
    .blog-details-grid {
        grid-template-columns: 2fr 1fr;
    }
}
.blog-content h2, .blog-content h3 { margin: 30px 0 15px; color: var(--dark); }
.blog-content p { margin-bottom: 20px; }
.blog-content ul, .blog-content ol { margin-bottom: 20px; padding-left: 20px; }
.blog-content img { max-width: 100%; border-radius: 10px; }
.rel-title:hover { color: var(--primary) !important; }
</style>


<?php include 'includes/footer.php'; ?>

