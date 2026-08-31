<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$conn = new mysqli('localhost', 'pet_user', 'pet_password', 'pet_db');
if ($conn->connect_error) { 
    die("Connection Failed: " . $conn->connect_error); 
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vet') { 
    header("Location: ../login.php"); 
    exit(); 
}

$vet_id = $_SESSION['user_id'];

$status = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] == 'accept_appointment') {
        $apt_id = intval($_POST['apt_id']);
        $meeting_link = mysqli_real_escape_string($conn, $_POST['meeting_link']);
        $meeting_time = mysqli_real_escape_string($conn, $_POST['meeting_time']);
        $meeting_pwd = mysqli_real_escape_string($conn, $_POST['meeting_pwd']);
        $meeting_desc = mysqli_real_escape_string($conn, $_POST['meeting_desc']);

        $stmt = $conn->prepare("UPDATE appointments SET status='accepted', meeting_link=?, meeting_time=?, meeting_pwd=?, meeting_desc=? WHERE id=? AND vet_id=?");
        $stmt->bind_param("ssssii", $meeting_link, $meeting_time, $meeting_pwd, $meeting_desc, $apt_id, $vet_id);
        if($stmt->execute()) $status = "success|Appointment Accepted!";
    }

    if ($_POST['action'] == 'reject_appointment') {
        $apt_id = intval($_POST['apt_id']);
        $conn->query("UPDATE appointments SET status='rejected' WHERE id=$apt_id AND vet_id=$vet_id");
        $status = "success|Appointment Rejected.";
    }

    if ($_POST['action'] == 'upd_vet_profile') {
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $spec = mysqli_real_escape_string($conn, $_POST['spec']);
        $fee = floatval($_POST['fee']);
        $payment_info = mysqli_real_escape_string($conn, $_POST['payment_info']);
        $exp = mysqli_real_escape_string($conn, $_POST['exp']);
        $is_avail = isset($_POST['is_available']) ? 1 : 0;
        $avail_days = isset($_POST['days']) ? implode(',', $_POST['days']) : '';

        $conn->query("UPDATE users SET full_name='$full_name', email='$email' WHERE id=$vet_id");
        $conn->query("UPDATE vet_profiles SET specialization='$spec', consultation_fee=$fee, payment_info='$payment_info', experience_details='$exp', is_available=$is_avail, available_days='$avail_days' WHERE user_id=$vet_id");
        $status = "success|Profile Updated Successfully!";
    }

    if ($_POST['action'] == 'write_prescription') {
        $apt_id = intval($_POST['apt_id']);
        $user_id = intval($_POST['patient_id']);
        $meds = mysqli_real_escape_string($conn, $_POST['medications']);
        $instr = mysqli_real_escape_string($conn, $_POST['instructions']);
        
        $stmt = $conn->prepare("INSERT INTO e_prescriptions (appointment_id, vet_id, user_id, medications, instructions) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $apt_id, $vet_id, $user_id, $meds, $instr);
        if($stmt->execute()) $status = "success|Prescription Sent to Patient!";
    }
}

$pending_count = $conn->query("SELECT COUNT(*) FROM appointments WHERE vet_id=$vet_id AND status='pending'")->fetch_row()[0];
$active_count = $conn->query("SELECT COUNT(*) FROM appointments WHERE vet_id=$vet_id AND status='accepted'")->fetch_row()[0];

$rating_res = $conn->query("SELECT AVG(rating) FROM vet_reviews WHERE vet_id=$vet_id");
$avg_rating = $rating_res->fetch_row()[0] ? round($rating_res->fetch_row()[0], 1) : 0.0;

