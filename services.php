<?php include 'includes/header.php'; ?>

<style>
    /* ===== HERO ===== */
    .services-hero { padding: 120px 0 80px; }
    .services-hero h1 { font-size: 3.8rem; font-weight: 800; letter-spacing: -2px; line-height: 1.1; }
    
    /* ===== SERVICE CARDS ===== */
    .services-grid { padding: 80px 0; }
    .svc-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 28px; padding: 44px 36px; transition: all 0.35s ease; height: 100%; position: relative; overflow: hidden; }
    .svc-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; transform:scaleX(0); transition:0.35s; transform-origin:left; }
    .svc-card.purple::before { background: linear-gradient(90deg, #6366f1, #a855f7); }
    .svc-card.rose::before { background: linear-gradient(90deg, #f43f5e, #fb923c); }
    .svc-card.emerald::before { background: linear-gradient(90deg, #10b981, #06b6d4); }
    .svc-card.amber::before { background: linear-gradient(90deg, #f59e0b, #84cc16); }
    .svc-card:hover { transform: translateY(-10px); box-shadow: 0 25px 60px rgba(0,0,0,0.1); }
    .svc-card:hover::before { transform: scaleX(1); }
    .svc-icon { width: 72px; height: 72px; border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 28px; }
    .svc-icon.purple { background: linear-gradient(135deg, #6366f1, #a855f7); color: white; }
    .svc-icon.rose { background: linear-gradient(135deg, #f43f5e, #fb923c); color: white; }
    .svc-icon.emerald { background: linear-gradient(135deg, #10b981, #06b6d4); color: white; }
    .svc-icon.amber { background: linear-gradient(135deg, #f59e0b, #84cc16); color: white; }
    .svc-title { font-size: 1.35rem; font-weight: 800; margin-bottom: 12px; }
    .svc-desc { color: var(--footer-text); line-height: 1.75; margin-bottom: 24px; }
    .svc-list { list-style: none; padding: 0; margin: 0 0 28px 0; }
    .svc-list li { padding: 6px 0; color: var(--footer-text); font-size: 0.9rem; display: flex; align-items: center; gap: 10px; }
    .svc-list li::before { content:'✓'; color: var(--primary); font-weight: 800; }

    /* ===== PRICING ===== */
    .pricing-section { padding: 90px 0; background: var(--card-bg); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .price-card { background: var(--bg); border: 1px solid var(--border); border-radius: 28px; padding: 40px 32px; text-align: center; height: 100%; transition: 0.3s; }
    .price-card:hover { border-color: var(--primary); transform: translateY(-6px); box-shadow: 0 20px 50px rgba(99,102,241,0.1); }
    .price-card.featured { background: linear-gradient(135deg, #6366f1, #a855f7); color: white; border-color: transparent; transform: scale(1.04); }
    .price-card.featured:hover { transform: scale(1.04) translateY(-6px); }
    .price-tag { font-size: 3rem; font-weight: 800; letter-spacing: -1px; }
    .price-period { font-size: 0.9rem; opacity: 0.7; }
    .price-name { font-size: 1.1rem; font-weight: 700; margin-bottom: 4px; }
    .price-sub { font-size: 0.85rem; opacity: 0.7; margin-bottom: 28px; }
    .price-features { list-style: none; padding: 0; margin: 0 0 32px 0; text-align: left; }
    .price-features li { padding: 8px 0; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(128,128,128,0.1); }
    .price-features li:last-child { border: none; }
    .pf-check { color: #10b981; }
    .price-card.featured .pf-check { color: rgba(255,255,255,0.8); }

    /* ===== FAQ ===== */
    .faq-section { padding: 90px 0; }
    .faq-item { border: 1px solid var(--border); border-radius: 16px; margin-bottom: 12px; overflow: hidden; background: var(--card-bg); transition: 0.3s; }
    .faq-item:hover { border-color: var(--primary); }
    .faq-question { padding: 20px 28px; font-weight: 700; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px; font-size: 1rem; }
    .faq-question .faq-icon { transition: 0.3s; color: var(--primary); flex-shrink: 0; }
    .faq-question.open .faq-icon { transform: rotate(180deg); }
    .faq-answer { padding: 0 28px; max-height: 0; overflow: hidden; transition: all 0.4s ease; color: var(--footer-text); line-height: 1.75; font-size: 0.95rem; }
    .faq-answer.open { max-height: 200px; padding: 0 28px 24px; }

    /* ===== CTA STRIP ===== */
    .svc-cta { padding: 80px 0; background: linear-gradient(135deg, #6366f1, #a855f7); }
    .svc-cta h2 { color: white; font-size: 2.5rem; font-weight: 800; }
    .svc-cta p { color: rgba(255,255,255,0.8); }

    .section-badge { display: inline-block; background: rgba(99,102,241,0.1); color: var(--primary); border: 1px solid rgba(99,102,241,0.2); border-radius: 50px; padding: 6px 18px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
    .section-title { font-size: 2.6rem; font-weight: 800; letter-spacing: -1px; }
    @media(max-width:768px) { .services-hero h1 { font-size: 2.5rem; } .section-title { font-size: 2rem; } .price-card.featured { transform: scale(1); } }
</style>

<!-- ===== HERO ===== -->
<section class="services-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-badge">Our Platform</span>
                <h1 class="mb-4">We're Changing the Way <span class="text-primary">Pets are Cared For.</span></h1>
                <p class="fs-5 opacity-75 mb-4 lh-lg">PetCare combines technology and compassion to build Pakistan's first complete pet ecosystem — from booking a vet to finding your next furry friend.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="signup.php" class="btn btn-primary rounded-pill px-5 py-3 fw-bold" style="background:var(--primary); border:none;">Join Free</a>
                    <a href="pets.php" class="btn btn-outline-secondary rounded-pill px-5 py-3 fw-bold">View Adoptions</a>
                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="zoom-in" data-aos-delay="200">
                <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: 40px; padding: 60px 40px;">
                    <div class="row g-4">
                        <div class="col-6"><div style="background:rgba(99,102,241,0.1); border-radius:20px; padding:24px;"><i class="fa-solid fa-stethoscope text-primary" style="font-size:2.5rem;"></i><p class="fw-bold mt-2 mb-0 small">Vet Booking</p></div></div>
                        <div class="col-6"><div style="background:rgba(244,63,94,0.1); border-radius:20px; padding:24px;"><i class="fa-solid fa-store" style="color:#f43f5e; font-size:2.5rem;"></i><p class="fw-bold mt-2 mb-0 small">Marketplace</p></div></div>
                        <div class="col-6"><div style="background:rgba(16,185,129,0.1); border-radius:20px; padding:24px;"><i class="fa-solid fa-house-chimney-heart" style="color:#10b981; font-size:2.5rem;"></i><p class="fw-bold mt-2 mb-0 small">Adoption</p></div></div>
                        <div class="col-6"><div style="background:rgba(245,158,11,0.1); border-radius:20px; padding:24px;"><i class="fa-solid fa-file-medical" style="color:#f59e0b; font-size:2.5rem;"></i><p class="fw-bold mt-2 mb-0 small">Pet Profiles</p></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SERVICE CARDS GRID ===== -->
<section class="services-grid">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Services</span>
            <h2 class="section-title">Everything You Need,<br><span class="text-primary">All in One Platform</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="svc-card purple h-100">
                    <div class="svc-icon purple"><i class="fa-solid fa-stethoscope"></i></div>
                    <div class="svc-title">Veterinary Consultations</div>
                    <div class="svc-desc">Connect with Pakistan's top certified vets through secure video calls. Get expert advice, prescriptions, and follow-up care without leaving home.</div>
                    <ul class="svc-list">
                        <li>Video & chat consultations</li>
                        <li>Instant appointment booking</li>
                        <li>Verified specialist vets</li>
                        <li>Post-consultation follow-up</li>
                    </ul>
                    <a href="login.php" class="btn btn-primary rounded-pill px-4 fw-bold" style="background:var(--primary); border:none;">Book a Vet →</a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="svc-card rose h-100">
                    <div class="svc-icon rose"><i class="fa-solid fa-store"></i></div>
                    <div class="svc-title">Pet Marketplace</div>
                    <div class="svc-desc">Buy and sell pets, premium food, accessories, and healthcare products. A trusted community-driven marketplace with seller verification.</div>
                    <ul class="svc-list">
                        <li>Verified sellers & listings</li>
                        <li>Pets, food, and accessories</li>
                        <li>Photo & video listings</li>
                        <li>Direct seller contact</li>
                    </ul>
                    <a href="login.php" class="btn btn-danger rounded-pill px-4 fw-bold" style="border:none;">Browse Market →</a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="svc-card emerald h-100">
                    <div class="svc-icon emerald"><i class="fa-solid fa-house-chimney-heart"></i></div>
                    <div class="svc-title">Pet Adoption Center</div>
                    <div class="svc-desc">Give a rescue pet a loving forever home. Browse dogs, cats, birds, and more from trusted adopters across Pakistan.</div>
                    <ul class="svc-list">
                        <li>Rescue & shelter pets</li>
                        <li>Filtered by city & species</li>
                        <li>Direct contact with adopters</li>
                        <li>Free adoption listings</li>
                    </ul>
                    <a href="pets.php" class="btn btn-success rounded-pill px-4 fw-bold" style="border:none;">Adopt a Pet →</a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="svc-card amber h-100">
                    <div class="svc-icon amber"><i class="fa-solid fa-file-medical"></i></div>
                    <div class="svc-title">Digital Pet Profiles</div>
                    <div class="svc-desc">Create and manage digital health records for each of your pets. Track vaccinations, appointments, and share profiles with your vet.</div>
                    <ul class="svc-list">
                        <li>Multi-pet management</li>
                        <li>Health & vaccination notes</li>
                        <li>Appointment history</li>
                        <li>Shareable pet profiles</li>
                    </ul>
                    <a href="signup.php" class="btn btn-warning rounded-pill px-4 fw-bold text-dark" style="border:none;">Create Profile →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRICING ===== -->
<section class="pricing-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">Transparent Pricing</span>
            <h2 class="section-title">Simple, Fair <span class="text-primary">Pricing</span></h2>
            <p class="text-muted mt-2">No hidden fees. Join free, pay only for consultations.</p>
        </div>
        <div class="row g-4 align-items-center justify-content-center">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="price-card">
                    <div class="price-name">Free Account</div>
                    <div class="price-sub">For pet owners</div>
                    <div class="price-tag mb-1">Rs. 0</div>
                    <div class="price-period">Forever free</div>
                    <ul class="price-features mt-4">
                        <li><i class="fa-solid fa-check pf-check"></i> Browse marketplace</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Manage pet profiles</li>
                        <li><i class="fa-solid fa-check pf-check"></i> View adoption listings</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Post up to 3 listings</li>
                        <li><i class="fa-solid fa-times text-danger"></i> Priority support</li>
                    </ul>
                    <a href="signup.php" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-3">Sign Up Free</a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="price-card featured">
                    <div class="price-name">Vet Consultation</div>
                    <div class="price-sub">Per session</div>
                    <div class="price-tag mb-1">Rs. 500+</div>
                    <div class="price-period">Set by each vet</div>
                    <ul class="price-features mt-4">
                        <li><i class="fa-solid fa-check pf-check"></i> Live video consultation</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Expert prescription</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Follow-up care notes</li>
                        <li><i class="fa-solid fa-check pf-check"></i> View meeting history</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Leave verified review</li>
                    </ul>
                    <a href="login.php" class="btn w-100 rounded-pill fw-bold py-3" style="background:white; color:#6366f1;">Book Consultation</a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="price-card">
                    <div class="price-name">Vet Registration</div>
                    <div class="price-sub">For veterinarians</div>
                    <div class="price-tag mb-1">Rs. 0</div>
                    <div class="price-period">Free to join</div>
                    <ul class="price-features mt-4">
                        <li><i class="fa-solid fa-check pf-check"></i> Professional dashboard</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Manage appointments</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Set your own fees</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Client reviews</li>
                        <li><i class="fa-solid fa-check pf-check"></i> Verification badge</li>
                    </ul>
                    <a href="signup.php" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-3">Join as Vet</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FAQ ===== -->
<section class="faq-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5" data-aos="fade-up">
                    <span class="section-badge">FAQ</span>
                    <h2 class="section-title">Frequently Asked <span class="text-primary">Questions</span></h2>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            How do I book a vet appointment?
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">Simply create a free account, navigate to the "Vet Network" section in your dashboard, choose a vet, and fill out the booking form with your pet's details. The vet will review and confirm your appointment with a meeting link.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            Are the veterinarians on PetCare verified?
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">Yes! All vets go through a verification process by our admin team. Verified vets display a blue checkmark badge. We review their credentials and experience before granting full access to patient bookings.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            How does the marketplace work?
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">Any registered user can post listings for pets, supplies, or food. Each listing goes through admin approval before going live. Buyers can view listings and contact sellers directly through the provided contact details.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            Can I list a pet for adoption?
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">Absolutely! When creating a marketplace listing, you can mark it as an "Adoption" listing. It will then appear on our public Adoption page, giving your pet maximum visibility to potential adopters across Pakistan.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            Is PetCare available across all of Pakistan?
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">Yes! PetCare is a fully online platform available nationwide. Veterinary consultations are conducted via video call, so you can access quality care regardless of your location in Pakistan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA STRIP ===== -->
<section class="svc-cta text-center">
    <div class="container" data-aos="zoom-in">
        <h2 class="mb-3">Ready to Get Started?</h2>
        <p class="mb-4 fs-5">Join PetCare today — it's completely free.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="signup.php" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary">Create Free Account</a>
            <a href="pets.php" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold">Browse Adoptions</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
function toggleFAQ(el) {
    const answer = el.nextElementSibling;
    const isOpen = answer.classList.contains('open');
    document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-question').forEach(q => q.classList.remove('open'));
    if (!isOpen) { answer.classList.add('open'); el.classList.add('open'); }
}
</script>