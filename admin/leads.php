<?php
require_once 'config.php';
requireLogin();

$pageTitle = 'Leads Management';
$activePage = 'leads';

$db = getDB();

// Handle Status Update
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];
    $allowedStatuses = ['new', 'contacted', 'converted', 'lost'];
    
    if (in_array($status, $allowedStatuses)) {
        $stmt = $db->prepare("UPDATE leads SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $successMessage = "Lead status updated successfully.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->execute([$id]);
    $successMessage = "Lead deleted successfully.";
}

// Fetch Leads
try {
    $leads = $db->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    $errorMessage = "Error fetching leads: " . $e->getMessage();
}

ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-envelope me-2"></i> All Contact Leads</span>
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact Info</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($leads)): ?>
                        <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td>#<?php echo $lead['id']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($lead['name']); ?></div>
                            </td>
                            <td>
                                <div><i class="fas fa-envelope fa-xs text-muted me-1"></i> <?php echo htmlspecialchars($lead['email']); ?></div>
                                <?php if ($lead['phone']): ?>
                                <div><i class="fas fa-phone fa-xs text-muted me-1"></i> <?php echo htmlspecialchars($lead['phone']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="text-truncate d-inline-block" style="max-width: 150px;"><?php echo htmlspecialchars($lead['subject']); ?></span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#msgModal<?php echo $lead['id']; ?>">
                                    View Message
                                </button>
                                
                                <!-- Message Modal -->
                                <div class="modal fade" id="msgModal<?php echo $lead['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Message from <?php echo htmlspecialchars($lead['name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Subject:</strong> <?php echo htmlspecialchars($lead['subject']); ?></p>
                                                <hr>
                                                <p style="white-space: pre-line;"><?php echo htmlspecialchars($lead['message']); ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="mailto:<?php echo $lead['email']; ?>" class="btn btn-primary btn-sm">Reply via Email</a>
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <?php 
                                    $statusClass = [
                                        'new' => 'badge-new',
                                        'contacted' => 'badge-contacted',
                                        'converted' => 'badge-converted',
                                        'lost' => 'badge-lost'
                                    ];
                                    ?>
                                    <button class="badge <?php echo $statusClass[$lead['status']] ?? 'bg-secondary'; ?> border-0 dropdown-toggle" 
                                            type="button" data-bs-toggle="dropdown">
                                        <?php echo ucfirst($lead['status']); ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?id=<?php echo $lead['id']; ?>&status=new">New</a></li>
                                        <li><a class="dropdown-item" href="?id=<?php echo $lead['id']; ?>&status=contacted">Contacted</a></li>
                                        <li><a class="dropdown-item" href="?id=<?php echo $lead['id']; ?>&status=converted">Converted</a></li>
                                        <li><a class="dropdown-item" href="?id=<?php echo $lead['id']; ?>&status=lost">Lost</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td><small><?php echo date('M d, Y h:i A', strtotime($lead['created_at'])); ?></small></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('leads.php?delete=<?php echo $lead['id']; ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No leads found in the database.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
