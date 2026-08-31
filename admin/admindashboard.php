<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$conn = new mysqli('localhost', 'pet_user', 'pet_password', 'pet_db');
if ($conn->connect_error) { 
    die("Connection Failed: " . $conn->connect_error); 
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$conn->query("ALTER TABLE products ADD COLUMN IF NOT EXISTS status ENUM('pending','approved','rejected') DEFAULT 'pending'");

$status = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'approve_listing') {
        $id = intval($_POST['id']);
        $conn->query("UPDATE products SET status='approved' WHERE id=$id");
        $status = "success|Listing Approved!";
    }

    if ($action == 'reject_listing') {
        $id = intval($_POST['id']);
        $conn->query("UPDATE products SET status='rejected' WHERE id=$id");
        $status = "success|Listing Rejected!";
    }

    if ($action == 'delete_item') {
        $id = intval($_POST['id']);
        $type = $_POST['type'];
        if ($type == 'user') $conn->query("DELETE FROM users WHERE id=$id");
        if ($type == 'product') $conn->query("DELETE FROM products WHERE id=$id");
        if ($type == 'appointment') $conn->query("DELETE FROM appointments WHERE id=$id");
        $status = "success|Record Deleted Successfully!";
    }

    if ($action == 'update_order') {
        $id = intval($_POST['id']);
        $st = mysqli_real_escape_string($conn, $_POST['status']);
        $conn->query("UPDATE orders SET status='$st' WHERE id=$id");
        $status = "success|Order Status Updated!";
    }
    
    if ($action == 'verify_vet') {
        $id = intval($_POST['id']);
        $val = intval($_POST['val']);
        $conn->query("UPDATE users SET is_verified=$val WHERE id=$id AND role='vet'");
        $status = "success|Vet Verification Status Updated!";
    }
    
    if ($action == 'save_announcement') {
        $msg = $_POST['announcement_msg'];
        file_put_contents(__DIR__.'/../config/announcement.txt', $msg);
        $status = "success|Site-wide announcement updated!";
    }
}

$current_announcement = file_exists(__DIR__.'/../config/announcement.txt') ? file_get_contents(__DIR__.'/../config/announcement.txt') : '';

$user_count = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];
$vet_count  = $conn->query("SELECT COUNT(*) FROM users WHERE role='vet'")->fetch_row()[0];
$total_apts = $conn->query("SELECT COUNT(*) FROM appointments")->fetch_row()[0];
$pending_list = $conn->query("SELECT COUNT(*) FROM products WHERE status='pending'")->fetch_row()[0];
$pending_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetch_row()[0];