$pending_requests = $conn->query("SELECT a.*, u.full_name, u.phone, u.email FROM appointments a 
                                  JOIN users u ON a.user_id = u.id 
                                  WHERE a.vet_id = $vet_id AND a.status = 'pending'");

$active_apts = $conn->query("SELECT a.*, u.full_name, u.phone FROM appointments a 
                             JOIN users u ON a.user_id = u.id 
                             WHERE a.vet_id = $vet_id AND a.status = 'accepted' ORDER BY a.id DESC");

$reviews = $conn->query("SELECT r.*, u.full_name FROM vet_reviews r 
                         JOIN users u ON r.user_id = u.id 
                         WHERE r.vet_id = $vet_id ORDER BY r.created_at DESC");

$prof = $conn->query("SELECT u.*, vp.* FROM users u 
                      LEFT JOIN vet_profiles vp ON u.id = vp.user_id 
                      WHERE u.id = $vet_id")->fetch_assoc();

$presc_check = $conn->query("SELECT appointment_id FROM e_prescriptions WHERE vet_id = $vet_id");
$presc_map = [];
while($pr = $presc_check->fetch_assoc()) {
    $presc_map[$pr['appointment_id']] = true;
}

$earnings_res = $conn->query("SELECT COUNT(*) as cnt FROM appointments WHERE vet_id=$vet_id AND status='accepted'");
$total_earnings = ($earnings_res->fetch_assoc()['cnt'] ?? 0) * (float)($prof['consultation_fee'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>VetPanel - PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --p: #0d9488; --s-w: 260px; --grad: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); }
        body { font-family: 'Inter', sans-serif; background: var(--bs-body-bg); }
        .sidebar { width: var(--s-w); height: 100vh; position: fixed; background: var(--bs-tertiary-bg); border-right: 1px solid var(--bs-border-color); padding: 2rem 1rem; z-index: 1000; }
        .main { margin-left: var(--s-w); padding: 3rem; min-height: 100vh; }
        .nav-btn { color: var(--bs-secondary-color); padding: 14px 20px; border-radius: 15px; margin-bottom: 8px; display: block; border: none; background: transparent; width: 100%; text-align: left; transition: 0.3s; cursor: pointer; }
        .nav-btn.active { background: var(--grad); color: white !important; box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3); }
        .pane { display: none; }
        .pane.active { display: block; animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card-pro { border-radius: 25px; overflow: hidden; }
        .market-img { height: 200px; object-fit: cover; }
    </style>
</head>
<body>

<aside class="sidebar">
    <h4 class="fw-bold mb-5 px-3"><i class="fa-solid fa-hand-holding-medical text-success me-2"></i>VetPanel</h4>
    <nav>
        <button class="nav-btn active" onclick="nav('v-overview', this)"><i class="fa-solid fa-chart-line me-3"></i>Dashboard</button>
        <button class="nav-btn" onclick="nav('v-requests', this)"><i class="fa-solid fa-bell me-3"></i>New Requests 
            <?php if($pending_count > 0): ?><span class="badge bg-danger ms-2"><?= $pending_count ?></span><?php endif; ?>
        </button>
        <button class="nav-btn" onclick="nav('v-active', this)"><i class="fa-solid fa-calendar-check me-3"></i>Active Patients</button>
        <button class="nav-btn" onclick="nav('v-profile', this)"><i class="fa-solid fa-user-doctor me-3"></i>My Profile</button>
        <button class="nav-btn" onclick="nav('v-reviews', this)"><i class="fa-solid fa-star-half-stroke me-3"></i>Reviews</button>
    </nav>
    <div class="position-absolute bottom-0 start-0 w-100 p-4">
        <a href="../logout.php" class="btn btn-danger w-100 rounded-pill">Logout</a>
    </div>
</aside>

<main class="main">
    <?php if($status): $s = explode('|', $status); ?>
        <div class="alert alert-<?= $s[0] ?> alert-dismissible fade show rounded-4"><?= $s[1] ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold" id="view-title">Dashboard</h1>
    </div>

    <div id="v-overview" class="pane active">
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card-pro p-4 border-start border-4 border-success bg-white shadow-sm">
                    <small class="fw-bold text-muted">Active Patients</small>
                    <h2 class="fw-bold text-success"><?= $active_count ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-pro p-4 border-start border-4 border-warning bg-white shadow-sm">
                    <small class="fw-bold text-muted">New Requests</small>
                    <h2 class="fw-bold text-warning"><?= $pending_count ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-pro p-4 border-start border-4 border-teal bg-white shadow-sm">
                    <small class="fw-bold text-muted">Avg Rating</small>
                    <h2 class="fw-bold"><?= $avg_rating ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-pro p-4 border-start border-4 border-primary bg-white shadow-sm">
                    <small class="fw-bold text-muted">Total Earnings</small>
                    <h2 class="fw-bold text-primary">Rs. <?= number_format($total_earnings) ?></h2>
                </div>
            </div>
        </div>
        
        <div class="card-pro p-4 bg-white shadow-sm mb-5">
            <h4 class="fw-bold mb-4">Consultation & Earnings Trend</h4>
            <canvas id="earningsChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <div id="v-requests" class="pane">
        <h2 class="fw-bold mb-4">Pending Appointment Requests</h2>
        <div class="row g-4">
            <?php if($pending_requests->num_rows == 0): ?>
                <div class="col-12 text-center py-5 text-muted">No pending requests.</div>
            <?php else: while($r = $pending_requests->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4 shadow-sm">
                    <h5><?= htmlspecialchars($r['full_name']) ?></h5>
                    <p><strong>Pet:</strong> <?= htmlspecialchars($r['pet_name']) ?> (<?= htmlspecialchars($r['pet_age'] ?? 'N/A') ?>)</p>
                    <p><strong>Reason:</strong> <?= htmlspecialchars($r['reason']) ?></p>
                    <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#reviewModal<?= $r['id'] ?>">Process Request</button>
                </div>
            </div>

            <div class="modal fade" id="reviewModal<?= $r['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content rounded-4">
                        <form method="POST">
                            <input type="hidden" name="action" value="accept_appointment">
                            <input type="hidden" name="apt_id" value="<?= $r['id'] ?>">
                            <div class="modal-body p-4">
                                <h4>Approve Appointment - <?= htmlspecialchars($r['full_name']) ?></h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Meeting Link</label>
                                        <input type="url" name="meeting_link" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Meeting Time</label>
                                        <input type="text" name="meeting_time" class="form-control" placeholder="e.g. 5:00 PM" required>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>Password (Optional)</label>
                                        <input type="text" name="meeting_pwd" class="form-control">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label>Instructions</label>
                                        <textarea name="meeting_desc" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success rounded-pill px-4">Approve & Send</button>
                                <button type="button" class="btn btn-danger rounded-pill px-4" onclick="rejectAppointment(<?= $r['id'] ?>)">Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>

    <div id="v-active" class="pane">
        <h2 class="fw-bold mb-4">Confirmed Appointments</h2>
        <div class="row g-4">
            <?php while($row = $active_apts->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4 h-100 d-flex flex-column shadow-sm bg-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($row['full_name']) ?></h5>
                            <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></small>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <p class="mb-1"><strong>Pet:</strong> <?= htmlspecialchars($row['pet_name']) ?></p>
                        <p class="mb-1"><strong>Issue:</strong> <?= htmlspecialchars($row['reason']) ?></p>
                        <p class="mb-0 text-primary fw-bold"><i class="fa-solid fa-clock me-2"></i><?= htmlspecialchars($row['meeting_time']) ?></p>
                    </div>
                    <div class="mt-auto d-flex gap-2 flex-wrap">
                        <?php if($row['meeting_link']): ?>
                            <a href="<?= htmlspecialchars($row['meeting_link']) ?>" target="_blank" class="btn btn-primary flex-grow-1 fw-bold rounded-pill"><i class="fa-solid fa-video me-2"></i>Join Call</a>
                        <?php endif; ?>
                        
                        <?php if(!isset($presc_map[$row['id']])): ?>
                            <button class="btn btn-outline-success flex-grow-1 fw-bold rounded-pill" onclick="openPrescription(<?= $row['id'] ?>, <?= $row['user_id'] ?>, '<?= addslashes($row['full_name']) ?>')"><i class="fa-solid fa-pen-to-square me-2"></i>Write Prescription</button>
                        <?php else: ?>
                            <button class="btn btn-light text-success flex-grow-1 fw-bold rounded-pill" disabled><i class="fa-solid fa-check-circle me-2"></i>Prescribed</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-profile" class="pane">
        <h2 class="fw-bold mb-4">My Professional Profile</h2>
        <div class="card-pro p-5">
            <form method="POST">
                <input type="hidden" name="action" value="upd_vet_profile">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label>Full Name (Dr.)</label>
                        <input type="text" name="full_name" class="form-control p-3" value="<?= htmlspecialchars($prof['full_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control p-3" value="<?= htmlspecialchars($prof['email'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Specialization</label>
                        <input type="text" name="spec" class="form-control p-3" value="<?= htmlspecialchars($prof['specialization'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Consultation Fee (PKR)</label>
                        <input type="number" name="fee" class="form-control p-3" value="<?= htmlspecialchars($prof['consultation_fee'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label>Payment Info (JazzCash / EasyPaisa)</label>
                        <input type="text" name="payment_info" class="form-control p-3" value="<?= htmlspecialchars($prof['payment_info'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label>Experience / Biography</label>
                        <textarea name="exp" class="form-control p-3" rows="5"><?= htmlspecialchars($prof['experience_details'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="p-4 bg-light rounded-4 border">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-calendar-alt text-primary me-2"></i>Availability Settings</h5>
                            <div class="form-check form-switch fs-5 mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_available" id="isAvail" <?= ($prof['is_available'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="isAvail">Accepting New Appointments</label>
                            </div>
                            <?php $days = explode(',', $prof['available_days'] ?? 'Mon,Tue,Wed,Thu,Fri'); ?>
                            <label class="fw-bold mb-2">Available Days</label>
                            <div class="d-flex gap-3 flex-wrap">
                                <?php foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="days[]" value="<?= $d ?>" id="day<?= $d ?>" <?= in_array($d, $days) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="day<?= $d ?>"><?= $d ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark px-5 py-3 rounded-pill fw-bold w-100">Save All Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="v-reviews" class="pane">
        <h2 class="fw-bold mb-4">Client Reviews</h2>
        <div class="row g-4">
            <?php while($rev = $reviews->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4">
                    <div class="d-flex justify-content-between">
                        <h6><?= htmlspecialchars($rev['full_name']) ?></h6>
                        <div class="text-warning">
                            <?php for($i=1; $i<=5; $i++) echo $i <= $rev['rating'] ? '★' : '☆'; ?>
                        </div>
                    </div>
                    <p class="mt-2">"<?= htmlspecialchars($rev['comment']) ?>"</p>
                </div>
            </div>
            <?php endwhile; if($reviews->num_rows == 0) echo "<p class='text-muted'>No reviews yet.</p>"; ?>
        </div>
    </div>
</main>

<!-- PRESCRIPTION MODAL -->
<div class="modal fade" id="prescModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-5">
            <form method="POST">
                <input type="hidden" name="action" value="write_prescription">
                <input type="hidden" name="apt_id" id="presc_apt_id">
                <input type="hidden" name="patient_id" id="presc_patient_id">
                <div class="modal-header border-0 pb-0">
                    <h4 class="fw-bold text-success"><i class="fa-solid fa-pen-to-square me-2"></i>Write E-Prescription</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Patient: <strong id="presc_patient_name" class="text-dark"></strong></p>
                    <div class="mb-3">
                        <label class="fw-bold text-success"><i class="fa-solid fa-pills me-2"></i>Medications & Dosage</label>
                        <textarea name="medications" class="form-control bg-light p-3 border-success-subtle" rows="4" placeholder="e.g. Amoxicillin 50mg, twice a day for 7 days" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="fw-bold text-warning"><i class="fa-solid fa-list-check me-2"></i>Follow-up & Instructions</label>
                        <textarea name="instructions" class="form-control bg-light p-3 border-warning-subtle" rows="3" placeholder="Dietary restrictions, next visit details..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold">Send Prescription to Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function nav(id, btn) {
    document.querySelectorAll('.pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    btn.classList.add('active');
    document.getElementById('view-title').innerText = btn.innerText.trim();
}

function rejectAppointment(id) {
    if(confirm("Reject this appointment?")) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `<input type="hidden" name="action" value="reject_appointment"><input type="hidden" name="apt_id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

function openPrescription(aptId, patientId, patientName) {
    document.getElementById('presc_apt_id').value = aptId;
    document.getElementById('presc_patient_id').value = patientId;
    document.getElementById('presc_patient_name').innerText = patientName;
    new bootstrap.Modal('#prescModal').show();
}

// Chart.js init
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('earningsChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Appointments',
                    data: [12, 19, 15, <?= $active_count ?>],
                    backgroundColor: 'rgba(13, 148, 136, 0.7)',
                    borderColor: '#0d9488',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
});
</script>
</body>
</html>