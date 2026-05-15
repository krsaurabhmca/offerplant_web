<?php include_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site['meta_title']; ?></title>
    <meta name="description" content="<?php echo $site['meta_description']; ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Analytics -->
    <?php echo $site['google_analytics'] ?? ''; ?>

    <?php
    // Visitor Tracking
    session_start();
    $session_id = session_id();
    $page_url = basename($_SERVER['PHP_SELF']);
    
    // Update Page Views
    mysqli_query($conn, "INSERT INTO visitor_stats (page_url, views) VALUES ('$page_url', 1) ON DUPLICATE KEY UPDATE views = views + 1");
    
    // Update Active Users
    mysqli_query($conn, "INSERT INTO active_users (session_id, last_activity) VALUES ('$session_id', CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE last_activity = CURRENT_TIMESTAMP");
    // Clean up old sessions (inactive for 5 mins)
    mysqli_query($conn, "DELETE FROM active_users WHERE last_activity < (NOW() - INTERVAL 5 MINUTE)");
    ?>
    
    <!-- Custom CSS -->

    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Swiper Slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- SEO Favicon -->

    <link rel="icon" type="image/png" href="assets/images/favicon.png">
</head>
<body class="<?php echo ($site['site_theme'] ?? '') . ' ' . ($site['layout_view'] ?? 'layout-full') . ' ' . (($site['header_sticky'] ?? 'sticky') == 'static' ? 'header-static' : 'header-sticky'); ?>">

<div class="main-wrapper">

<div class="top-bar <?php echo $site['topbar_theme'] ?? 'topbar-dark'; ?>">
    <div class="container">
        <div class="top-bar-left">
            <div class="topbar-item">
                <i class="fas fa-envelope"></i> 
                <span><?php echo $site['email']; ?></span>
            </div>
            <div class="topbar-item desktop-only">
                <i class="fas fa-phone-alt"></i> 
                <span><?php echo $site['phone']; ?></span>
            </div>
        </div>
        <div class="top-bar-right">
            <div class="top-socials">
                <?php if($site['facebook_url']): ?><a href="<?php echo $site['facebook_url']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                <?php if($site['twitter_url']): ?><a href="<?php echo $site['twitter_url']; ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                <?php if($site['linkedin_url']): ?><a href="<?php echo $site['linkedin_url']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                <?php if($site['instagram_url']): ?><a href="<?php echo $site['instagram_url']; ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
            </div>
            <a href="admin/login.php" class="desktop-only" style="margin-left: 15px; border-left: 1px solid rgba(128,128,128,0.2); padding-left: 15px; font-weight: 600;">
                <i class="fas fa-lock" style="margin-right: 5px; font-size: 11px;"></i> Admin
            </a>
        </div>
    </div>
</div>

<header class="<?php echo $site['menu_layout'] ?? 'menu-inline'; ?>">
    <?php if(($site['menu_layout'] ?? '') == 'menu-separate'): ?>
        <!-- Separate Layout: Logo on Left, Action on Right -->
        <div class="logo-area">
            <a href="index.php" class="logo">
                <?php if (isset($site['logo']) && $site['logo']): ?>
                    <img src="uploads/<?php echo $site['logo']; ?>" alt="<?php echo $site['site_name']; ?>" style="height: 60px;">
                <?php else: ?>
                    <img src="assets/images/logo.png" alt="OfferPlant" style="height: 60px;">
                <?php endif; ?>
            </a>
            <div style="display: flex; align-items: center; gap: 25px;">
                <div class="desktop-only" style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 45px; height: 45px; background: #fff0f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <small style="display: block; color: #888; font-size: 11px; text-transform: uppercase; font-weight: 700;">Call Us Anytime</small>
                        <span style="font-weight: 700; color: var(--dark);"><?php echo $site['phone']; ?></span>
                    </div>
                </div>
                <a href="#contact" class="btn btn-primary desktop-only" style="padding: 10px 25px; border-radius: 50px;">Request Quote</a>
                <div class="mobile-menu-btn" onclick="toggleMobileMenu()" style="color: var(--dark);">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>

        <div class="nav-area">
            <div class="container">
                <nav style="justify-content: center;">
                    <ul class="nav-links" id="navLinks" style="gap: 40px;">
                        <?php
                        $header_menus = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 ORDER BY order_no ASC");
                        while($hm = mysqli_fetch_assoc($header_menus)) {
                            echo '<li><a href="'.$hm['url'].'" style="font-weight: 600; text-transform: uppercase; font-size: 14px;">'.$hm['title'].'</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    <?php else: ?>
        <!-- Inline Layout: Logo and Menu in same row -->
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <?php if (isset($site['logo']) && $site['logo']): ?>
                        <img src="uploads/<?php echo $site['logo']; ?>" alt="<?php echo $site['site_name']; ?>" style="height: 50px;">
                    <?php else: ?>
                        <img src="assets/images/logo.png" alt="OfferPlant" style="height: 50px;">
                    <?php endif; ?>
                </a>
                <ul class="nav-links" id="navLinks">
                    <?php
                    $header_menus = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 ORDER BY order_no ASC");
                    while($hm = mysqli_fetch_assoc($header_menus)) {
                        echo '<li><a href="'.$hm['url'].'">'.$hm['title'].'</a></li>';
                    }
                    ?>
                </ul>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <a href="#contact" class="btn-contact desktop-only">Get In Touch</a>
                    <div class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </nav>
        </div>
    <?php endif; ?>
</header>


<script>
function toggleMobileMenu() {
    const nav = document.getElementById('navLinks');
    nav.classList.toggle('active');
    const icon = document.querySelector('.mobile-menu-btn i');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
}
</script>

