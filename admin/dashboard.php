<?php
require_once __DIR__ . '/config.php';
requireLogin();

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$useDataTables = true;

// Get statistics
try {
    $db = getDB();
    $leadStats = $db->query(
        "SELECT COUNT(*) AS total, SUM(status = 'new') AS new_count,
                SUM(status = 'converted') AS converted_count
         FROM leads"
    )->fetch();
    $blogStats = $db->query(
        "SELECT COUNT(*) AS total, SUM(status = 'published') AS published_count
         FROM blogs"
    )->fetch();
    $totalLeads = ['count' => (int) $leadStats['total']];
    $newLeads = ['count' => (int) $leadStats['new_count']];
    $convertedLeads = ['count' => (int) $leadStats['converted_count']];
    $totalBlogs = ['count' => (int) $blogStats['total']];
    $publishedBlogs = ['count' => (int) $blogStats['published_count']];
} catch (RuntimeException $e) {
    error_log('Admin MySQL dashboard data could not be loaded: ' . $e->getMessage());
    $errorMessage = 'Dashboard data could not be loaded. Check the MySQL configuration and tables.';
    $totalLeads = $newLeads = $convertedLeads = $totalBlogs = $publishedBlogs = ['count' => 0];
}

ob_start();
?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card position-relative">
            <h3><?php echo $totalLeads['count'] ?? 0; ?></h3>
            <p>Total Leads</p>
            <div class="icon"><i class="fas fa-envelope"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card position-relative" style="border-left-color: #3498db;">
            <h3><?php echo $newLeads['count'] ?? 0; ?></h3>
            <p>New Leads</p>
            <div class="icon"><i class="fas fa-inbox"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card position-relative" style="border-left-color: #27ae60;">
            <h3><?php echo $convertedLeads['count'] ?? 0; ?></h3>
            <p>Converted</p>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card position-relative" style="border-left-color: #9b59b6;">
            <h3><?php echo $publishedBlogs['count'] ?? 0; ?></h3>
            <p>Published Blogs</p>
            <div class="icon"><i class="fas fa-blog"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Leads -->
    <div class="col-md-6">
        <div class="card dashboard-table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Leads</span>
                <a href="<?php echo htmlspecialchars(adminUrl('leads.php')); ?>" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="dashboardLeadsTable" class="table table-hover mb-0 w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Blogs -->
    <div class="col-md-6">
        <div class="card dashboard-table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Blogs</span>
                <a href="<?php echo htmlspecialchars(adminUrl('blogs.php')); ?>" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="dashboardBlogsTable" class="table table-hover mb-0 w-100">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="<?php echo htmlspecialchars(adminUrl('leads.php?action=export')); ?>" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-download"></i> Export Leads
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo htmlspecialchars(adminUrl('blogs.php?action=create')); ?>" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-plus"></i> New Blog Post
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo htmlspecialchars(adminUrl('blog-categories.php?action=create')); ?>" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-folder-plus"></i> New Category
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo htmlspecialchars(adminUrl('../index.php')); ?>" t arget="_blank" rel="noopener noreferrer" class="btn btn-outline-dark w-100 mb-2">
                            <i class="fas fa-external-link-alt"></i> View Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

$extraScripts = '<script>window.initAdminDataTable("#dashboardLeadsTable", "dashboard-leads", {pageLength:5,lengthMenu:[[5,10,25],[5,10,25]],scrollY:"260px",scrollCollapse:false,order:[[3,"desc"]]});window.initAdminDataTable("#dashboardBlogsTable", "dashboard-blogs", {pageLength:5,lengthMenu:[[5,10,25],[5,10,25]],scrollY:"260px",scrollCollapse:false,order:[[3,"desc"]]});</script>';

require __DIR__ . '/layout.php';