$users = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY id DESC");
$vets  = $conn->query("SELECT * FROM users WHERE role='vet' ORDER BY id DESC");
$appointments = $conn->query("SELECT a.*, u.full_name as owner, v.full_name as vet_name 
                              FROM appointments a 
                              JOIN users u ON a.user_id = u.id 
                              JOIN users v ON a.vet_id = v.id ORDER BY a.id DESC");
$products = $conn->query("SELECT p.*, u.full_name as seller FROM products p 
                          JOIN users u ON p.seller_id = u.id ORDER BY p.id DESC");
$orders = $conn->query("SELECT o.*, u.full_name FROM orders o 
                        JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>PetCare Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --p: #000000; --s-w: 260px; --grad: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        body { font-family: 'Inter', sans-serif; background: var(--bs-body-bg); }
        .sidebar { width: var(--s-w); height: 100vh; position: fixed; background: var(--bs-tertiary-bg); border-right: 1px solid var(--bs-border-color); padding: 2rem 1rem; z-index: 1000; }
        .main { margin-left: var(--s-w); padding: 3rem; min-height: 100vh; }
        .nav-btn { color: var(--bs-secondary-color); padding: 14px 20px; border-radius: 15px; margin-bottom: 8px; display: block; border: none; background: transparent; width: 100%; text-align: left; transition: 0.3s; cursor: pointer; }
        .nav-btn.active { background: var(--grad); color: white !important; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }
        .pane { display: none; }
        .pane.active { display: block; animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card-pro { border-radius: 20px; overflow: hidden; }
    </style>
</head>
<body>

<aside class="sidebar">
    <h4 class="fw-bold mb-5 px-3"><i class="fa-solid fa-shield-halved text-primary me-2"></i>PetCare Admin</h4>
    <nav>
        <button class="nav-btn active" onclick="nav('v-overview', this)"><i class="fa-solid fa-home me-3"></i>Overview</button>
        <button class="nav-btn" onclick="nav('v-users', this)"><i class="fa-solid fa-users me-3"></i>Users</button>
        <button class="nav-btn" onclick="nav('v-vets', this)"><i class="fa-solid fa-user-doctor me-3"></i>Veterinarians</button>
        <button class="nav-btn" onclick="nav('v-appointments', this)"><i class="fa-solid fa-calendar-check me-3"></i>Appointments</button>
        <button class="nav-btn" onclick="nav('v-listings', this)"><i class="fa-solid fa-tags me-3"></i>Marketplace 
            <?php if($pending_list > 0): ?><span class="badge bg-warning ms-2"><?= $pending_list ?></span><?php endif; ?>
        </button>
        <button class="nav-btn" onclick="nav('v-orders', this)"><i class="fa-solid fa-shopping-cart me-3"></i>Orders 
            <?php if($pending_orders > 0): ?><span class="badge bg-danger ms-2"><?= $pending_orders ?></span><?php endif; ?>
        </button>
    </nav>
    <div class="position-absolute bottom-0 start-0 w-100 p-4">
        <a href="../includes/auth_handler.php?action=logout" class="btn btn-danger w-100 rounded-pill">Logout</a>
    </div>
</aside>

<main class="main">
    <?php if($status): $s = explode('|', $status); ?>
        <div class="alert alert-<?= $s[0] ?> alert-dismissible fade show rounded-4"><?= $s[1] ?></div>
    <?php endif; ?>

    <h1 class="fw-bold mb-4" id="view-title">Overview</h1>

    <div id="v-overview" class="pane active">
        <div class="row g-4 mb-5">
            <div class="col-md-3"><div class="card-pro p-4 bg-white shadow-sm border-start border-4 border-primary"><h2><?= $user_count ?></h2><p class="text-muted fw-bold">Total Users</p></div></div>
            <div class="col-md-3"><div class="card-pro p-4 bg-white shadow-sm border-start border-4 border-success"><h2><?= $vet_count ?></h2><p class="text-muted fw-bold">Total Vets</p></div></div>
            <div class="col-md-3"><div class="card-pro p-4 bg-white shadow-sm border-start border-4 border-warning"><h2><?= $total_apts ?></h2><p class="text-muted fw-bold">Appointments</p></div></div>
            <div class="col-md-3"><div class="card-pro p-4 bg-white shadow-sm border-start border-4 border-danger"><h2><?= $pending_orders ?></h2><p class="text-muted fw-bold">Pending Orders</p></div></div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card-pro p-4 bg-white shadow-sm">
                    <h5 class="fw-bold mb-4">Platform Growth (Users)</h5>
                    <canvas id="growthChart" style="height: 250px;"></canvas>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card-pro p-4 bg-white shadow-sm h-100">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-bullhorn text-warning me-2"></i>Global Announcement</h5>
                    <p class="text-muted small mb-4">This message will appear as a banner on the main page for all users.</p>
                    <form method="POST">
                        <input type="hidden" name="action" value="save_announcement">
                        <textarea name="announcement_msg" class="form-control bg-light p-3 border-0 mb-3 rounded-4" rows="4" placeholder="Enter announcement text..."><?= htmlspecialchars($current_announcement) ?></textarea>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Publish Announcement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="v-users" class="pane">
        <h2 class="fw-bold mb-4">All Users</h2>
        <div class="row g-4">
            <?php while($u = $users->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4">
                    <h5><?= htmlspecialchars($u['full_name']) ?></h5>
                    <p><?= htmlspecialchars($u['email']) ?></p>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem(<?= $u['id'] ?>, 'user')">Delete</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-vets" class="pane">
        <h2 class="fw-bold mb-4">All Veterinarians</h2>
        <div class="row g-4">
            <?php while($v = $vets->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            Dr. <?= htmlspecialchars($v['full_name']) ?>
                            <?php if(isset($v['is_verified']) && $v['is_verified']): ?>
                                <i class="fa-solid fa-circle-check text-primary ms-1" title="Verified Vet"></i>
                            <?php endif; ?>
                        </h5>
                        <p class="text-muted mb-0"><?= htmlspecialchars($v['email']) ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if(isset($v['is_verified'])): ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="verify_vet">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <input type="hidden" name="val" value="<?= $v['is_verified'] ? 0 : 1 ?>">
                                <button type="submit" class="btn btn-sm <?= $v['is_verified'] ? 'btn-outline-warning' : 'btn-success' ?>">
                                    <?= $v['is_verified'] ? 'Revoke' : 'Verify' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem(<?= $v['id'] ?>, 'user')"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-appointments" class="pane">
        <h2 class="fw-bold mb-4">All Appointments</h2>
        <div class="row g-4">
            <?php while($a = $appointments->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4">
                    <h5><?= htmlspecialchars($a['owner']) ?> → Dr. <?= htmlspecialchars($a['vet_name']) ?></h5>
                    <p><strong>Pet:</strong> <?= htmlspecialchars($a['pet_name']) ?></p>
                    <span class="badge bg-<?= $a['status']=='accepted'?'success':($a['status']=='pending'?'warning':'danger') ?>">
                        <?= strtoupper($a['status']) ?>
                    </span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-listings" class="pane">
        <h2 class="fw-bold mb-4">Marketplace Listings</h2>
        <div class="row g-4">
            <?php while($p = $products->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card-pro shadow-sm">
                    <img src="../assets/uploads/<?= htmlspecialchars($p['image'] ?? 'default.png') ?>" class="w-100" style="height:180px;object-fit:cover;">
                    <div class="p-3">
                        <h6><?= htmlspecialchars($p['name']) ?></h6>
                        <p class="text-primary">Rs. <?= number_format($p['price']) ?></p>
                        <small>Seller: <?= htmlspecialchars($p['seller']) ?></small><br><br>
                        <span class="badge bg-<?= ($p['status']??'pending')=='approved'?'success':'warning' ?>"><?= strtoupper($p['status']??'pending') ?></span>
                        <?php if(($p['status']??'pending') == 'pending'): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="approve_listing">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm mt-2">Approve</button>
                            </form>
                        <?php endif; ?>
                        <button class="btn btn-danger btn-sm mt-2" onclick="deleteItem(<?= $p['id'] ?>, 'product')">Delete</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-orders" class="pane">
        <h2 class="fw-bold mb-4">All Orders</h2>
        <div class="row g-4">
            <?php while($o = $orders->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4">
                    <h5>#<?= $o['id'] ?> - <?= htmlspecialchars($o['full_name']) ?></h5>
                    <p class="fw-bold">Rs. <?= number_format($o['total_amount'] ?? 0) ?></p>
                    <span class="badge bg-<?= $o['status']=='pending'?'warning':'success' ?>"><?= strtoupper($o['status']) ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function nav(id, btn) {
    document.querySelectorAll('.pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    btn.classList.add('active');
    document.getElementById('view-title').innerText = btn.innerText.trim();
}

function deleteItem(id, type) {
    if(confirm("Delete this record permanently?")) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete_item">
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="type" value="${type}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('growthChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Users',
                    data: [12, 19, 30, 45, 60, <?= $user_count + 60 ?>],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
});
</script>
</body>
</html>