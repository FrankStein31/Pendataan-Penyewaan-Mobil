<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rental Mobil - Solusi Transportasi Anda</title>
  <link href="styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="styling/assets/css/argon-dashboard.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* Enhanced Hero Section */
    .hero-section {
      background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(30,58,138,0.4)), 
        url('https://images.unsplash.com/photo-1583267748498-6b418b3b91b1?auto=format&fit=crop&w=1500&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      height: 100vh;
      display: flex;
      align-items: center;
      color: white;
      text-align: left;
      position: relative;
      overflow: hidden;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, rgba(79,195,247,0.1), rgba(41,182,246,0.1));
      animation: shimmer 3s ease-in-out infinite alternate;
    }

    .hero-section::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(79,195,247,0.05) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes shimmer {
      0% { opacity: 0.3; }
      100% { opacity: 0.7; }
    }

    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .hero-content {
      animation: fadeInUp 1.5s ease;
      position: relative;
      z-index: 2;
    }

    .hero-content h1 {
      font-size: 4rem;
      font-weight: 800;
      background: linear-gradient(135deg, #ffffff, #4fc3f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 2rem;
      text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    .hero-content p {
      font-size: 1.3rem;
      font-weight: 400;
      color: rgba(255,255,255,0.9);
      margin-bottom: 3rem;
      text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }

    .btn-hero {
      background: linear-gradient(135deg, #4fc3f7, #29b6f6);
      border: none;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
      padding: 1rem 2.5rem;
      border-radius: 50px;
      box-shadow: 0 10px 30px rgba(79,195,247,0.4);
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.6s;
    }

    .btn-hero:hover::before {
      left: 100%;
    }

    .btn-hero:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 20px 50px rgba(79,195,247,0.6);
      color: white;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(60px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Enhanced Features Section */
    .features-section {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      padding: 8rem 0;
      position: relative;
      overflow: hidden;
    }

    .features-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23e2e8f0" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
      opacity: 0.3;
    }

    .section-title {
      position: relative;
      z-index: 2;
    }

    .section-title h2 {
      font-size: 3rem;
      font-weight: 800;
      background: linear-gradient(135deg, #1e293b, #475569);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1.5rem;
    }

    .section-title p {
      font-size: 1.2rem;
      color: #64748b;
      font-weight: 500;
    }

    .feature-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: none;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      background: linear-gradient(135deg, #ffffff, #f8fafc);
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
      height: 100%;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(79,195,247,0.05), rgba(41,182,246,0.05));
      opacity: 0;
      transition: opacity 0.4s ease;
    }

    .feature-card:hover::before {
      opacity: 1;
    }

    .feature-card:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 30px 80px rgba(79,195,247,0.3);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      font-size: 2rem;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .feature-icon::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, currentColor, transparent);
      opacity: 0.1;
    }

    .feature-icon.primary {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .feature-icon.success {
      background: linear-gradient(135deg, #10b981, #059669);
    }

    .feature-icon.warning {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .feature-card h5 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 1rem;
    }

    .feature-card p {
      color: #64748b;
      font-size: 1rem;
      line-height: 1.6;
    }

    /* Enhanced Navbar */
    .navbar {
      backdrop-filter: blur(20px);
      background: rgba(0, 0, 0, 0.9) !important;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
      padding: 1rem 0;
    }

    .navbar-brand {
      font-size: 1.8rem;
      font-weight: 800;
      color: #ffffff !important;
      transition: all 0.3s ease;
    }

    .navbar-brand:hover {
      color: #4fc3f7 !important;
      transform: scale(1.05);
    }

    .navbar-brand i {
      background: linear-gradient(45deg, #4fc3f7, #29b6f6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.8rem;
      margin-right: 0.5rem;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    .navbar .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 600;
      font-size: 1rem;
      padding: 0.8rem 1.5rem !important;
      margin: 0 0.3rem;
      border-radius: 30px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .navbar .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
      transition: left 0.5s;
    }

    .navbar .nav-link:hover::before {
      left: 100%;
    }

    .navbar .nav-link:hover {
      color: #ffffff !important;
      background: rgba(79, 195, 247, 0.2);
      transform: translateY(-2px);
    }

    .navbar .btn-outline-white {
      border: 2px solid rgba(255, 255, 255, 0.8);
      color: #ffffff !important;
      background: transparent;
      border-radius: 30px;
      font-weight: 700;
      padding: 0.7rem 2rem;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .navbar .btn-outline-white::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, #4fc3f7, #29b6f6);
      transition: left 0.4s ease;
      z-index: -1;
    }

    .navbar .btn-outline-white:hover::before {
      left: 0;
    }

    .navbar .btn-outline-white:hover {
      color: #ffffff !important;
      border-color: #4fc3f7;
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(79, 195, 247, 0.5);
    }

    /* Enhanced Footer */
    footer {
      background: linear-gradient(135deg, #1e293b, #334155);
      color: white;
      padding: 3rem 0;
      position: relative;
      overflow: hidden;
    }

    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, rgba(79,195,247,0.05), rgba(41,182,246,0.05));
    }

    footer .navbar-brand {
      color: white !important;
      font-size: 1.5rem;
    }

    footer .navbar-brand i {
      color: #4fc3f7;
    }

    footer p {
      color: rgba(255,255,255,0.7);
      margin: 0;
    }

    /* Scroll animations */
    .animate-on-scroll {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }

    .animate-on-scroll.animate {
      opacity: 1;
      transform: translateY(0);
    }

    /* Mobile responsiveness */
    @media (max-width: 991.98px) {
      .hero-content h1 {
        font-size: 2.5rem;
      }
      
      .hero-content p {
        font-size: 1.1rem;
      }
      
      .section-title h2 {
        font-size: 2rem;
      }
      
      .navbar-nav {
        background: rgba(0, 0, 0, 0.95);
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: 1rem;
        backdrop-filter: blur(20px);
      }
      
      .navbar .nav-link {
        text-align: center;
        margin: 0.5rem 0;
      }
      
      .navbar .btn-outline-white {
        margin-top: 1rem;
        width: 100%;
      }
    }

    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark position-fixed w-100" style="z-index: 100;">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <i class="fa fa-car me-2"></i>
        Rental Mobil
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#beranda">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#layanan">Layanan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-sm btn-outline-white px-4 ms-2" href="login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section" id="beranda">
    <div class="container">
      <div class="row align-items-center hero-content">
        <div class="col-lg-8">
          <h1 class="display-3 fw-bold mb-4">Sewa Mobil Nyaman & Terpercaya</h1>
          <p class="lead mb-4">Nikmati kemudahan menyewa mobil dengan harga bersaing, pilihan armada lengkap, dan driver profesional. Solusi transportasi Anda dimulai di sini.</p>
          <a href="login.php" class="btn btn-hero">Telusuri!</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features-section" id="layanan">
    <div class="container">
      <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-8 section-title animate-on-scroll">
          <h2 class="mb-4">Keunggulan Kami</h2>
          <p>Kami hadir untuk memberikan pengalaman rental mobil terbaik dengan layanan premium dan kualitas terdepan</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 mb-5">
          <div class="card feature-card text-center p-4 animate-on-scroll">
            <div class="feature-icon primary">
              <i class="fa-solid fa-car"></i>
            </div>
            <h5 class="fw-bold">Armada Lengkap</h5>
            <p>Beragam pilihan mobil premium yang selalu terawat dan siap memberikan kenyamanan perjalanan terbaik untuk Anda.</p>
          </div>
        </div>
        <div class="col-lg-4 mb-5">
          <div class="card feature-card text-center p-4 animate-on-scroll">
            <div class="feature-icon success">
              <i class="fa-solid fa-user-tie"></i>
            </div>
            <h5 class="fw-bold">Driver Profesional</h5>
            <p>Sopir berpengalaman, berlisensi resmi, ramah dan menguasai rute terbaik untuk memastikan perjalanan Anda aman dan nyaman.</p>
          </div>
        </div>
        <div class="col-lg-4 mb-5">
          <div class="card feature-card text-center p-4 animate-on-scroll">
            <div class="feature-icon warning">
              <i class="fa-solid fa-tags"></i>
            </div>
            <h5 class="fw-bold">Harga Transparan</h5>
            <p>Tanpa biaya tersembunyi, sistem harga yang jelas, kompetitif, dan sesuai dengan kualitas layanan premium yang kami berikan.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-lg-start text-center mb-3 mb-lg-0">
          <a class="navbar-brand d-flex align-items-center justify-content-center justify-content-lg-start" href="#">
            <i class="fa fa-car me-2"></i>
            Rental Mobil
          </a>
        </div>
        <div class="col-lg-6 text-lg-end text-center">
          <p>&copy; 2024 Rental Mobil. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="styling/assets/js/core/popper.min.js"></script>
  <script src="styling/assets/js/core/bootstrap.min.js"></script>
  <script src="styling/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="styling/assets/js/plugins/smooth-scrollbar.min.js"></script>
  
  <script>
    // Scroll animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      observer.observe(el);
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const hero = document.querySelector('.hero-section');
      if (hero) {
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
      }
    });
  </script>
</body>
</html>