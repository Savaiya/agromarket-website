<?php
require_once 'config.php';
require_once 'database.php';

$error = '';
$success = '';

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

// Include login form view
ob_start();
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
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #2ecc71 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background: #2ecc71;
            padding: 30px;
            text-align: center;
        }
        .login-header h2 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        .login-header p {
            color: rgba(255,255,255,0.8);
            margin: 10px 0 0 0;
        }
        .login-body {
            padding: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
        }
        .btn-login {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #27ae60;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2><i class="fas fa-leaf"></i> AMPL Agro Market</h2>
            <p>Admin Panel Login</p>
        </div>
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Enter your email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Back to Website
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
echo ob_get_clean();