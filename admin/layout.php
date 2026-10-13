<?php
require_once __DIR__ . '/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?> - <?php echo htmlspecialchars(ADMIN_SITE_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php if (!empty($useDataTables)): ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <?php endif; ?>
    <style>
        :root {
            --admin-green: #229954;
            --admin-green-dark: #187642;
            --admin-green-soft: #eaf6ee;
            --admin-ink: #1d2b22;
            --admin-muted: #748078;
            --admin-border: #e7ede8;
            --admin-canvas: #f4f7f5;
            --admin-sidebar-width: 264px;
            --admin-radius: 14px;
            --admin-shadow: 0 8px 28px rgba(28, 52, 36, 0.06);
        }
        body {
            min-height: 100vh;
            color: var(--admin-ink);
            background: var(--admin-canvas);
            font-family: Inter, "Segoe UI", -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .sidebar {
            position: fixed;
            z-index: 1000;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--admin-sidebar-width);
            overflow-y: auto;
            color: #d8e3dc;
            background: #17251d;
            box-shadow: 8px 0 30px rgba(19, 39, 26, 0.08);
        }
        .sidebar-brand {
            padding: 27px 22px 25px;
            color: #fff;
            text-align: left;
            background: linear-gradient(135deg, #25aa5e, #1b8548);
        }
        .sidebar-brand h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 750;
            letter-spacing: -0.02em;
        }
        .sidebar-brand h4 i {
            margin-right: 8px;
        }
        .sidebar-brand small {
            display: block;
            margin: 5px 0 0 31px;
            color: rgba(255, 255, 255, 0.76);
            font-size: 0.74rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .sidebar-menu {
            padding: 20px 13px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            min-height: 46px;
            margin: 4px 0;
            padding: 10px 13px;
            border: 1px solid transparent;
            border-radius: 9px;
            color: #aab8ae;
            font-size: 0.91rem;
            font-weight: 550;
            text-decoration: none;
            transition: color 160ms ease, background 160ms ease, border-color 160ms ease;
        }
        .sidebar-menu a:focus-visible {
            outline: 2px solid #64d48c;
            outline-offset: 2px;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: #fff;
            border-color: rgba(113, 219, 151, 0.13);
            background: rgba(64, 178, 103, 0.16);
        }
        .sidebar-menu a.active {
            box-shadow: inset 3px 0 #43c477;
        }
        .sidebar-menu a i {
            width: 25px;
            margin-right: 9px;
            color: #8ca394;
            text-align: center;
        }
        .sidebar-menu a.active i,
        .sidebar-menu a:hover i {
            color: #64d48c;
        }
        .sidebar-menu hr {
            margin: 18px 10px;
            border-color: rgba(221, 238, 226, 0.13) !important;
        }
        .main-content {
            min-height: 100vh;
            margin-left: var(--admin-sidebar-width);
            padding: 26px clamp(20px, 3vw, 42px) 42px;
        }
        .topbar {
            position: sticky;
            z-index: 900;
            top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 66px;
            margin-bottom: 26px;
            padding: 13px 22px;
            border: 1px solid rgba(229, 236, 230, 0.9);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 5px 20px rgba(28, 52, 36, 0.045);
            backdrop-filter: blur(12px);
        }
        .topbar h5 {
            margin: 0;
            color: var(--admin-ink);
            font-size: 1.08rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #526057;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .user-dropdown > span i {
            margin-right: 5px;
            color: var(--admin-green);
            font-size: 1.15rem;
            vertical-align: -1px;
        }
        .user-dropdown .btn {
            display: grid;
            width: 36px;
            height: 36px;
            padding: 0;
            place-items: center;
            border-radius: 9px;
        }
        .card {
            overflow: hidden;
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius);
            background: #fff;
            box-shadow: var(--admin-shadow);
        }
        .card-header {
            min-height: 62px;
            padding: 17px 21px;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-ink);
            background: #fff;
            font-size: 0.96rem;
            font-weight: 700;
        }
        .card-header > span > i,
        .card-header > i {
            color: var(--admin-green);
        }
        .card-body {
            padding: 22px;
        }
        .card-body.p-0 {
            padding: 0;
        }
        .stat-card {
            position: relative;
            min-height: 145px;
            padding: 23px;
            overflow: hidden;
            border: 1px solid var(--admin-border);
            border-top: 3px solid var(--admin-green);
            border-radius: 13px;
            background: #fff;
            box-shadow: var(--admin-shadow);
            transition: transform 160ms ease, box-shadow 160ms ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 32px rgba(28, 52, 36, 0.1);
        }
        .stat-card h3 {
            position: relative;
            z-index: 1;
            margin: 4px 0 8px;
            color: var(--admin-ink);
            font-size: clamp(1.8rem, 2.5vw, 2.35rem);
            font-weight: 750;
            letter-spacing: -0.055em;
        }
        .stat-card p {
            position: relative;
            z-index: 1;
            margin: 0;
            color: var(--admin-muted);
            font-size: 0.82rem;
            font-weight: 650;
        }
        .stat-card .icon {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            color: rgba(34, 153, 84, 0.11);
            font-size: 3.3rem;
        }
        .table-responsive {
            border-radius: inherit;
        }
        .dataTables_wrapper {
            padding: 16px;
        }
        .dataTables_wrapper .row:first-child,
        .dataTables_wrapper .row:last-child {
            row-gap: 12px;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            color: var(--admin-muted);
            font-size: 0.84rem;
        }
        .dataTables_wrapper .dataTables_filter {
            text-align: right;
        }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            min-height: 38px;
            margin-left: 8px;
            border: 1px solid #dfe7e1;
            border-radius: 7px;
            background: #fff;
            color: #2d3a31;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 6px 10px;
        }
        .dataTables_wrapper .dataTables_info {
            color: var(--admin-muted);
            font-size: 0.82rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
        }
        .dataTables_processing {
            z-index: 3;
            border: 1px solid var(--admin-border) !important;
            border-radius: 8px;
            background: #fff !important;
            box-shadow: var(--admin-shadow);
        }
        .dashboard-table-card {
            height: 100%;
        }
        .dashboard-table-card .dataTables_scrollBody {
            height: 260px !important;
            max-height: 260px !important;
        }
        .dashboard-table-card .table {
            table-layout: fixed;
        }
        .table {
            margin-bottom: 0;
            color: #3c4940;
            font-size: 0.88rem;
        }
        .table thead th {
            padding: 13px 16px;
            border-bottom: 1px solid var(--admin-border);
            color: #77837a;
            background: #f8faf8;
            font-size: 0.7rem;
            font-weight: 750;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .table td {
            padding: 14px 16px;
            border-color: #edf1ed;
            vertical-align: middle;
        }
        .table td form {
            display: inline-block;
            margin: 0 0 0 0.3rem;
            vertical-align: middle;
        }
        .table tbody tr {
            transition: background-color 140ms ease;
        }
        .table-hover > tbody > tr:hover > * {
            color: inherit;
            background: #f7fbf8;
        }
        .table .fw-bold {
            color: #29382e;
            font-weight: 650 !important;
        }
        .table .text-muted,
        .text-muted {
            color: var(--admin-muted) !important;
        }
        .table .img-thumbnail {
            padding: 2px;
            border-color: var(--admin-border);
            border-radius: 7px;
            background: #fff;
        }
        .table code {
            padding: 4px 7px;
            border-radius: 5px;
            color: #38734c;
            background: #eef6f0;
        }
        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: transform 140ms ease, box-shadow 140ms ease, background-color 140ms ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .btn-primary {
            border-color: var(--admin-green);
            background: var(--admin-green);
            box-shadow: 0 3px 8px rgba(34, 153, 84, 0.12);
        }
        .btn-primary:hover,
        .btn-primary:focus {
            border-color: var(--admin-green-dark);
            background: var(--admin-green-dark);
        }
        .btn-outline-primary {
            border-color: #b9d8c3;
            color: var(--admin-green-dark);
        }
        .btn-outline-primary:hover {
            border-color: var(--admin-green);
            background: var(--admin-green);
        }
        .btn-outline-danger,
        .btn-outline-secondary,
        .btn-light {
            border-color: var(--admin-border);
        }
        .btn-group form {
            display: inline-flex;
        }
        .form-label {
            margin-bottom: 7px;
            color: #45534a;
            font-size: 0.84rem;
            font-weight: 650;
        }
        .form-control,
        .form-select {
            min-height: 43px;
            border-color: #dfe7e1;
            border-radius: 8px;
            color: #2d3a31;
            background-color: #fff;
            font-size: 0.9rem;
        }
        textarea.form-control {
            min-height: auto;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #75c591;
            box-shadow: 0 0 0 0.22rem rgba(34, 153, 84, 0.12);
        }
        .form-check-input:checked {
            border-color: var(--admin-green);
            background-color: var(--admin-green);
        }
        .form-check-input:focus {
            border-color: #75c591;
            box-shadow: 0 0 0 0.22rem rgba(34, 153, 84, 0.12);
        }
        .badge {
            padding: 0.45em 0.7em;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .badge-new { background: #3183cf; }
        .badge-contacted { background: #d78b19; }
        .badge-converted { background: #229954; }
        .badge-lost { background: #d94a4a; }
        .alert {
            border: 1px solid transparent;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(28, 52, 36, 0.04);
        }
        .alert-success {
            border-color: #d2ebda;
            color: #24633a;
            background: #eff9f2;
        }
        .alert-danger {
            border-color: #f2d4d2;
            color: #933b37;
            background: #fff5f4;
        }
        .alert-dismissible .btn-close {
            padding: 1rem;
        }
        .dropdown-menu {
            padding: 6px;
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(25, 44, 31, 0.13);
        }
        .dropdown-item {
            border-radius: 6px;
            font-size: 0.86rem;
        }
        .dropdown-item:hover {
            color: var(--admin-green-dark);
            background: var(--admin-green-soft);
        }
        .modal-content {
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            box-shadow: 0 18px 50px rgba(19, 39, 26, 0.18);
        }
        .modal-header,
        .modal-footer {
            border-color: var(--admin-border);
        }
        @media (max-width: 991.98px) {
            :root {
                --admin-sidebar-width: 220px;
            }
            .main-content {
                padding: 20px;
            }
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: relative;
                right: auto;
                bottom: auto;
                width: 100%;
                min-height: 0;
            }
            .sidebar-brand {
                padding: 16px 20px;
            }
            .sidebar-menu {
                display: flex;
                gap: 5px;
                overflow-x: auto;
                padding: 8px 12px 12px;
                white-space: nowrap;
            }
            .sidebar-menu a {
                flex: 0 0 auto;
                min-height: 40px;
                margin: 0;
                padding: 8px 11px;
            }
            .sidebar-menu a.active {
                box-shadow: inset 0 -3px #43c477;
            }
            .sidebar-menu hr {
                display: none;
            }
            .main-content {
                min-height: auto;
                margin-left: 0;
                padding: 15px 14px 28px;
            }
            .topbar {
                position: static;
                min-height: 58px;
                margin-bottom: 17px;
                padding: 11px 15px;
            }
            .card-header {
                padding: 14px 16px;
            }
            .card-body {
                padding: 17px;
            }
            .stat-card {
                min-height: 125px;
                padding: 18px;
            }
        }
        @media (max-width: 575.98px) {
            .user-dropdown {
                gap: 6px;
                font-size: 0.78rem;
            }
            .user-dropdown .btn {
                width: 32px;
                height: 32px;
            }
            .table {
                font-size: 0.82rem;
            }
            .table td {
                padding: 11px 12px;
            }
            .table td.empty-state {
                text-align: left !important;
            }
        }
        @media print {
            .sidebar,
            .topbar,
            .btn,
            .dropdown,
            form {
                display: none !important;
            }
            .main-content {
                margin: 0;
                padding: 0;
            }
            .card,
            .stat-card {
                box-shadow: none;
            }
        }
    </style>
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
</head>
<body class="admin-shell">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4><Img src="../assets/img/logo/logo.webp" alt="AgroMarket Logo" style="height: 30px; margin-right: 10px;"></Img> AgroMarket</h4>
            <small>Admin Panel</small>

        </div>
        <div class="sidebar-menu">
            <a href="<?php echo htmlspecialchars(adminUrl('dashboard.php')); ?>" class="<?php echo ($activePage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="<?php echo htmlspecialchars(adminUrl('leads.php')); ?>" class="<?php echo ($activePage ?? '') === 'leads' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Leads
                <?php if (!isset($errorMessage)):
                    $newLeadsCount = (int) getDB()->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn();
                    if ($newLeadsCount > 0):
                ?>
                    <span class="badge bg-danger ms-auto"><?php echo (int) $newLeadsCount; ?></span>
                <?php endif; endif; ?>
            </a>
            <a href="<?php echo htmlspecialchars(adminUrl('blogs.php')); ?>" class="<?php echo ($activePage ?? '') === 'blogs' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog Posts
            </a>
            <a href="<?php echo htmlspecialchars(adminUrl('blog-categories.php')); ?>" class="<?php echo ($activePage ?? '') === 'categories' ? 'active' : ''; ?>">
                <i class="fas fa-folder"></i> Categories
            </a>
            <a href="<?php echo htmlspecialchars(adminUrl('tags.php')); ?>" class="<?php echo ($activePage ?? '') === 'tags' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Tags
            </a>
            <hr style="border-color: #333; margin: 15px 20px;">
            <a href="<?php echo htmlspecialchars(adminUrl('../index.php')); ?>" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
            <a href="<?php echo htmlspecialchars(adminUrl('logout.php')); ?>">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0"><?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?></h5>
            <div class="user-dropdown">
                <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                <a href="<?php echo htmlspecialchars(adminUrl('logout.php')); ?>" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <?php if (isset($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($successMessage); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errorMessage); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?php echo $content ?? ''; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($useDataTables)): ?>
        <script src="../assets/js/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
        <script>
            window.initAdminDataTable = function (selector, tableName, options = {}) {
                return $(selector).DataTable(Object.assign({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: <?php echo json_encode(adminUrl('datatable-ajax.php')); ?>,
                        data: function (request) {
                            request.table = tableName;
                        }
                    },
                    pageLength: 10,
                    lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                    autoWidth: false,
                    dom: '<"row g-3 align-items-center mb-3"<"col-sm-6"l><"col-sm-6"f>>t<"row g-3 align-items-center mt-3"<"col-sm-5"i><"col-sm-7"p>>',
                    language: {
                        lengthMenu: 'Show _MENU_ entries',
                        search: '',
                        searchPlaceholder: 'Search records...'
                    }
                }, options));
            };
        </script>
    <?php endif; ?>
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