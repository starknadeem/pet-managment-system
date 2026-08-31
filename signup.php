<?php 
require 'config/db.php'; 
include 'includes/header.php'; 
?>

<style>
    .auth-wrapper { position: relative; overflow: hidden; padding: 120px 0 100px; background: var(--bg); }
    .brand-logo-float { position: absolute; opacity: 0.07; font-size: 10rem; z-index: 0; color: var(--primary); animation: float 10s ease-in-out infinite; }
    .bl-1 { top: 10%; left: 5%; }
    .bl-2 { bottom: 15%; right: 5%; transform: rotate(20deg); }
    .bl-3 { top: 50%; right: 10%; opacity: 0.03; font-size: 15rem; }

    .auth-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 40px;
        box-shadow: 0 40px 80px -20px rgba(0,0,0,0.15);
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
    }
    .form-control-modern {
        background: var(--bg) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
        padding: 12px 18px;
        border-radius: 12px;
        transition: 0.3s;
    }
    .form-control-modern:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
    .input-group-text { background: var(--bg); border: 1px solid var(--border); color: var(--primary); border-radius: 12px 0 0 12px; }
    .role-select { border: 2px solid var(--border); border-radius: 20px; transition: 0.3s; cursor: pointer; }
    .btn-check:checked + .role-select { border-color: var(--primary); background: rgba(99, 102, 241, 0.05); color: var(--primary); transform: translateY(-5px); }
</style>

<div class="auth-wrapper">
    <i class="fa-solid fa-shield-dog brand-logo-float bl-1"></i>
    <i class="fa-solid fa-cat brand-logo-float bl-2"></i>
    <i class="fa-solid fa-paw brand-logo-float bl-3"></i>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8" data-aos="fade-up">
                <div class="auth-card p-4 p-md-5">
                    <div class="text-center mb-5">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3 fw-bold"></span>
                        <h1 class="fw-800 display-6">Create Your Profile</h1>
                        <p class="opacity-75">Fill in your details to access Pakistan's #1 Pet Portal</p>
                    </div>

                    <form method="POST" action="includes/auth_handler.php?action=signup" id="signupForm" onsubmit="return validateForm()">
                        <div class="mb-5">
                            <label class="form-label small fw-800 text-uppercase opacity-50 d-block text-center mb-4">Choose Account Type</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="role" id="role_user" value="user" checked>
                                    <label class="role-select p-4 d-block text-center" for="role_user">
                                        <i class="fa-solid fa-user-tag fs-2 mb-2"></i>
                                        <span class="d-block fw-bold fs-5">Pet Owner</span>
                                        <small class="opacity-70">I want to care for my pets</small>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="role" id="role_vet" value="vet">
                                    <label class="role-select p-4 d-block text-center" for="role_vet">
                                        <i class="fa-solid fa-user-doctor fs-2 mb-2"></i>
                                        <span class="d-block fw-bold fs-5">Veterinarian</span>
                                        <small class="opacity-70">I want to provide services</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-modern" placeholder="e.g. Ahmed Ali" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-modern" placeholder="ahmed@example.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control form-control-modern" 
                                       placeholder="03001234567" pattern="03[0-9]{9}" 
                                       title="Please enter a valid Pakistani number starting with 03 (11 digits)" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">City</label>
                                <select name="city" class="form-select form-control-modern" required>
                                    <option value="" selected disabled>Select City</option>
                                    <option>Islamabad</option>
                                    <option>Lahore</option>
                                    <option>Karachi</option>
                                    <option>Peshawar</option>
                                    <option>Faisalabad</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Residential Address</label>
                                <input type="text" name="address" class="form-control form-control-modern" placeholder="House #, Street, Area..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Create Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="p1" class="form-control form-control-modern" placeholder="Min. 8 characters" required minlength="8">
                                    <button type="button" class="btn border-start-0 border btn-outline-secondary" style="border-radius: 0 12px 12px 0;" onclick="toggle('p1')"><i class="fa-solid fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="p2" class="form-control form-control-modern" placeholder="Re-type password" required>
                                    <button type="button" class="btn border-start-0 border btn-outline-secondary" style="border-radius: 0 12px 12px 0;" onclick="toggle('p2')"><i class="fa-solid fa-eye"></i></button>
                                </div>
                                <div id="passError" class="text-danger small mt-2 d-none">Passwords do not match!</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold mt-5 fs-5" style="background: var(--primary); border:none; box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.4);">
                            Create My Account <i class="fa-solid fa-circle-check ms-2"></i>
                        </button>

                        <p class="text-center mt-4 small opacity-75">By signing up, you agree to our <a href="#" class="text-primary text-decoration-none fw-bold">Terms & Conditions</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggle(id) {
    const p = document.getElementById(id);
    p.type = p.type === 'password' ? 'text' : 'password';
}

function validateForm() {
    const p1 = document.getElementById('p1').value;
    const p2 = document.getElementById('p2').value;
    const error = document.getElementById('passError');
    
    if (p1 !== p2) {
        error.classList.remove('d-none');
        return false;
    }
    error.classList.add('d-none');
    return true;
}
</script>

<?php include 'includes/footer.php'; ?>