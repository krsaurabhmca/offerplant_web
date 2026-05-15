<?php 
include 'includes/header.php'; 

if (!isset($_GET['slug'])) {
    header("Location: index.php");
    exit();
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);
$res = mysqli_query($conn, "SELECT * FROM pages WHERE slug = '$slug' AND status = 1");
$page = mysqli_fetch_assoc($res);

if (!$page) {
    header("Location: index.php");
    exit();
}

$breadcrumb_current = $page['title'];
include 'includes/breadcrumb.php';
?>

<section style="padding: 60px 0 100px; background: #fff;">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 style="font-size: 3rem; margin-bottom: 40px; color: var(--dark);"><?php echo $page['title']; ?></h1>
            
            <div class="page-content" style="line-height: 1.8; color: #444; font-size: 1.1rem;">
                <?php echo $page['content']; ?>
            </div>
        </div>
    </div>
</section>

<style>
.page-content h2 { margin: 30px 0 15px; color: var(--dark); }
.page-content p { margin-bottom: 20px; }
.page-content ul { margin-bottom: 20px; padding-left: 20px; list-style: disc; }
.page-content li { margin-bottom: 10px; }
</style>

<?php include 'includes/footer.php'; ?>
