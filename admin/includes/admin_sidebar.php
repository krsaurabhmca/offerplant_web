<div class="sidebar">
    <a href="index.php" class="logo">Offer<span>Plant</span></a>
    <ul class="nav-menu">
        <div class="nav-group-label">Core</div>
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="sliders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'sliders.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i> Manage Sliders
            </a>
        </li>

        <div class="nav-group-label">Content</div>
        <li class="nav-item">
            <a href="services.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <i class="fas fa-laptop-code"></i> Services
            </a>
        </li>
        <li class="nav-item">
            <a href="products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a href="blogs.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blogs.php' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog Posts
            </a>
        </li>
        <li class="nav-item">
            <a href="pricing.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pricing.php' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Pricing Plans
            </a>
        </li>
        <li class="nav-item">
            <a href="testimonials.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'testimonials.php' ? 'active' : ''; ?>">
                <i class="fas fa-quote-left"></i> Testimonials
            </a>
        </li>
        <li class="nav-item">
            <a href="albums.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'albums.php' || basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : ''; ?>">
                <i class="fas fa-photo-video"></i> Media Gallery
            </a>
        </li>
        <li class="nav-item">
            <a href="faqs.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'faqs.php' ? 'active' : ''; ?>">
                <i class="fas fa-question-circle"></i> Manage FAQs
            </a>
        </li>


        <div class="nav-group-label">Appearance</div>
        <li class="nav-item">
            <a href="pages.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pages.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> Custom Pages
            </a>
        </li>
        <li class="nav-item">
            <a href="menus.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'menus.php' ? 'active' : ''; ?>">
                <i class="fas fa-bars"></i> Menu Manager
            </a>
        </li>

        <div class="nav-group-label">System</div>
        <li class="nav-item">
            <a href="enquiries.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'enquiries.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Enquiries
            </a>
        </li>
        <li class="nav-item">
            <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Site Settings
            </a>
        </li>
        <li class="nav-item">
            <a href="security.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'security.php' ? 'active' : ''; ?>">
                <i class="fas fa-shield-alt"></i> Security
            </a>
        </li>
        <li class="nav-item" style="margin-top: 30px;">
            <a href="logout.php" class="nav-link" style="color: #dc3545;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>

</div>

<div class="main-content" id="mainContent">
    <div class="top-header">
        <div class="header-left">
            <div class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </div>
            <h2 style="font-size: 20px; font-weight: 700; color: #333;">Admin Dashboard</h2>
        </div>
        
        <div class="header-right" style="display: flex; align-items: center; gap: 15px;">
            <a href="../index.php" target="_blank" class="header-icon" title="View Website">
                <i class="fas fa-globe"></i>
            </a>
            <div class="header-icon" title="Notifications">
                <i class="fas fa-bell"></i>
            </div>
            <div class="profile-dropdown" onclick="toggleProfileMenu()">
                <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?></div>
                    <i class="fas fa-chevron-down" style="font-size: 10px; color: #94a3b8;"></i>
                </div>
                <div class="profile-menu" id="profileMenu">
                    <div style="padding: 10px 15px; border-bottom: 1px solid #f1f5f9; margin-bottom: 5px;">
                        <p style="font-size: 13px; font-weight: 700; color: #1e293b;"><?php echo $_SESSION['admin_name']; ?></p>
                        <p style="font-size: 11px; color: #64748b;">Administrator</p>
                    </div>
                    <a href="profile.php" class="profile-item"><i class="fas fa-user-circle"></i> My Profile</a>
                    <a href="settings.php" class="profile-item"><i class="fas fa-cog"></i> Account Settings</a>
                    <div style="border-top: 1px solid #f1f5f9; margin-top: 5px; padding-top: 5px;">
                        <a href="logout.php" class="profile-item" style="color: #dc2626;"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleProfileMenu() {
        document.getElementById('profileMenu').classList.toggle('show');
    }
    
    // Close dropdown on click outside
    window.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.profile-dropdown');
        const menu = document.getElementById('profileMenu');
        if (!dropdown.contains(e.target)) {
            menu.classList.remove('show');
        }
    });

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (window.innerWidth > 992) {
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('full');
        } else {
            sidebar.classList.toggle('active');
        }
    }
    
    // Close sidebar on click outside (mobile)
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.querySelector('.sidebar-toggle');
        
        if (window.innerWidth <= 992 && 
            !sidebar.contains(event.target) && 
            !toggle.contains(event.target) && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });
    </script>

