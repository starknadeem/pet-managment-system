<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<style>
    .auth-wrapper { position: relative; overflow: hidden; padding: 120px 0 100px; min-height: 80vh; }
    .floating-icon { position: absolute; opacity: 0.05; font-size: 15rem; z-index: -1; animation: float 10s ease-in-out infinite; color: var(--primary); }
    .icon-left { top: 10%; left: -2%; }
    .icon-right { bottom: 5%; right: -2%; }
    
    .auth-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    .input-group-text {
        background: var(--bg);
        border: 1px solid var(--border);
        border-right: none;
        border-radius: 15px 0 0 15px;
        color: var(--primary);
    }
    .form-control-modern {
        background: var(--bg) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
        padding: 14px 20px;
    }
</style>

<div class="auth-wrapper">
    <i class="fa-solid fa-paw floating-icon icon-left"></i>
    <i class="fa-solid fa-fish floating-icon icon-right"></i>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8" data-aos="zoom-in">
                <div class="auth-card p-4 p-md-5">
                    <div class="text-center mb-5">
                        <div class="logo-icon mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                            <i class="fa-solid fa-unlock-keyhole"></i>
                        </div>
                        <h2 class="fw-800">Welcome Back</h2>
                        <p class="opacity-75">Sign in to your pet dashboard</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger border-0 rounded-4 mb-4 small fw-bold">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="includes/auth_handler.php?action=login">
                        <div class="mb-4 text-center">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="login_role" id="l_user" value="user" checked>
                                <label class="btn btn-outline-primary py-2 fw-bold" style="border-radius: 15px 0 0 15px;" for="l_user">Pet Owner</label>

                                <input type="radio" class="btn-check" name="login_role" id="l_vet" value="vet">
                                <label class="btn btn-outline-primary py-2 fw-bold" style="border-radius: 0 15px 15px 0;" for="l_vet">Veterinarian</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                                <input type="email" name="email" class="form-control form-control-modern" style="border-radius: 0 15px 15px 0;" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" id="loginPass" class="form-control form-control-modern" placeholder="••••••••" required>
                                <span class="input-group-text" style="cursor:pointer; border-radius: 0 15px 15px 0; border-left:none;" onclick="togglePass('loginPass', this)">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold mb-4" style="background: var(--primary); border:none;">
                            Access Account <i class="fa-solid fa-right-to-bracket ms-2"></i>
                        </button>

                        <div class="text-center">
                            <p class="small opacity-75">Don't have an account? <a href="signup.php" class="text-primary fw-bold text-decoration-none">Sign up free</a></p>
                            
                            <p class="mt-3">
                                <a href="admin/login.php" class="text-muted small text-decoration-none">
                                    <i class="fa-solid fa-shield-halved me-1"></i> Login as Admin
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePass(id, el) {
    const input = document.getElementById(id);
    const icon = el.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}
</script>

<?php include 'includes/footer.php'; ?>