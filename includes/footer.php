</main>
    <footer style="background: var(--card-bg); border-top: 1px solid var(--border); padding: 80px 0 40px; margin-top: auto;">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <a class="logo-wrapper mb-4" href="index.php">
                        <div class="logo-icon"><i class="fa-solid fa-shield-dog"></i></div>
                        PetCare
                    </a>
                    <p style="color: var(--footer-text); max-width: 350px; line-height: 1.8;">
                        Revolutionizing pet ownership in Pakistan through digital healthcare and verified community standards. Based in Islamabad, serving all pet lovers.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="btn btn-outline-primary rounded-circle" style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-primary rounded-circle" style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-primary rounded-circle" style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <h6 class="fw-800 text-uppercase mb-4" style="letter-spacing: 1px; font-size: 0.8rem; color: var(--text);">Company</h6>
                    <ul class="list-unstyled d-grid gap-3">
                        <li><a href="pages/about.php" class="text-decoration-none fw-semibold" style="color: var(--footer-text);">About Our Mission</a></li>
                        <li><a href="#" class="text-decoration-none fw-semibold" style="color: var(--footer-text);">Careers</a></li>
                        <li><a href="#" class="text-decoration-none fw-semibold" style="color: var(--footer-text);">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-800 text-uppercase mb-4" style="letter-spacing: 1px; font-size: 0.8rem; color: var(--text);">Services</h6>
                    <ul class="list-unstyled d-grid gap-3">
                        <li><a href="pages/services.php" class="text-decoration-none fw-semibold" style="color: var(--footer-text);">Veterinary Booking</a></li>
                        <li><a href="#" class="text-decoration-none fw-semibold" style="color: var(--footer-text);">Pet Marketplace</a></li>
                        <li><a href="#" class="text-decoration-none fw-semibold" style="color: var(--footer-text);">Adoption Center</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-5" style="border-color: var(--border); opacity: 0.5;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <p class="small mb-0" style="color: var(--footer-text);">&copy; 2026 PetCare Ecosystem. All rights reserved.</p>
                <p class="small mb-0 fw-bold" style="color: var(--primary);">Made with <i class="fa-solid fa-heart"></i> for Pets</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });

        const themeBtn = document.getElementById('themeBtn');
        const icon = themeBtn.querySelector('i');
        const html = document.documentElement;
        
        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-theme', 'dark');
            icon.className = 'fa-solid fa-moon';
        }

        themeBtn.addEventListener('click', () => {
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                icon.className = 'fa-solid fa-sun';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.className = 'fa-solid fa-moon';
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>