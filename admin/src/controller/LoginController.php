<?php

require_once __DIR__ . '/../../../config/config.php';

class LoginController
{
    public function handle(): array
    {
        $error = '';
        $email = '';

        if (isLoggedIn()) {
            header('Location: ' . adminUrl('dashboard.php'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireValidCsrfToken();
            $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

            if ($email === '' || $password === '') {
                $error = 'Please enter both email and password.';
            } else {
                try {
                    $db = getDB();
                    $statement = $db->prepare(
                        "SELECT id, name, email, password, role
                         FROM admins
                         WHERE email = :email AND status = 'active'
                         LIMIT 1"
                    );
                    $statement->execute(['email' => $email]);
                    $admin = $statement->fetch();

                    if ($admin && password_verify($password, $admin['password'])) {
                        session_regenerate_id(true);
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_name'] = $admin['name'];
                        $_SESSION['admin_email'] = $admin['email'];
                        $_SESSION['admin_role'] = $admin['role'];

                        $statement = $db->prepare('UPDATE admins SET last_login = UTC_TIMESTAMP() WHERE id = :id');
                        $statement->execute(['id' => $admin['id']]);

                        header('Location: ' . adminUrl('dashboard.php'));
                        exit;
                    }

                    $error = 'Invalid email or password.';
                } catch (RuntimeException $exception) {
                    error_log('Admin MySQL login operation failed: ' . $exception->getMessage());
                    $error = 'The admin database is unavailable or not configured. Please check the MySQL settings.';
                }
            }
        }

        return ['error' => $error, 'email' => $email];
    }
}
