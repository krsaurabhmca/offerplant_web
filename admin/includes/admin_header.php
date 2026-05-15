<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OfferPlant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    
    <style>
        /* DataTables Custom Styling - Fix Vertical/Bulleted Pagination */
        .dataTables_paginate {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-end !important;
            list-style-type: none !important;
            margin: 25px 0 0 0 !important;
            padding: 0 !important;
            gap: 8px !important;
        }
        .dataTables_paginate li {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .dataTables_paginate .paginate_button {
            padding: 8px 20px !important;
            border: 1px solid #f1f5f9 !important;
            border-radius: 50px !important;
            background: #fff !important;
            color: #64748b !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-decoration: none !important; /* Remove underline */
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-block !important;
            line-height: 1 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #fff0f7 !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            color: #fff !important;
            border-color: var(--primary) !important;
            box-shadow: 0 8px 20px rgba(255, 20, 147, 0.2) !important;
        }
        .dataTables_paginate .previous, .dataTables_paginate .next {
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            background: #f8fafc !important;
        }


        
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 8px 15px !important;
            outline: none !important;
            font-size: 13px !important;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
            outline: none !important;
            font-size: 13px !important;
        }
        .dataTables_info { font-size: 13px; color: #94a3b8; margin-top: 20px; font-weight: 500; }

        table.dataTable thead th { border-bottom: 2px solid #f1f5f9 !important; }
        table.dataTable.no-footer { border-bottom: 1px solid #f1f5f9 !important; }

        :root {
            --primary: #FF1493;
            --secondary: #059669;
            --dark: #0f172a;
            --gray: #64748b;
            --sidebar-width: 240px;
            --bg: #f8fafc;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: #1e293b; font-size: 14px; }
        
        /* Compact Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #fff;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 15px;
            box-shadow: 1px 0 0 0 #e2e8f0;
            z-index: 1000;
            transition: 0.3s;
            overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        .logo { font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 25px; display: block; text-decoration: none; padding-left: 10px; letter-spacing: -0.5px; }
        .logo span { color: var(--dark); }
        
        .nav-group-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            margin: 15px 0 8px 12px;
            letter-spacing: 0.5px;
        }

        .nav-menu { list-style: none; }
        .nav-item { margin-bottom: 2px; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.2s;
            font-weight: 500;
            font-size: 13px;
        }
        .nav-link i { margin-right: 10px; width: 18px; text-align: center; font-size: 14px; opacity: 0.7; }
        .nav-link:hover, .nav-link.active {
            background: #f1f5f9;
            color: var(--primary);
        }
        .nav-link.active { background: #fff0f7; color: var(--primary); font-weight: 600; }
        .nav-link.active i { opacity: 1; }

        /* Compact Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 20px;
            transition: 0.3s;
        }
        
        .top-header {
            background: #fff;
            padding: 10px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: -20px -20px 20px -20px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        
        .header-left { display: flex; align-items: center; gap: 15px; }
        .sidebar-toggle { font-size: 18px; cursor: pointer; color: #64748b; }
        
        .user-info .avatar {
            width: 30px; height: 30px;
            background: var(--primary); color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px;
        }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card {
            background: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #f1f5f9;
        }
        .stat-icon {
            width: 45px; height: 45px;
            border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .stat-icon.primary { background: #fff0f7; color: var(--primary); }
        .stat-icon.secondary { background: #ecfdf5; color: var(--secondary); }
        .stat-icon.info { background: #eff6ff; color: #3b82f6; }
        
        .stat-card h3 { font-size: 20px; font-weight: 700; color: var(--dark); margin-bottom: 2px; }
        .stat-card p { font-size: 12px; color: var(--gray); font-weight: 500; }

        /* Compact Tables & Cards */
        .card { 
            background: #fff; 
            padding: 20px; 
            border-radius: 12px; 
            box-shadow: var(--card-shadow); 
            border: 1px solid #f1f5f9;
            margin-bottom: 20px;
        }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .card-title { font-size: 16px; font-weight: 700; color: var(--dark); }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 12px; color: #64748b; border-bottom: 1px solid #f1f5f9; font-weight: 600; background: #f8fafc; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        
        .btn { padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; transition: 0.2s; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fecaca; }
        
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef9c3; color: #854d0e; }

        /* Native App Feel */
        @media (max-width: 992px) {
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: #fff;
                display: flex;
                justify-content: space-around;
                padding: 10px 0;
                box-shadow: 0 -2px 15px rgba(0,0,0,0.05);
                z-index: 1001;
                border-top: 1px solid #f1f5f9;
            }
            .nav-item-mobile {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                color: #64748b;
                text-decoration: none;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
            }
            .nav-item-mobile i { font-size: 18px; }
            .nav-item-mobile.active { color: var(--primary); }
            
            .sidebar { left: -100%; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; width: 100%; padding-bottom: 80px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
        @media (min-width: 993px) {
            .mobile-bottom-nav { display: none; }
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
            cursor: pointer;
        }
        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 220px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid #f1f5f9;
            padding: 10px;
            display: none;
            margin-top: 10px;
            z-index: 1000;
        }
        .profile-menu.show {
            display: block;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .profile-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 13px;
        }
        .profile-item:hover {
            background: #f8fafc;
            color: var(--primary);
        }
        .profile-item i { font-size: 14px; opacity: 0.7; }
        
        .header-icon {
            width: 35px; height: 35px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; color: #64748b; transition: 0.2s;
            text-decoration: none;
        }
        .header-icon:hover { background: #f1f5f9; color: var(--primary); }

        .btn { padding: 8px 16px; border-radius: 8px; } /* Slightly larger for touch */

    </style>

</head>
<body>

<div class="mobile-bottom-nav">
    <a href="index.php" class="nav-item-mobile <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i>
        <span>Home</span>
    </a>
    <a href="blogs.php" class="nav-item-mobile <?php echo basename($_SERVER['PHP_SELF']) == 'blogs.php' ? 'active' : ''; ?>">
        <i class="fas fa-blog"></i>
        <span>Blogs</span>
    </a>
    <a href="enquiries.php" class="nav-item-mobile <?php echo basename($_SERVER['PHP_SELF']) == 'enquiries.php' ? 'active' : ''; ?>">
        <i class="fas fa-envelope"></i>
        <span>Leads</span>
    </a>
    <a href="settings.php" class="nav-item-mobile <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
        <i class="fas fa-cog"></i>
        <span>Setup</span>
    </a>
</div>


