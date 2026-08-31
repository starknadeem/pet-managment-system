<?php
include 'includes/header.php';
include 'config/db.php';

// Fetch adoption listings from marketplace (products with category = 'adoption' or all for now)
// We'll show all products and let users filter. For adoption, if a 'category' column exists we use it.
// Safe fallback: show all products
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$species = isset($_GET['species']) ? trim($_GET['species']) : '';

$sql = "SELECT p.*, u.full_name as s_name, u.phone as s_phone, u.city as s_city 
        FROM products p JOIN users u ON p.seller_id = u.id 
        WHERE (p.status IS NULL OR p.status = 'approved' OR p.status = 'pending')";

if ($search !== '') {
    $safe = $pdo->quote('%' . $search . '%');
    $sql .= " AND (p.name LIKE $safe OR p.description LIKE $safe)";
}
if ($species !== '') {
    $safe = $pdo->quote('%' . $species . '%');
    $sql .= " AND (p.name LIKE $safe OR p.description LIKE $safe)";
}
$sql .= " ORDER BY p.id DESC LIMIT 60";

$pets = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .adoption-hero { padding: 110px 0 70px; }
    .adoption-hero h1 { font-size: 3.6rem; font-weight: 800; letter-spacing: -2px; line-height: 1.1; }

    .filter-bar { background: var(--card-bg); border: 1px solid var(--border); border-radius: 20px; padding: 24px 28px; margin-bottom: 40px; }
    .filter-bar input { border-radius: 12px; border: 1px solid var(--border); background: var(--bg); color: var(--text); padding: 12px 16px; transition: 0.2s; }
    .filter-bar input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
    .species-btn { border-radius: 50px; padding: 8px 20px; font-weight: 600; font-size: 0.88rem; border: 1px solid var(--border); background: var(--bg); color: var(--text); cursor: pointer; transition: 0.2s; }
    .species-btn.active, .species-btn:hover { background: var(--primary); color: white; border-color: var(--primary); }

    .pet-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 24px; overflow: hidden; transition: all 0.3s; cursor: pointer; height: 100%; }
    .pet-card:hover { transform: translateY(-8px); border-color: var(--primary); box-shadow: 0 20px 50px rgba(99,102,241,0.12); }
    .pet-img { height: 220px; object-fit: cover; width: 100%; }
    .pet-img-placeholder { height: 220px; background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(168,85,247,0.1)); display: flex; align-items: center; justify-content: center; font-size: 5rem; color: var(--primary); opacity: 0.4; }
    .pet-body { padding: 20px; }
    .pet-name { font-weight: 800; font-size: 1.1rem; margin-bottom: 4px; }
    .pet-price { font-weight: 700; color: var(--primary); }
    .pet-city { font-size: 0.82rem; color: var(--footer-text); }
    .pet-desc { font-size: 0.85rem; color: var(--footer-text); margin: 8px 0 14px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.5; }
    .contact-btn { border-radius: 50px; padding: 8px 20px; font-weight: 700; font-size: 0.85rem; background: var(--primary); color: white; border: none; cursor: pointer; transition: 0.2s; }
    .contact-btn:hover { opacity: 0.85; transform: scale(1.02); }

    .empty-state { text-align: center; padding: 80px 20px; color: var(--footer-text); }
    .empty-state i { font-size: 5rem; opacity: 0.2; margin-bottom: 20px; }
    .empty-state h4 { font-weight: 700; margin-bottom: 8px; }

    .section-badge { display: inline-block; background: rgba(99,102,241,0.1); color: var(--primary); border: 1px solid rgba(99,102,241,0.2); border-radius: 50px; padding: 6px 18px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }

    @media(max-width:768px) { .adoption-hero h1 { font-size: 2.4rem; } }
</style>

