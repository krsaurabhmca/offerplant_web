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
        
        <div class="header-right">
            <a href="../index.php" target="_blank" class="header-icon" title="View Website">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <div class="user-info">
                <span style="font-size: 14px; color: #666; display: none; @media(min-width: 600px){display: block;}">Hi, <strong><?php echo $_SESSION['admin_name']; ?></strong></span>
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?></div>
            </div>
        </div>
    </div>

    <script>
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

