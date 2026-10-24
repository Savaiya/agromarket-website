<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/src/controller/LoginController.php';
$loginData = (new LoginController())->handle();
$error = $loginData['error'];
$email = $loginData['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo htmlspecialchars($adminSiteName); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --login-green: #208b4c;
            --login-green-dark: #176d3b;
            --login-ink: #1d2b22;
        }
        body {
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            color: var(--login-ink);
            background:
                radial-gradient(ellipse at 12% 15%, rgba(84, 190, 116, 0.26), transparent 32%),
                radial-gradient(ellipse at 88% 88%, rgba(35, 132, 73, 0.3), transparent 34%),
                linear-gradient(135deg, #15231a, #203a29 55%, #15251b);
            font-family: Inter, "Segoe UI", -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        body::before,
        body::after {
            position: fixed;
            z-index: 0;
            width: 340px;
            height: 340px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            content: "";
            pointer-events: none;
        }
        body::before {
            top: -170px;
            left: -120px;
            box-shadow: 0 0 0 28px rgba(255, 255, 255, 0.025), 0 0 0 58px rgba(255, 255, 255, 0.02);
        }
        body::after {
            right: -150px;
            bottom: -200px;
            width: 420px;
            height: 420px;
            box-shadow: 0 0 0 28px rgba(255, 255, 255, 0.025), 0 0 0 58px rgba(255, 255, 255, 0.02);
        }
        .login-card {
            position: relative;
            z-index: 1;
            width: min(100%, 440px);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.28);
            animation: fadeIn 420ms ease-out both;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(14px) scale(0.99); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-header {
            position: relative;
            padding: 34px 32px 30px;
            text-align: center;
            color: #fff;
            background: linear-gradient(135deg, #249b55, #187841);
        }
        .login-logo {
            position: relative;
            z-index: 1;
            display: block;
            width: min(190px, 70%);
            height: 125px;
            margin: 0 auto 15px;
            object-fit: contain;
        }
        .login-header::after {
            position: absolute;
            right: -28px;
            bottom: -64px;
            width: 170px;
            height: 170px;
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 50%;
            box-shadow: 0 0 0 20px rgba(255, 255, 255, 0.04), 0 0 0 42px rgba(255, 255, 255, 0.03);
            content: "";
        }
        .login-header i {
            position: relative;
            z-index: 1;
            display: inline-grid;
            width: 62px;
            height: 62px;
            margin-bottom: 15px;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.26);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.13);
            font-size: 1.9rem;
        }
        .login-header h2 {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: 1.35rem;
            font-weight: 750;
            letter-spacing: 0.015em;
        }
        .login-header p {
            position: relative;
            z-index: 1;
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.87rem;
        }
        .login-body {
            padding: 32px 36px 29px;
        }
        .form-label {
            margin-bottom: 7px;
            color: #405047;
            font-size: 0.84rem;
            font-weight: 650;
        }
        .form-control {
            min-height: 48px;
            padding: 11px 14px;
            border: 1px solid #dfe7e1;
            border-radius: 9px;
            color: var(--login-ink);
            background: #fbfcfb;
            font-size: 0.92rem;
            transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }
        .form-control:focus {
            border-color: #68b984;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(34, 153, 84, 0.11);
            outline: none;
        }
        .password-field {
            position: relative;
        }
        .password-field .form-control {
            padding-right: 50px;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            display: grid;
            width: 36px;
            height: 36px;
            padding: 0;
            place-items: center;
            border: 0;
            border-radius: 7px;
            color: #65736a;
            background: transparent;
            transform: translateY(-50%);
        }
        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: var(--login-green-dark);
            background: #edf6ef;
        }
        .password-toggle:focus-visible {
            outline: 2px solid #68b984;
            outline-offset: 2px;
        }
        .btn-login {
            min-height: 49px;
            margin-top: 7px;
            padding: 12px 16px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: linear-gradient(135deg, #239b54, #1b8046);
            box-shadow: 0 7px 16px rgba(32, 139, 76, 0.19);
            font-size: 0.92rem;
            font-weight: 650;
            transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }
        .btn-login:hover,
        .btn-login:focus {
            color: #fff;
            background: linear-gradient(135deg, #208b4c, #176d3b);
            box-shadow: 0 10px 22px rgba(32, 139, 76, 0.25);
            transform: translateY(-1px);
        }
        .alert {
            padding: 13px 15px;
            border: none;
            border-radius: 9px;
            font-size: 0.87rem;
        }
        .back-link {
            display: inline-block;
            margin-top: 23px;
            color: #78847c;
            font-size: 0.86rem;
            text-decoration: none;
            transition: color 160ms ease;
        }
        .back-link:hover,
        .back-link:focus {
            color: var(--login-green);
        }
        @media (max-width: 480px) {
            .login-card {
                border-radius: 16px;
            }
            .login-header {
                padding: 28px 23px 25px;
            }
            .login-logo {
                width: min(165px, 70%);
                height: 110px;
            }
            .login-body {
                padding: 25px 23px 23px;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img class="login-logo" src="<?php echo htmlspecialchars(adminUrl('../assets/img/logo/logo.webp')); ?>" alt="AMPL AGRO Logo">
            <h2>AMPL AGRO</h2>
        </div>
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars(adminUrl('login-process.php')); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="admin@agromarket.com" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-field">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="••••••••" required>
                        <button type="button" class="password-toggle" id="passwordToggle"
                                aria-label="Show password" aria-controls="password" aria-pressed="false">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Dashboard
                </button>
            </form>
            
            <div class="text-center">
                <a href="<?php echo htmlspecialchars(adminUrl('../index.php')); ?>" class="back-link">
                    <i class="fas fa-arrow-left me-1"></i> Return to Website
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');

        passwordToggle.addEventListener('click', () => {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            passwordToggle.setAttribute('aria-pressed', String(!isVisible));
            passwordToggle.querySelector('i').classList.toggle('fa-eye', isVisible);
            passwordToggle.querySelector('i').classList.toggle('fa-eye-slash', !isVisible);
        });
    </script>
</body>
</html>