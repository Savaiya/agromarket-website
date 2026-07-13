<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard'; ?> - AMPL Agro Market Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2ecc71;
            --primary-dark: #27ae60;
            --dark: #1a1a1a;
            --light: #f8f9fa;
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--dark);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        .sidebar-brand {
            padding: 20px;
            background: var(--primary);
            color: white;
            text-align: center;
        }
        .sidebar-brand h4 {
            margin: 0;
            font-weight: 600;
        }
        .sidebar-menu {
            padding: 15px 0;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #aaa;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(46, 204, 113, 0.1);
            color: white;
            border-left-color: var(--primary);
        }
        .sidebar-menu a i {
            width: 25px;
            margin-right: 10px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        .topbar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid var(--primary);
        }
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark);
        }
        .stat-card p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .stat-card .icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            color: rgba(46, 204, 113, 0.2);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .badge-new { background: #3498db; }
        .badge-contacted { background: #f39c12; }
        .badge-converted { background: #27ae60; }
        .badge-lost { background: #e74c3c; }
        .table th {
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #eee;
        }
        .table td {
            vertical-align: middle;
        }
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="fas fa-leaf"></i> AMPL Agro</h4>
            <small>Admin Panel</small>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="<?php echo $activePage ?? '' === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="leads.php" class="<?php echo $activePage ?? '' === 'leads' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Leads
                <?php 
                require_once 'database.php';
                try {
                    $newLeads = getDB()->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new'")->fetch();
                    if ($newLeads['count'] > 0): 
                ?>
                <span class="badge bg-danger ms-auto"><?php echo $newLeads['count']; ?></span>
                <?php endif; } catch (Exception $e) {} ?>
            </a>
            <a href="blogs.php" class="<?php echo $activePage ?? '' === 'blogs' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog Posts
            </a>
            <a href="blog-categories.php" class="<?php echo $activePage ?? '' === 'categories' ? 'active' : ''; ?>">
                <i class="fas fa-folder"></i> Categories
            </a>
            <a href="products.php" class="<?php echo $activePage ?? '' === 'products' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="settings.php" class="<?php echo $activePage ?? '' === 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
            <hr style="border-color: #333; margin: 15px 20px;">
            <a href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0"><?php echo $pageTitle ?? 'Dashboard'; ?></h5>
            <div class="user-dropdown">
                <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <?php if (isset($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $errorMessage; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?php echo $content ?? ''; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
    <script>
        // Delete confirmation
        function confirmDelete(url, message = 'Are you sure you want to delete this item?') {
            Swal.fire({
                title: 'Confirm Delete',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
</body>
</html>