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
        /* DataTables Custom Styling - Premium Pagination */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 6px 12px !important;
            margin: 0 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            background: #fff !important;
            color: #64748b !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: 0.2s !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8fafc !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important;
            color: #fff !important;
            border-color: var(--primary) !important;
            box-shadow: 0 4px 10px rgba(255, 20, 147, 0.2);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            background: #f1f5f9 !important;
        }
        .dataTables_wrapper .dataTables_paginate .previous,
        .dataTables_wrapper .dataTables_paginate .next {
            background: #f8fafc !important;
            font-weight: 700 !important;
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

    </style>
</head>
<body>

