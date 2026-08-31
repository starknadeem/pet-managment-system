<?php include 'includes/header.php'; ?>

<style>
    /* ========== HERO ========== */
    .hero-section { padding: 100px 0 80px; position: relative; overflow: hidden; }
    .hero-section::before {
        content: ''; position: absolute; top: -200px; right: -200px;
        width: 700px; height: 700px; border-radius: 50%;
        background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-h1 { font-size: 4.5rem; font-weight: 800; line-height: 1.05; letter-spacing: -2px; }
    .hero-icon-lg { font-size: 12rem; color: var(--primary); filter: drop-shadow(0 20px 40px rgba(99,102,241,0.25)); animation: float 6s ease-in-out infinite; }
    @keyframes float { 0%,100%{transform:translateY(0) rotate(0)} 50%{transform:translateY(-18px) rotate(4deg)} }

    .badge-hero { background: rgba(99,102,241,0.1); color: var(--primary); border: 1px solid rgba(99,102,241,0.25); }

    /* ========== STATS ========== */
    .stats-section { padding: 60px 0; background: var(--card-bg); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .stat-item { text-align: center; }
    .stat-num { font-size: 2.8rem; font-weight: 800; background: linear-gradient(135deg, var(--primary), #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .stat-label { font-size: 0.9rem; color: var(--footer-text); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

    /* ========== FEATURES ========== */
    .features-section { padding: 100px 0; }
    .section-badge { display: inline-block; background: rgba(99,102,241,0.1); color: var(--primary); border: 1px solid rgba(99,102,241,0.2); border-radius: 50px; padding: 6px 18px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
    .section-title { font-size: 2.8rem; font-weight: 800; letter-spacing: -1px; line-height: 1.1; }

    .feature-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 28px; padding: 40px 32px; transition: all 0.3s ease; height: 100%; }
    .feature-card:hover { transform: translateY(-8px); border-color: var(--primary); box-shadow: 0 20px 50px rgba(99,102,241,0.12); }
    .feature-icon { width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 24px; }
    .fi-purple { background: linear-gradient(135deg, #6366f1, #a855f7); color: white; }
    .fi-rose { background: linear-gradient(135deg, #f43f5e, #fb7185); color: white; }
    .fi-emerald { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
    .fi-amber { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
    .feature-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 12px; }
    .feature-desc { color: var(--footer-text); line-height: 1.7; font-size: 0.95rem; }

    /* ========== HOW IT WORKS ========== */
    .hiw-section { padding: 100px 0; background: var(--card-bg); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .step-num { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #a855f7); color: white; font-size: 1.3rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 8px 20px rgba(99,102,241,0.3); }
    .step-connector { position: absolute; top: 28px; left: 55%; width: 90%; height: 2px; background: linear-gradient(90deg, var(--primary), transparent); }
    .step-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 8px; }
    .step-desc { color: var(--footer-text); font-size: 0.9rem; line-height: 1.6; }

    /* ========== TESTIMONIALS ========== */
    .testimonials-section { padding: 100px 0; }
    .tcard { background: var(--card-bg); border: 1px solid var(--border); border-radius: 24px; padding: 36px; height: 100%; transition: 0.3s; }
    .tcard:hover { border-color: var(--primary); transform: translateY(-4px); }
    .tcard-quote { font-size: 2.5rem; line-height: 1; color: var(--primary); opacity: 0.3; font-family: Georgia, serif; margin-bottom: 8px; }
    .tcard-text { color: var(--footer-text); line-height: 1.8; font-size: 0.95rem; margin-bottom: 20px; }
    .tcard-author { font-weight: 700; font-size: 0.95rem; }
    .tcard-role { font-size: 0.8rem; color: var(--footer-text); }
    .star-row { color: #f59e0b; font-size: 0.85rem; margin-bottom: 12px; }
    .avatar { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #a855f7); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0; }

    /* ========== CTA ========== */
    .cta-section { padding: 100px 0; }
    .cta-box { background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); border-radius: 40px; padding: 80px 60px; position: relative; overflow: hidden; }
    .cta-box::before { content:''; position:absolute; top:-100px; right:-100px; width:400px; height:400px; border-radius:50%; background:rgba(255,255,255,0.07); }
    .cta-box::after { content:''; position:absolute; bottom:-100px; left:-100px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.05); }
    .cta-title { font-size: 2.8rem; font-weight: 800; color: white; letter-spacing: -1px; }
    .cta-sub { color: rgba(255,255,255,0.8); font-size: 1.1rem; }
    .cta-input { border-radius: 50px; border: none; padding: 16px 24px; font-size: 0.95rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); color: white; border: 1px solid rgba(255,255,255,0.3); }
    .cta-input::placeholder { color: rgba(255,255,255,0.6); }
    .cta-input:focus { outline: none; background: rgba(255,255,255,0.25); border-color: rgba(255,255,255,0.5); }
    .cta-btn { border-radius: 50px; padding: 16px 32px; background: white; color: var(--primary); font-weight: 700; border: none; cursor: pointer; transition: 0.2s; white-space: nowrap; }
    .cta-btn:hover { transform: scale(1.03); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

    /* ========== TRUST BAND ========== */
    .trust-section { padding: 50px 0; background: var(--card-bg); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .partner-logo { font-size: 2rem; opacity: 0.35; transition: 0.3s; color: var(--text); cursor: default; }
    .partner-logo:hover { opacity: 0.9; color: var(--primary); }

    @media(max-width:768px) {
        .hero-h1 { font-size: 2.8rem; }
        .hero-icon-lg { font-size: 7rem; }
        .section-title { font-size: 2rem; }
        .cta-box { padding: 50px 30px; }
        .cta-title { font-size: 2rem; }
    }
</style>

<!-- ===== HERO ===== -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7" data-aos="fade-up">
                <span class="badge badge-hero px-3 py-2 rounded-pill mb-4 fw-bold fs-7">🇵🇰 PAKISTAN'S FIRST PET ECOSYSTEM</span>
                <h1 class="hero-h1 mb-4">Smart Care for <br><span class="text-primary">Happy Tails.</span></h1>
                <p class="fs-4 opacity-75 mb-5 pe-lg-5">Pakistan's first integrated platform for expert veterinary care, secure pet adoptions, and premium pet supplies — all in one place.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="signup.php" class="btn btn-primary rounded-pill px-5 py-3 fs-5 fw-bold shadow" style="background: var(--primary); border: none;">Get Started Free</a>
                    <a href="services.php" class="btn btn-outline-secondary rounded-pill px-5 py-3 fs-5 fw-bold">Explore Services</a>
                </div>
                <div class="d-flex gap-4 mt-5 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <small class="fw-semibold opacity-75">Verified Vets</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <small class="fw-semibold opacity-75">Secure Marketplace</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <small class="fw-semibold opacity-75">Free to Join</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-center" data-aos="zoom-in" data-aos-delay="200">
                <i class="fa-solid fa-dog hero-icon-lg"></i>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3 stat-item" data-aos="fade-up">
                <div class="stat-num" data-target="1200">0</div>
                <div class="stat-label">Happy Pet Owners</div>
            </div>
            <div class="col-6 col-md-3 stat-item" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-num" data-target="85">0</div>
                <div class="stat-label">Certified Vets</div>
            </div>
            <div class="col-6 col-md-3 stat-item" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-num" data-target="340">0</div>
                <div class="stat-label">Pets Adopted</div>
            </div>
            <div class="col-6 col-md-3 stat-item" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-num" data-target="4800">0</div>
                <div class="stat-label">Appointments Booked</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TRUST BAND ===== -->
<section class="trust-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4 px-lg-5">
            <div class="partner-logo"><i class="fa-solid fa-shield-virus me-2"></i><span class="fs-5 fw-bold">VET-PRO</span></div>
            <div class="partner-logo"><i class="fa-solid fa-bone me-2"></i><span class="fs-5 fw-bold">PET-FOOD</span></div>
            <div class="partner-logo"><i class="fa-solid fa-heart-pulse me-2"></i><span class="fs-5 fw-bold">HEALTH-CO</span></div>
            <div class="partner-logo"><i class="fa-solid fa-truck-fast me-2"></i><span class="fs-5 fw-bold">SWIFT-DELIVERY</span></div>
            <div class="partner-logo"><i class="fa-solid fa-house-chimney-window me-2"></i><span class="fs-5 fw-bold">ADOPT-ME</span></div>
        </div>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="features-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">What We Offer</span>
            <h2 class="section-title">Everything Your Pet Needs,<br><span class="text-primary">In One Place.</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="feature-icon fi-purple"><i class="fa-solid fa-stethoscope"></i></div>
                    <div class="feature-title">Vet Booking</div>
                    <div class="feature-desc">Connect with certified veterinarians instantly. Book video consultations and get professional advice from the comfort of your home.</div>
                    <a href="login.php" class="btn btn-sm btn-outline-primary rounded-pill mt-3 fw-bold">Book Now →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon fi-rose"><i class="fa-solid fa-store"></i></div>
                    <div class="feature-title">Pet Marketplace</div>
                    <div class="feature-desc">Buy and sell pets, food, accessories, and supplies. A trusted community-driven marketplace with verified sellers.</div>
                    <a href="login.php" class="btn btn-sm btn-outline-primary rounded-pill mt-3 fw-bold">Explore →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon fi-emerald"><i class="fa-solid fa-house-chimney-heart"></i></div>
                    <div class="feature-title">Pet Adoption</div>
                    <div class="feature-desc">Give a furry friend a forever home. Browse pets available for adoption near you and change a life today.</div>
                    <a href="pets.php" class="btn btn-sm btn-outline-primary rounded-pill mt-3 fw-bold">Adopt →</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon fi-amber"><i class="fa-solid fa-file-medical"></i></div>
                    <div class="feature-title">Pet Profiles</div>
                    <div class="feature-desc">Manage all your pets in one digital profile. Track health records, vaccinations, and appointment history.</div>
                    <a href="signup.php" class="btn btn-sm btn-outline-primary rounded-pill mt-3 fw-bold">Get Started →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="hiw-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Simple Process</span>
            <h2 class="section-title">How It <span class="text-primary">Works</span></h2>
        </div>
        <div class="row g-5 text-center">
            <div class="col-md-3 position-relative" data-aos="fade-up" data-aos-delay="0">
                <div class="step-num">1</div>
                <div class="step-title">Create Your Account</div>
                <div class="step-desc">Sign up in under 60 seconds. No credit card required. Instantly access the full platform.</div>
            </div>
            <div class="col-md-3 position-relative" data-aos="fade-up" data-aos-delay="100">
                <div class="step-num">2</div>
                <div class="step-title">Add Your Pets</div>
                <div class="step-desc">Build digital profiles for each of your pets with their breed, age, and health notes.</div>
            </div>
            <div class="col-md-3 position-relative" data-aos="fade-up" data-aos-delay="200">
                <div class="step-num">3</div>
                <div class="step-title">Book a Vet</div>
                <div class="step-desc">Browse verified vets, view their specializations, and book an online consultation instantly.</div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="step-num">4</div>
                <div class="step-title">Get Expert Care</div>
                <div class="step-desc">Join your video session, receive prescriptions, and leave a review to help the community.</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="testimonials-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Testimonials</span>
            <h2 class="section-title">Loved by Pet Owners<br><span class="text-primary">Across Pakistan</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="tcard">
                    <div class="star-row">★★★★★</div>
                    <div class="tcard-quote">"</div>
                    <div class="tcard-text">I booked a vet within minutes for my sick cat. Dr. Ahmed was incredibly professional. This platform is a game-changer for Pakistan!</div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">SA</div>
                        <div>
                            <div class="tcard-author">Sara Ahmed</div>
                            <div class="tcard-role">Cat Owner, Islamabad</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="tcard">
                    <div class="star-row">★★★★★</div>
                    <div class="tcard-quote">"</div>
                    <div class="tcard-text">Found my golden retriever puppy through the marketplace. The seller was verified and the process was completely smooth and trustworthy.</div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">FK</div>
                        <div>
                            <div class="tcard-author">Fahad Khan</div>
                            <div class="tcard-role">Dog Owner, Lahore</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="tcard">
                    <div class="star-row">★★★★☆</div>
                    <div class="tcard-quote">"</div>
                    <div class="tcard-text">As a vet, managing appointments has never been easier. The dashboard is clean, intuitive, and I can respond to clients in seconds.</div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">DR</div>
                        <div>
                            <div class="tcard-author">Dr. Rimsha Malik</div>
                            <div class="tcard-role">Veterinarian, Karachi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA / NEWSLETTER ===== -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box" data-aos="zoom-in">
            <div class="row align-items-center g-5 position-relative" style="z-index:1">
                <div class="col-lg-6">
                    <div class="cta-title mb-3">Ready to Give Your Pet the Best? 🐾</div>
                    <div class="cta-sub mb-4">Join thousands of happy pet owners. Get updates on new vets, adoption events, and pet care tips.</div>
                    <div class="d-flex gap-3 flex-wrap">
                        <input type="email" class="cta-input flex-grow-1" placeholder="Enter your email address...">
                        <button class="cta-btn">Subscribe Free</button>
                    </div>
                    <small style="color:rgba(255,255,255,0.5); font-size:0.78rem;" class="mt-2 d-block">No spam. Unsubscribe any time.</small>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fa-solid fa-envelope-open-text" style="font-size:9rem; color:rgba(255,255,255,0.12);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
// Animated stat counters
function animateCounter(el) {
    const target = parseInt(el.getAttribute('data-target'));
    const suffix = target >= 1000 ? '+' : '+';
    let current = 0;
    const step = Math.ceil(target / 80);
    const interval = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString() + suffix;
        if (current >= target) clearInterval(interval);
    }, 20);
}

// Trigger counters when stats section enters viewport
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            document.querySelectorAll('.stat-num[data-target]').forEach(animateCounter);
            statsObserver.disconnect();
        }
    });
}, { threshold: 0.3 });

const statsSection = document.querySelector('.stats-section');
if (statsSection) statsObserver.observe(statsSection);
</script>