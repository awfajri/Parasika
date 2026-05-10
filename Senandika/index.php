<!DOCTYPE html>
<html lang="id">
<head>
    <!-- 
      DOKUMENTASI SECTION HEAD
      Memuat metadata, stylesheet (Bootstrap, Google Fonts, Tailwind), dan icon.
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Elektronik Arsip dan Dokumen - Parasika</title>
    
    <!-- Link CSS Framework dan Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Tailwind CSS CDN dengan konfigurasi Preflight disabled agar tidak konflik dengan Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false,
        }
      }
    </script>
    
    <!-- Custom CSS khusus untuk landing page Senandika -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/img/logo-parasika.png">
</head>
<body>

    <!-- SECTION: HERO & NAVIGATION -->
    <div class="hero-wrapper">
        
        <!-- Navbar Glassmorphism - Navigasi melayang dengan efek blur -->
        
    <nav class="fixed top-6 left-1/2 -translate-x-1/2 w-[90%] max-w-4xl z-50 flex flex-col">
        <div class="flex justify-between items-center w-full glass-nav-container rounded-[34px] p-2">
            <a href="index.php" class="flex items-center gap-3 pl-2">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                    <img src="assets/img/logo.jpg" alt="Logo Parasika" class="w-10 h-10 rounded-full object-cover bg-white p-1" />
                </div>
                <span class="text-white font-bold text-[24px] hidden sm:block">Parasika</span>
            </a>
            <div class="hidden md:flex items-center gap-6 pr-6">
                <a href="#fitur" class="text-white/80 hover:text-white text-sm font-medium transition-colors text-decoration-none">Fitur</a>
                <a href="#faq" class="text-white/80 hover:text-white text-sm font-medium transition-colors text-decoration-none">FAQ</a>
                <a href="registrasi.php" class="text-white/80 hover:text-white text-sm font-medium transition-colors text-decoration-none">Register</a>
                <a href="login.php" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2 transition-all text-decoration-none">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
            <button id="navToggle" class="hamburger-btn md:hidden">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div id="mobileMenu" class="mobile-menu">
            <a href="#fitur" class="mobile-menu-item">
                <span>Fitur</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <div class="mobile-menu-divider"></div>
            <a href="#faq" class="mobile-menu-item">
                <span>FAQ</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <div class="mobile-menu-divider"></div>
            <div class="nav-btn-container">
                <a href="registrasi.php" class="btn-outline-white">Register</a>
                <a href="login.php" class="btn-fill-white">Login</a>
            </div>
        </div>
    </nav>


        <!-- Background Image Hero -->
        <div class="hero-img-container">
            <img src="assets/img/hero.jpg" alt="Kegiatan Parasika">
        </div>

    </div>

    <!-- Main Content Hero - Judul dan Deskripsi singkat aplikasi -->
    <div class="container hero-content max-w-7xl mx-auto px-6 lg:px-24">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="hero-title">Sistem Elektronik Arsip dan Dokumen</h1>
                <h1 class="hero-title hero-title-maroon">Parasika</h1>
                <a href="login.php" class="btn-maroon mt-3">Masuk Ke Dashboard</a>
            </div>
            
            <div class="col-lg-6">
                <div class="row align-items-center mb-4">
                    <div class="col-12">
                        <p class="hero-desc">
                            Aplikasi E-Archive terpusat untuk menyimpan, mengelola, dan menemukan kembali dokumen organisasi UKM Kesenian Kampus dengan mudah dan aman.
                        </p>
                    </div>
                </div>
                <div class="stat-container">
                    <img src="../assets/img/logo-parasika.png" alt="Logo Parasika" class="parasika-logo-large d-none d-sm-block">
                    
                    <div class="stat-box">
                        <span class="stat-number">20+</span>
                        <span class="stat-text">Surat<br>Tersimpan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: FITUR UTAMA -->
    <div id="fitur" class="container py-4 py-lg-5 fitur-section max-w-7xl mx-auto px-6 lg:px-24">
        <h2 class="section-title">Fitur <span class="text-dark">Senandika</span></h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-10">
                <div class="feature-card">
                    <i class="bi bi-cloud-arrow-up feature-icon"></i>
                    <p class="feature-text">Dokumen disimpan dengan teknologi terdistribusi ke server eksternal untuk menjaga kapasitas server utama.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-10">
                <div class="feature-card">
                    <i class="bi bi-search feature-icon"></i>
                    <p class="feature-text">Temukan dokumen penting dalam hitungan detik dengan fitur pencarian live berbasis kategori.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-10">
                <div class="feature-card">
                    <i class="bi bi-shield-check feature-icon"></i>
                    <p class="feature-text">Hak akses terbagi menjadi tiga level (Sekretaris, Ketua, Anggota) sesuai tupoksi dan wewenang.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: FAQ (Accordion) -->
    <div id="faq" class="container faq-section max-w-7xl mx-auto px-6 lg:px-24">
        <h2 class="section-title text-center mb-5">Frequently Asked<br><span class="text-dark">Question</span></h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="faqAccordion">
                    <!-- Item FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <span class="faq-number">01</span>
                                <span class="faq-title">Apa itu Senandika?</span>
                                <span class="faq-toggle-icon"><i class="bi bi-plus"></i></span>
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Senandika adalah Sistem Elektronik Arsip dan Dokumen, sebuah aplikasi e-archive terpusat yang dirancang khusus untuk mengelola, menyimpan, dan melacak seluruh dokumen UKM Kesenian Parasika secara digital dan aman.
                            </div>
                        </div>
                    </div>

                    <!-- Item FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <span class="faq-number">02</span>
                                <span class="faq-title">Siapa saja yang dapat mengakses Senandika?</span>
                                <span class="faq-toggle-icon"><i class="bi bi-plus"></i></span>
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Akses sistem terbagi menjadi tiga level: <strong>Sekretaris</strong> (mengelola/menambah arsip dan user), <strong>Ketua</strong> (memantau log aktivitas dan statistik dokumen), dan <strong>Anggota</strong> (hanya dapat mencari dan melihat dokumen publik).
                            </div>
                        </div>
                    </div>

                    <!-- Item FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <span class="faq-number">03</span>
                                <span class="faq-title">Apakah dokumen yang diunggah aman?</span>
                                <span class="faq-toggle-icon"><i class="bi bi-plus"></i></span>
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sangat aman! Senandika menggunakan integrasi API dengan teknologi penyimpanan Cloud dari Supabase, sehingga file fisik tidak akan hilang dan database tersimpan secara terdistribusi.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: FOOTER -->
    <div class="footer-wrapper">
        <div class="container max-w-7xl mx-auto px-6 lg:px-24">
            <div class="footer-card">
                <div class="row h-100">
                    
                    <!-- Footer Logo & Sosmed -->
                    <div class="col-lg-3 d-flex flex-column justify-content-between mb-4 mb-lg-0">
                        <div>
                            <img src="../assets/img/logo-parasika.png" alt="Logo" class="footer-logo">
                        </div>
                        <div class="mt-4">
                            <a href="https://www.instagram.com/official.parasika/" class="social-circle"><i class="bi bi-instagram"></i></a>
                            <a href="https://www.tiktok.com/@parasika?_t=8oTWa7FdUFV&_r=1" class="social-circle"><i class="bi bi-tiktok"></i></a>
                            <a href="https://www.youtube.com/@paguyubanrakyatseniunsika4023" class="social-circle"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>

                    <!-- Footer Info Links -->
                    <div class="col-lg-3 mb-4 mb-lg-0">
                        <div class="footer-heading">INFORMATION</div>
                        <a href="#fitur" class="footer-link">Fitur</a>
                        <a href="#faq" class="footer-link">FAQ</a>
                    </div>

                    <!-- Footer Internal App & Alamat -->
                    <div class="col-lg-3 d-flex flex-column justify-content-between mb-4 mb-lg-0">
                        <div>
                            <div class="footer-heading">INTERNAL APP</div>
                            <a href="#" class="footer-link">Senandika</a>
                        </div>
                        <div class="mt-4 mt-lg-0">
                            <p class="footer-contact-text text-muted" style="font-size: 0.85rem;">
                                Jl. HS. Ronggo Waluyo, Puseurjaya, Telukjambe Timur, Karawang 41361
                            </p>
                        </div>
                    </div>

                    <!-- Footer Call to Action & Kontak -->
                    <div class="col-lg-3 d-flex flex-column align-items-lg-end text-lg-end">
                        <div>
                            <button class="btn-request">Request a call</button>
                            <p class="footer-contact-text fw-bold mt-2">+6283161505080</p>
                            <p class="footer-contact-text">officialparasika@unsika.ac.id</p>
                        </div>
                        
                        <div class="mt-auto pt-4">
                            <img src="assets/img/music.jpg" onerror="this.src='assets/img/music.jpg'" alt="Kaset Mojang Priangan" class="cassette-img">
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Copyright Footer -->
            <div class="footer-bottom-text">
                <span>© 2026 — Copyright Kelompok 4</span>
                <a href="#">Privacy Policy | Term of Service</a>
            </div>
        </div>
    </div>

    <!-- Floating Action Button (FAB) untuk kembali ke web utama -->
    <div class="fab-back-container">
        <a href="../index.html" class="fab-back" title="Kembali ke Website Utama">
            <img src="../assets/img/logo-parasika.png" alt="Logo">
            <span class="fab-text">Back to Homepage</span>
        </a>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const navToggle = document.getElementById('navToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        if (navToggle && mobileMenu) {
            navToggle.addEventListener('click', () => {
                navToggle.classList.toggle('active');
                mobileMenu.classList.toggle('active');
            });
        }
    </script>

</body>
</html>