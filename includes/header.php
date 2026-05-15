<?php include_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="/offerplant/">
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

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />


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
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#FF1493">
</head>

<body
    class="<?php echo ($site['site_theme'] ?? '') . ' ' . ($site['layout_view'] ?? 'layout-full') . ' ' . (($site['header_sticky'] ?? 'sticky') == 'static' ? 'header-static' : 'header-sticky') . ' ' . (basename($_SERVER['PHP_SELF'], '.php') == 'index' ? 'home-page' : ''); ?>">


    <div class="main-wrapper">

        <div class="top-bar <?php echo $site['topbar_theme'] ?? 'topbar-dark'; ?>">
            <div class="container">
                <div class="top-bar-left">
                    <div class="topbar-item">
                        <span class="top-icon"><i class="fas fa-phone-alt"></i></span>
                        <span class="top-text"><?php echo $site['phone']; ?></span>
                    </div>
                    <div class="topbar-item desktop-only">
                        <span class="top-icon"><i class="fas fa-envelope"></i></span>
                        <span class="top-text"><?php echo $site['email']; ?></span>
                    </div>
                </div>
                <div class="top-bar-right">
                    <div class="top-socials">
                        <?php if ($site['facebook_url']): ?><a href="<?php echo $site['facebook_url']; ?>"
                                target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                        <?php if ($site['twitter_url']): ?><a href="<?php echo $site['twitter_url']; ?>" target="_blank"
                                title="Twitter"><i class="fab fa-twitter"></i></a><?php endif; ?>
                        <?php if ($site['linkedin_url']): ?><a href="<?php echo $site['linkedin_url']; ?>"
                                target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                        <?php if ($site['instagram_url']): ?><a href="<?php echo $site['instagram_url']; ?>"
                                target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    </div>
                    <div class="topbar-divider"></div>
                    <a href="admin/login.php" class="topbar-login-btn">
                        <i class="fas fa-user-shield"></i> Admin Portal
                    </a>
                </div>


            </div>
        </div>

        <header class="<?php echo $site['menu_layout'] ?? 'menu-inline'; ?>">
            <?php if (($site['menu_layout'] ?? '') == 'menu-separate'): ?>
                <!-- Separate Layout: Logo on Left, Action on Right -->
                <div class="logo-area">
                    <a href="index.php" class="logo">
                        <?php if (isset($site['logo']) && $site['logo']): ?>
                            <img src="uploads/<?php echo $site['logo']; ?>" alt="<?php echo $site['site_name']; ?>"
                                style="height: 65px;">
                        <?php else: ?>
                            <img src="assets/images/logo.png" alt="OfferPlant" style="height: 65px;">
                        <?php endif; ?>
                    </a>
                    <div style="display: flex; align-items: center; gap: 30px;">
                        <a href="#contact" class="btn btn-primary desktop-only"
                            style="padding: 12px 30px; border-radius: 50px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 13px;">Request
                            Quote</a>
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
                                $manual_menus = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 AND parent_id = 0 ORDER BY order_no ASC");
                                while ($m = mysqli_fetch_assoc($manual_menus)):
                                    $subs = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 AND parent_id = " . $m['id'] . " ORDER BY order_no ASC");
                                    $has_subs = mysqli_num_rows($subs) > 0;
                                    ?>
                                    <li class="<?php echo $has_subs ? 'has-dropdown' : ''; ?>">
                                        <a href="<?php echo $m['url']; ?>"
                                            style="font-weight: 600; text-transform: uppercase; font-size: 14px;">
                                            <?php echo $m['title']; ?>
                                            <?php if ($has_subs)
                                                echo '<i class="fas fa-chevron-down" style="font-size:10px; margin-left:5px;"></i>'; ?>
                                        </a>
                                        <?php if ($has_subs): ?>
                                            <ul class="dropdown">
                                                <?php while ($s = mysqli_fetch_assoc($subs)): ?>
                                                    <li><a href="<?php echo $s['url']; ?>"><?php echo $s['title']; ?></a></li>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endwhile; ?>
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
                                <img src="uploads/<?php echo $site['logo']; ?>" alt="<?php echo $site['site_name']; ?>"
                                    style="height: 50px;">
                            <?php else: ?>
                                <img src="assets/images/logo.png" alt="OfferPlant" style="height: 50px;">
                            <?php endif; ?>
                        </a>
                        <ul class="nav-menu">
                            <?php
                            // 1. Fetch Manual Menus (Top Level)
                            $manual_menus = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 AND parent_id = 0 ORDER BY order_no ASC");
                            while ($m = mysqli_fetch_assoc($manual_menus)):
                                // Check for submenus
                                $subs = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 AND parent_id = " . $m['id'] . " ORDER BY order_no ASC");
                                $has_subs = mysqli_num_rows($subs) > 0;
                                ?>
                                <li class="<?php echo $has_subs ? 'has-dropdown' : ''; ?>">
                                    <a href="<?php echo $m['url']; ?>"><?php echo $m['title']; ?>
                                        <?php if ($has_subs)
                                            echo '<i class="fas fa-chevron-down" style="font-size:10px; margin-left:5px;"></i>'; ?></a>
                                    <?php if ($has_subs): ?>
                                        <ul class="dropdown">
                                            <?php while ($s = mysqli_fetch_assoc($subs)): ?>
                                                <li><a href="<?php echo $s['url']; ?>"><?php echo $s['title']; ?></a></li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; ?>

                            <?php
                            // 2. Auto-Add Pages (if enabled)
                            if (($site['auto_add_pages'] ?? '0') == '1'):
                                $auto_pages = mysqli_query($conn, "SELECT title, slug FROM pages WHERE status = 1");
                                while ($ap = mysqli_fetch_assoc($auto_pages)):
                                    ?>
                                    <li><a href="page.php?slug=<?php echo $ap['slug']; ?>"><?php echo $ap['title']; ?></a></li>
                                <?php
                                endwhile;
                            endif;
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
        <div class="main-wrapper">



            <!-- Mobile Sidebar -->
            <div class="mobile-menu-overlay" onclick="toggleMobileMenu()"></div>
            <div class="mobile-sidebar">
                <div class="mobile-sidebar-header">
                    <a href="index.php" class="logo">
                        <?php if (isset($site['logo']) && $site['logo']): ?>
                            <img src="uploads/<?php echo $site['logo']; ?>" alt="<?php echo $site['site_name']; ?>"
                                style="height: 45px;">
                        <?php else: ?>
                            <img src="assets/images/logo.png" alt="OfferPlant" style="height: 45px;">
                        <?php endif; ?>
                    </a>
                    <div class="close-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-times"></i>
                    </div>
                </div>

                <div class="mobile-sidebar-content">
                    <ul class="mobile-nav-links">
                        <?php
                        $mobile_menus = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 ORDER BY order_no ASC");
                        while ($mm = mysqli_fetch_assoc($mobile_menus)) {
                            echo '<li><a href="' . $mm['url'] . '">' . $mm['title'] . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="mobile-sidebar-footer">
                    <p>Get in Touch</p>
                    <div class="mobile-contact-grid">
                        <a href="tel:<?php echo $site['phone']; ?>" class="m-contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>Call</span>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $site['whatsapp_number']); ?>"
                            target="_blank" class="m-contact-item" style="background: #25d366;">

                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="mailto:<?php echo $site['email']; ?>" class="m-contact-item"
                            style="background: var(--primary);">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </a>
                    </div>
                </div>
            </div>

            <script>
                function toggleMobileMenu() {
                    document.querySelector('.mobile-sidebar').classList.toggle('active');
                    document.querySelector('.mobile-menu-overlay').classList.toggle('active');
                    document.body.style.overflow = document.querySelector('.mobile-sidebar').classList.contains('active') ? 'hidden' : '';
                }

                // Sticky Header Logic
                window.addEventListener('scroll', function () {
                    const header = document.querySelector('header');
                    if (window.scrollY > 40) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                });
            </script>