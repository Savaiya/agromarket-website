<?php
require_once 'config.php';
requireLogin();

$pageTitle = 'Dashboard';

// Get statistics
try {
    $db = getDB();
    
    // Total leads
    $totalLeads = $db->query("SELECT COUNT(*) as count FROM leads")->fetch();
    
    // New leads
    $newLeads = $db->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new'")->fetch();
    
    // Converted leads
    $convertedLeads = $db->query("SELECT COUNT(*) as count FROM leads WHERE status = 'converted'")->fetch();
    
    // Total blogs
    $totalBlogs = $db->query("SELECT COUNT(*) as count FROM blogs")->fetch();
    
    // Published blogs
    $publishedBlogs = $db->query("SELECT COUNT(*) as count FROM blogs WHERE status = 'published'")->fetch();
    
    // Recent leads
    $recentLeads = $db->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();
    
    // Recent blogs
    $recentBlogs = $db->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 5")->fetchAll();
    
} catch (Exception $e) {
    $error = $e->getMessage();
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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Leads</span>
                <a href="leads.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentLeads)): ?>
                                <?php foreach ($recentLeads as $lead): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($lead['name']); ?></td>
                                    <td><?php echo htmlspecialchars($lead['email']); ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = [
                                            'new' => 'badge-new',
                                            'contacted' => 'badge-contacted',
                                            'converted' => 'badge-converted',
                                            'lost' => 'badge-lost'
                                        ];
                                        ?>
                                        <span class="badge <?php echo $statusClass[$lead['status']] ?? 'bg-secondary'; ?>">
                                            <?php echo ucfirst($lead['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d', strtotime($lead['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No leads yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Blogs -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Blogs</span>
                <a href="blogs.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentBlogs)): ?>
                                <?php foreach ($recentBlogs as $blog): 
                                    $cat = $db->query("SELECT name FROM blog_categories WHERE id = " . intval($blog['category_id']))->fetch();
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                                    <td><?php echo $cat ? htmlspecialchars($cat['name']) : '-'; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $blog['status'] === 'published' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($blog['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d', strtotime($blog['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No blogs yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
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
                        <a href="leads.php?action=export" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-download"></i> Export Leads
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="blogs.php?action=create" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-plus"></i> New Blog Post
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="blog-categories.php?action=create" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-folder-plus"></i> New Category
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="../index.php" target="_blank" class="btn btn-outline-dark w-100 mb-2">
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

include 'layout.php';