<?php
require_once __DIR__ . '/config.php';
requireLogin();

$pageTitle = 'Leads Management';
$activePage = 'leads';
$useDataTables = true;

$db = getDB();

if (isset($_GET['action']) && $_GET['action'] === 'export') {
    $leads = $db->query(
        'SELECT id, name, email, phone, subject, message, status, created_at
         FROM leads ORDER BY created_at DESC'
    )->fetchAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="leads.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Created']);
    foreach ($leads as $lead) {
        fputcsv($output, [
            $lead['id'] ?? '',
            $lead['name'] ?? '',
            $lead['email'] ?? '',
            $lead['phone'] ?? '',
            $lead['subject'] ?? '',
            $lead['message'] ?? '',
            $lead['status'] ?? '',
            adminFormatDate($lead['created_at'] ?? null, 'Y-m-d H:i:s'),
        ]);
    }
    fclose($output);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidCsrfToken();
}

// Handle status update
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = is_string($_POST['id']) ? trim($_POST['id']) : '';
    $status = $_POST['status'];
    $allowedStatuses = ['new', 'contacted', 'converted', 'lost'];
    
    if (!isAdminId($id) || !is_string($status) || !in_array($status, $allowedStatuses, true)) {
        http_response_code(400);
        exit('Invalid lead status update.');
    }
    $statement = $db->prepare('UPDATE leads SET status = :status, updated_at = UTC_TIMESTAMP() WHERE id = :id');
    $statement->execute(['status' => $status, 'id' => $id]);
    $successMessage = $statement->rowCount() > 0
        ? 'Lead status updated successfully.'
        : 'The selected lead was not found.';
}

// Handle delete
if (isset($_POST['delete'])) {
    $id = is_string($_POST['delete']) ? trim($_POST['delete']) : '';
    if (!isAdminId($id)) {
        http_response_code(400);
        exit('Invalid lead identifier.');
    }
    $statement = $db->prepare('DELETE FROM leads WHERE id = :id');
    $statement->execute(['id' => $id]);
    $successMessage = $statement->rowCount() > 0 ? 'Lead deleted successfully.' : 'The selected lead was not found.';
}

ob_start();
?>

<div class="card">
    <!-- <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-envelope me-2"></i> All Contact Leads</span>
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div> -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="leadsTable" class="table table-hover align-middle mb-0 w-100">
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
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="leadMessageModal" tabindex="-1" aria-labelledby="leadMessageTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadMessageTitle">Lead message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Subject:</strong> <span id="leadMessageSubject"></span></p>
                <hr>
                <p id="leadMessageBody" style="white-space: pre-line;"></p>
            </div>
            <div class="modal-footer">
                <a id="leadReplyLink" href="#" class="btn btn-primary btn-sm">Reply via Email</a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$extraScripts = '<script>window.initAdminDataTable("#leadsTable", "leads", {order:[[6,"desc"]], columnDefs:[{targets:[0,4,7],orderable:false,searchable:false}]});document.getElementById("leadMessageModal").addEventListener("show.bs.modal",function(event){const button=event.relatedTarget;document.getElementById("leadMessageTitle").textContent="Message from "+button.dataset.leadName;document.getElementById("leadMessageSubject").textContent=button.dataset.leadSubject||"—";document.getElementById("leadMessageBody").textContent=button.dataset.leadMessage||"";document.getElementById("leadReplyLink").href="mailto:"+button.dataset.leadEmail;});</script>';
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
