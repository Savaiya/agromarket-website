<?php
require_once 'config.php';
require_once 'database.php';

$error = '';
$success = '';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $stmt = getDB()->prepare("SELECT * FROM admins WHERE email = ? AND status = 'active' LIMIT 1");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                // Login successful
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_role'] = $admin['role'];
                
                // Update last login
                $updateStmt = getDB()->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
                $updateStmt->execute([$admin['id']]);
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AMPL Agro Market Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2ecc71;
            --primary-dark: #27ae60;
            --dark: #1a1a1a;
        }
        body {
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 420px;
            width: 90%;
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-header {
            background: var(--primary);
            padding: 35px 30px;
            text-align: center;
            color: white;
        }
        .login-header i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .login-header h2 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .login-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .login-body {
            padding: 40px 35px;
        }
        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(46, 204, 113, 0.1);
            outline: none;
        }
        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
            color: white;
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #777;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-leaf"></i>
            <h2>AMPL AGRO</h2>
            <p>Quality Agricultural Solutions</p>
        </div>
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="admin@agromarket.com" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Dashboard
                </button>
            </form>
            
            <div class="text-center">
                <a href="../index.php" class="back-link">
                    <i class="fas fa-arrow-left me-1"></i> Return to Website
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>