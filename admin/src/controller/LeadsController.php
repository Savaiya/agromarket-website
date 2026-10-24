<?php

require_once __DIR__ . '/../../../config/config.php';

class LeadsController
{
    public function handle(): array
    {
        requireLogin();
        $db = getDB();
        $successMessage = null;

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

        if (isset($_POST['delete'])) {
            $id = is_string($_POST['delete']) ? trim($_POST['delete']) : '';
            if (!isAdminId($id)) {
                http_response_code(400);
                exit('Invalid lead identifier.');
            }
            $statement = $db->prepare('DELETE FROM leads WHERE id = :id');
            $statement->execute(['id' => $id]);
            $successMessage = $statement->rowCount() > 0
                ? 'Lead deleted successfully.'
                : 'The selected lead was not found.';
        }

        return ['successMessage' => $successMessage];
    }
}
