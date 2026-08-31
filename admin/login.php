<?php
session_start();
require '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin_accounts WHERE username = ?");
    $stmt->execute([$user]);
    $admin = $stmt->fetch();

    if ($admin && $pass === $admin['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $admin['username'];
        $_SESSION['role'] = 'admin'; 
        
        header("Location: admindashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e2937 0%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .form-control {
            border-radius: 12px;
            padding: 14px 20px;
            border: 1px solid #e2e8f0;
        }
        .btn-login {
            border-radius: 12px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        
        <div class="login-header">
            <i class="fa-solid fa-shield-halved fa-3x mb-3"></i>
            <h3 class="fw-bold mb-1">Admin Panel</h3>
            <p class="mb-0 opacity-75">Restricted Access</p>
        </div>

        <div class="p-5">
            <?php if($error): ?>
                <div class="alert alert-danger rounded-3">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                           placeholder="Enter admin username" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-dark w-100 btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Login to Dashboard
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="../login.php" class="text-muted text-decoration-none small">
                    ← Back to User Login
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>