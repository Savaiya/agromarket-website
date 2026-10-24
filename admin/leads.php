<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/src/controller/LeadsController.php';

$leadData = (new LeadsController())->handle();
extract($leadData, EXTR_SKIP);

$pageTitle = 'Leads Management';
$activePage = 'leads';
$useDataTables = true;

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