<!-- HERO -->
<section class="adoption-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-up">
                <span class="section-badge">🐾 Adoption Center</span>
                <h1 class="mb-4">Find Your <span class="text-primary">Forever</span><br>Companion.</h1>
                <p class="fs-5 opacity-75 mb-5 lh-lg">Browse pets available for adoption across Pakistan. Every pet here deserves a loving home. Will you be theirs?</p>
                <div class="d-flex gap-4 flex-wrap">
                    <div class="text-center">
                        <div style="font-size:1.8rem; font-weight:800; color:var(--primary);"><?= count($pets) ?>+</div>
                        <small class="text-muted fw-semibold">Listings</small>
                    </div>
                    <div class="text-center">
                        <div style="font-size:1.8rem; font-weight:800; color:#10b981;">340+</div>
                        <small class="text-muted fw-semibold">Adopted</small>
                    </div>
                    <div class="text-center">
                        <div style="font-size:1.8rem; font-weight:800; color:#f59e0b;">15+</div>
                        <small class="text-muted fw-semibold">Cities</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="zoom-in" data-aos-delay="200">
                <i class="fa-solid fa-house-chimney-heart" style="font-size:11rem; color:var(--primary); opacity:0.15; filter:drop-shadow(0 20px 40px rgba(99,102,241,0.2));"></i>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section style="padding: 0 0 80px;">
    <div class="container">
        <!-- FILTER BAR -->
        <div class="filter-bar" data-aos="fade-up">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <form method="GET" id="filterForm">
                        <input type="text" name="q" class="form-control" placeholder="🔍  Search by name, breed, or description..." value="<?= htmlspecialchars($search) ?>" oninput="debounceFilter()">
                        <input type="hidden" name="species" id="speciesInput" value="<?= htmlspecialchars($species) ?>">
                    </form>
                </div>
                <div class="col-md-7">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="species-btn <?= $species==='' ? 'active':'' ?>" onclick="filterSpecies('')">🐾 All</button>
                        <button class="species-btn <?= $species==='dog' ? 'active':'' ?>" onclick="filterSpecies('dog')">🐶 Dogs</button>
                        <button class="species-btn <?= $species==='cat' ? 'active':'' ?>" onclick="filterSpecies('cat')">🐱 Cats</button>
                        <button class="species-btn <?= $species==='bird' ? 'active':'' ?>" onclick="filterSpecies('bird')">🦜 Birds</button>
                        <button class="species-btn <?= $species==='rabbit' ? 'active':'' ?>" onclick="filterSpecies('rabbit')">🐰 Rabbits</button>
                        <button class="species-btn <?= $species==='fish' ? 'active':'' ?>" onclick="filterSpecies('fish')">🐠 Fish</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRID -->
        <?php if (empty($pets)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-dog"></i>
            <h4>No Listings Found</h4>
            <p>Try a different search term or check back later.</p>
            <a href="pets.php" class="btn btn-primary rounded-pill px-5 mt-2">Clear Filters</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach($pets as $p): ?>
            <div class="col-sm-6 col-lg-4" data-aos="fade-up">
                <div class="pet-card h-100">
                    <?php if (!empty($p['image']) && $p['image'] !== 'default.png'): ?>
                        <img src="assets/uploads/<?= htmlspecialchars($p['image']) ?>" class="pet-img" alt="<?= htmlspecialchars($p['name']) ?>">
                    <?php else: ?>
                        <div class="pet-img-placeholder"><i class="fa-solid fa-paw"></i></div>
                    <?php endif; ?>
                    <div class="pet-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div class="pet-name"><?= htmlspecialchars($p['name']) ?></div>
                            <div class="pet-price">Rs. <?= number_format($p['price']) ?></div>
                        </div>
                        <div class="pet-city"><i class="fa-solid fa-location-dot me-1"></i><?= htmlspecialchars($p['s_city'] ?? 'Pakistan') ?></div>
                        <div class="pet-desc"><?= htmlspecialchars($p['description'] ?? 'No description available.') ?></div>
                        <div class="d-flex gap-2 align-items-center justify-content-between">
                            <small class="text-muted"><i class="fa-solid fa-user me-1"></i><?= htmlspecialchars($p['s_name']) ?></small>
                            <button class="contact-btn" onclick="showContact(<?= htmlspecialchars(json_encode($p)) ?>)">
                                <i class="fa-solid fa-phone me-1"></i>Contact
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CONTACT MODAL -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-1">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold" id="cModal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cModal-body"></div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
function filterSpecies(sp) {
    document.getElementById('speciesInput').value = sp;
    document.getElementById('filterForm').submit();
}
let filterTimeout;
function debounceFilter() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => document.getElementById('filterForm').submit(), 600);
}

function showContact(p) {
    document.getElementById('cModal-title').innerText = p.name;
    document.getElementById('cModal-body').innerHTML = `
        <div class="text-center mb-4">
            <i class="fa-solid fa-paw" style="font-size:3rem; color:var(--primary); opacity:0.2;"></i>
        </div>
        <table class="table table-borderless">
            <tr><td class="fw-bold text-muted small">Seller</td><td class="fw-bold">${p.s_name}</td></tr>
            <tr><td class="fw-bold text-muted small">Price</td><td class="fw-bold text-primary">Rs. ${Number(p.price).toLocaleString()}</td></tr>
            <tr><td class="fw-bold text-muted small">City</td><td>${p.s_city || 'N/A'}</td></tr>
            <tr><td class="fw-bold text-muted small">Phone</td><td>${p.s_phone ? '<a href="tel:'+p.s_phone+'" class="fw-bold text-primary">'+p.s_phone+'</a>' : 'Not provided'}</td></tr>
        </table>
        <p class="text-muted small text-center">Contact the seller directly to arrange a meeting.</p>
        ${p.s_phone ? `<a href="tel:${p.s_phone}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold"><i class="fa-solid fa-phone me-2"></i>Call ${p.s_phone}</a>` : ''}
    `;
    new bootstrap.Modal('#contactModal').show();
}
</script>
