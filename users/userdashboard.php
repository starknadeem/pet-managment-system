<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$conn = new mysqli('localhost', 'pet_user', 'pet_password', 'pet_db');
if ($conn->connect_error) { 
    die("Connection Failed: " . $conn->connect_error); 
}

if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}
$user_id = $_SESSION['user_id'];

$status = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] == 'add_listing') {
        $n = mysqli_real_escape_string($conn, $_POST['p_name']);
        $p_price = floatval($_POST['p_price']);
        $d = mysqli_real_escape_string($conn, $_POST['p_desc']);
        $pid = isset($_POST['p_id']) ? intval($_POST['p_id']) : 0;
        $up = "../assets/uploads/";
        $files = ['image'=>'default.png', 'img2'=>'', 'img3'=>'', 'img4'=>'', 'img5'=>'', 'video'=>''];

        if($pid > 0) {
            $existing = $conn->query("SELECT * FROM products WHERE id=$pid AND seller_id=$user_id")->fetch_assoc();
            if($existing) $files = $existing;
        }

        for($i=1; $i<=5; $i++) {
            $key = ($i==1) ? "p_img" : "p_img$i";
            if(!empty($_FILES[$key]['name'])) {
                $fname = time()."_$i"."_".$_FILES[$key]['name'];
                move_uploaded_file($_FILES[$key]['tmp_name'], $up.$fname);
                $db_key = ($i==1) ? "image" : "img$i";
                $files[$db_key] = $fname;
            }
        }
        if(!empty($_FILES['p_vid']['name'])) {
            $vname = time()."_vid_".$_FILES['p_vid']['name'];
            move_uploaded_file($_FILES['p_vid']['tmp_name'], $up.$vname);
            $files['video'] = $vname;
        }

        if($pid > 0) {
            $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=?, img2=?, img3=?, img4=?, img5=?, video=? WHERE id=? AND seller_id=?");
            $stmt->bind_param("sdsssssssii", $n, $p_price, $d, $files['image'], $files['img2'], $files['img3'], $files['img4'], $files['img5'], $files['video'], $pid, $user_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO products (seller_id, name, price, description, image, img2, img3, img4, img5, video) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isdsssssss", $user_id, $n, $p_price, $d, $files['image'], $files['img2'], $files['img3'], $files['img4'], $files['img5'], $files['video']);
        }
        if($stmt->execute()) $status = "success|Listing saved successfully!";
    }

    if ($_POST['action'] == 'del_listing') {
        $pid = intval($_POST['p_id']);
        $conn->query("DELETE FROM products WHERE id=$pid AND seller_id=$user_id");
        $status = "success|Listing deleted permanently.";
    }

    if ($_POST['action'] == 'upd_profile') {
        $addr = mysqli_real_escape_string($conn, $_POST['address']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $conn->query("UPDATE users SET address='$addr', phone='$phone' WHERE id=$user_id");
        $status = "success|Profile updated successfully!";
    }

    if ($_POST['action'] == 'book_appointment') {
        $vet_id = intval($_POST['vet_id']);
        $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
        $pet_age = mysqli_real_escape_string($conn, $_POST['pet_age']);
        $reason = mysqli_real_escape_string($conn, $_POST['reason']);

        $stmt = $conn->prepare("INSERT INTO appointments 
            (user_id, vet_id, pet_name, pet_age, reason, issue_description) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $user_id, $vet_id, $pet_name, $pet_age, $reason, $reason);
        if($stmt->execute()) {
            $status = "success|Appointment request sent to veterinarian!";
        }
    }
}

$me = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$vets = $conn->query("SELECT * FROM users WHERE role = 'vet'");
$all_market = $conn->query("SELECT p.*, u.full_name as s_name, u.phone as s_phone, u.city as s_city 
                           FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.id DESC");
$my_market = $conn->query("SELECT * FROM products WHERE seller_id = $user_id ORDER BY id DESC");
$my_appointments = $conn->query("SELECT a.*, u.full_name as vet_name 
                                 FROM appointments a JOIN users u ON a.vet_id = u.id 
                                 WHERE a.user_id = $user_id ORDER BY a.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>PetCare | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --p: #6366f1; --s-w: 260px; --grad: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
        body { font-family: 'Inter', sans-serif; background: var(--bs-body-bg); }
        .sidebar { width: var(--s-w); height: 100vh; position: fixed; background: var(--bs-tertiary-bg); border-right: 1px solid var(--bs-border-color); padding: 2rem 1rem; z-index: 1000; }
        .main { margin-left: var(--s-w); padding: 3rem; min-height: 100vh; }
        .nav-btn { color: var(--bs-secondary-color); padding: 14px 20px; border-radius: 15px; margin-bottom: 8px; display: block; border: none; background: transparent; width: 100%; text-align: left; transition: 0.3s; cursor: pointer; }
        .nav-btn.active { background: var(--grad); color: white !important; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }
        .pane { display: none; }
        .pane.active { display: block; animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card-pro { background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 25px; overflow: hidden; transition: 0.3s; cursor: pointer; }
        .card-pro:hover { transform: translateY(-8px); border-color: var(--p); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .market-img { height: 200px; object-fit: cover; }
        .detail-img { max-height: 420px; object-fit: contain; }
    </style>
</head>
<body>

<aside class="sidebar">
    <h4 class="fw-bold mb-5 px-3"><i class="fa-solid fa-paw text-primary me-2"></i>PetCare</h4>
    <nav>
        <button class="nav-btn active" onclick="nav('v-market', this)"><i class="fa-solid fa-earth-asia me-3"></i>Marketplace</button>
        <button class="nav-btn" onclick="nav('v-my', this)"><i class="fa-solid fa-tags me-3"></i>My Listings</button>
        <button class="nav-btn" onclick="nav('v-vets', this)"><i class="fa-solid fa-user-doctor me-3"></i>Vet Network</button>
        <button class="nav-btn" onclick="nav('v-appointments', this)"><i class="fa-solid fa-calendar-check me-3"></i>My Appointments</button>
        <button class="nav-btn" onclick="nav('v-settings', this)"><i class="fa-solid fa-gear me-3"></i>Settings</button>
    </nav>
    <div class="position-absolute bottom-0 start-0 w-100 p-4">
        <a href="../logout.php" class="btn btn-danger w-100 rounded-pill">Logout</a>
    </div>
</aside>

<main class="main">
    <?php if($status): $s = explode('|', $status); ?>
        <div class="alert alert-<?= $s[0] ?> alert-dismissible fade show rounded-4 shadow mb-4"><?= $s[1] ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold" id="view-title">Marketplace</h1>
        <button class="btn btn-primary rounded-pill px-4 shadow" onclick="openAddModal()">+ New Post</button>
    </div>

    <div id="v-market" class="pane active">
        <div class="row g-4">
            <?php while($p = $all_market->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card-pro h-100 shadow-sm" onclick='showProductDetail(<?= json_encode($p) ?>)'>
                    <img src="../assets/uploads/<?= htmlspecialchars($p['image']) ?>" class="market-img w-100">
                    <div class="p-3">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold"><?= htmlspecialchars($p['name']) ?></h5>
                            <span class="text-primary fw-bold">Rs. <?= number_format($p['price']) ?></span>
                        </div>
                        <small class="text-muted"><?= htmlspecialchars($p['s_city']) ?></small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-my" class="pane">
        <div class="row g-4">
            <?php while($mp = $my_market->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card-pro h-100 shadow-sm">
                    <img src="../assets/uploads/<?= htmlspecialchars($mp['image']) ?>" class="market-img w-100">
                    <div class="p-3">
                        <h5 class="fw-bold"><?= htmlspecialchars($mp['name']) ?></h5>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-light btn-sm flex-grow-1 rounded-pill" onclick='editListing(<?= json_encode($mp) ?>)'>Edit</button>
                            <form method="POST" class="flex-grow-1">
                                <input type="hidden" name="action" value="del_listing">
                                <input type="hidden" name="p_id" value="<?= $mp['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-vets" class="pane">
        <h2 class="fw-bold mb-4">Certified Veterinarians</h2>
        <div class="row g-4">
            <?php while($v = $vets->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle p-4 me-4"><i class="fa-solid fa-user-md fa-2x"></i></div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold"><?= htmlspecialchars($v['full_name']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($v['city'] ?? 'Not specified') ?></p>
                            <button class="btn btn-primary rounded-pill px-4" onclick="showVetProfile(<?= $v['id'] ?>, '<?= addslashes($v['full_name']) ?>')">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="v-appointments" class="pane">
        <h2 class="fw-bold mb-4">My Appointments</h2>
        <div class="row g-4">
            <?php if($my_appointments->num_rows == 0): ?>
                <div class="col-12 text-center py-5 text-muted">No appointments found.</div>
            <?php else: while($apt = $my_appointments->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="card-pro p-4">
                    <h5><?= htmlspecialchars($apt['vet_name']) ?></h5>
                    <p><strong>Pet:</strong> <?= htmlspecialchars($apt['pet_name']) ?> (<?= htmlspecialchars($apt['pet_age'] ?? 'N/A') ?>)</p>
                    <p><strong>Reason:</strong> <?= htmlspecialchars($apt['reason']) ?></p>
                    <span class="badge bg-<?= $apt['status']=='accepted' ? 'success' : ($apt['status']=='rejected' ? 'danger' : 'warning') ?>">
                        <?= strtoupper($apt['status']) ?>
                    </span>
                    <?php if($apt['status']=='accepted' && $apt['meeting_time']): ?>
                        <p class="mt-2"><i class="fa-solid fa-clock text-primary"></i> <?= htmlspecialchars($apt['meeting_time']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>

    <div id="v-settings" class="pane">
        <h2 class="fw-bold mb-4">Profile Settings</h2>
        <div class="card-pro p-5">
            <form method="POST">
                <input type="hidden" name="action" value="upd_profile">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small fw-bold">Full Name</label>
                        <input type="text" class="form-control p-3 bg-light" value="<?= htmlspecialchars($me['full_name']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Email</label>
                        <input type="text" class="form-control p-3 bg-light" value="<?= htmlspecialchars($me['email']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control p-3" value="<?= htmlspecialchars($me['phone'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold">Address</label>
                        <textarea name="address" class="form-control p-3 rounded-4"><?= htmlspecialchars($me['address'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark px-5 py-3 rounded-pill">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content rounded-5 p-4" id="postForm">
            <input type="hidden" name="action" value="add_listing">
            <input type="hidden" name="p_id" id="form-p-id">
            <h4 class="fw-bold mb-4" id="form-title">Create New Listing</h4>
            <div class="row g-3">
                <div class="col-md-8"><input type="text" name="p_name" id="form-name" class="form-control p-3" placeholder="Item Name" required></div>
                <div class="col-md-4"><input type="number" name="p_price" id="form-price" class="form-control p-3" placeholder="Price" required></div>
                <div class="col-12"><textarea name="p_desc" id="form-desc" class="form-control p-3" rows="4" placeholder="Description..."></textarea></div>
                <div class="col-md-4"><label>Main Image</label><input type="file" name="p_img" class="form-control"></div>
                <div class="col-md-4"><label>Photo 2</label><input type="file" name="p_img2" class="form-control"></div>
                <div class="col-md-4"><label>Video</label><input type="file" name="p_vid" class="form-control" accept="video/*"></div>
                <div class="col-12 mt-4"><button type="submit" class="btn btn-primary w-100 py-3 rounded-pill">Save Listing</button></div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h4 class="fw-bold" id="detail-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detail-body"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="vetModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-5">
            <div class="modal-header">
                <h4 class="fw-bold" id="vet-modal-name"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vet-modal-body"></div>
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

function openAddModal() {
    document.getElementById('postForm').reset();
    document.getElementById('form-p-id').value = "";
    document.getElementById('form-title').innerText = "Create New Listing";
    new bootstrap.Modal('#addModal').show();
}

function editListing(p) {
    document.getElementById('form-p-id').value = p.id;
    document.getElementById('form-name').value = p.name;
    document.getElementById('form-price').value = p.price;
    document.getElementById('form-desc').value = p.description || '';
    document.getElementById('form-title').innerText = "Edit Listing";
    new bootstrap.Modal('#addModal').show();
}

function showProductDetail(p) {
    let html = `
        <h3 class="text-primary mb-3">Rs. ${Number(p.price).toLocaleString('en-PK')}</h3>
        <p><strong>Seller:</strong> ${p.s_name} | <strong>Phone:</strong> ${p.s_phone || 'N/A'}</p>
        <p><strong>Location:</strong> ${p.s_city}</p>
        <hr>`;

    // All Images
    const imgs = ['image','img2','img3','img4','img5'];
    imgs.forEach(key => {
        if(p[key] && p[key] !== '') {
            html += `<img src="../assets/uploads/${p[key]}" class="detail-img img-fluid rounded-3 mb-3 d-block">`;
        }
    });

    if(p.video && p.video !== '') {
        html += `<video controls class="w-100 rounded-3 mb-3"><source src="../assets/uploads/${p.video}" type="video/mp4"></video>`;
    }

    html += `<h5 class="mt-4">Description</h5><p>${p.description || 'No description available.'}</p>`;

    document.getElementById('detail-title').innerText = p.name;
    document.getElementById('detail-body').innerHTML = html;
    new bootstrap.Modal('#productDetailModal').show();
}

function showVetProfile(vetId, vetName) {
    document.getElementById('vet-modal-name').innerText = "Book Appointment with " + vetName;
    document.getElementById('vet-modal-body').innerHTML = `
        <form method="POST">
            <input type="hidden" name="action" value="book_appointment">
            <input type="hidden" name="vet_id" value="${vetId}">
            <div class="mb-3">
                <label class="fw-bold">Pet Name</label>
                <input type="text" name="pet_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="fw-bold">Pet Age</label>
                <input type="text" name="pet_age" class="form-control" placeholder="e.g. 2 years" required>
            </div>
            <div class="mb-3">
                <label class="fw-bold">Reason / Issue</label>
                <textarea name="reason" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill">Send Request</button>
        </form>`;
    new bootstrap.Modal('#vetModal').show();
}
</script>
</body>
</html>