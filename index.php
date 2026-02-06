<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FormBuilderPro </title>

  <!-- Google Fonts + Bootstrap Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AOS (Animate On Scroll) CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #fdfdfd;
      color: #333;
      line-height: 1.6;
    }

    a {
      text-decoration: none;
    }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar h1 {
      color: #6a1b9a;
      font-size: 1.8rem;
    }

    .navbar .nav-links a {
      margin-left: 1rem;
      padding: 0.5rem 1rem;
      color: #6a1b9a;
      font-weight: 600;
      border: 2px solid #6a1b9a;
      border-radius: 5px;
      transition: 0.3s ease;
    }

    .navbar .nav-links a:hover {
      background: #6a1b9a;
      color: white;
    }

    /* Hero Section */
    .hero {
      padding: 5rem 2rem;
      background: linear-gradient(135deg, #e1bee7, #fff8e1);
      text-align: center;
    }

    .hero h2 {
      font-size: 2.5rem;
      color: #4a148c;
      margin-bottom: 1rem;
    }

    .hero p {
      max-width: 700px;
      margin: auto;
      font-size: 1.1rem;
    }

    .hero .btn-group {
      margin-top: 2rem;
    }

    .hero .btn-group a {
      display: inline-block;
      margin: 0 0.5rem;
      padding: 0.8rem 1.5rem;
      background: #6a1b9a;
      color: white;
      border-radius: 25px;
      font-weight: bold;
      transition: 0.3s;
    }

    .hero .btn-group a:hover {
      background: #4a0072;
    }

    /* Features Section */
    .features {
      background: #fafafa;
      padding: 4rem 2rem;
      text-align: center;
    }

    .features h3 {
      font-size: 2rem;
      margin-bottom: 2rem;
      color: #4a148c;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2rem;
      max-width: 1000px;
      margin: auto;
    }

    .feature {
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.07);
      transition: 0.3s ease;
    }

    .feature:hover {
      transform: translateY(-5px);
    }

    /* Pulse Animation */
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.15); opacity: 0.7; }
      100% { transform: scale(1); opacity: 1; }
    }

    .feature i {
      font-size: 2.5rem;
      color: #6a1b9a;
      margin-bottom: 1rem;
      animation: pulse 2s infinite;
    }

    /* How It Works */
    .how-it-works {
      padding: 4rem 2rem;
      background: linear-gradient(to right, #f3e5f5, #e8f5e9);
      text-align: center;
    }

    .how-it-works h3 {
      font-size: 2rem;
      color: #4a148c;
      margin-bottom: 2rem;
    }

    .steps {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      max-width: 800px;
      margin: auto;
    }

    .step {
      background: white;
      padding: 1rem 2rem;
      border-radius: 10px;
      text-align: left;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .step h4 {
      color: #6a1b9a;
    }

    
    /* Footer */
    .footer {
      text-align: center;
      padding: 2rem;
      background: #6a1b9a;
      color: white;
    }

    @media (max-width: 768px) {
      .hero h2 {
        font-size: 2rem;
      }

      .feature-grid {
        grid-template-columns: 1fr;
      }

      .step {
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <h1>FormBuilderPro</h1>
    <div class="nav-links">
      <a href="login.php">Login</a>
      <a href="signup.php">Sign Up</a>
    </div>
  </div>

  <!-- Hero -->
  <section class="hero" data-aos="fade-up">
    <h2>Create Forms. Share Links. Get Results Instantly.</h2>
    <p>Design beautiful forms with drag & drop. Share a public link. Automatically send responses to email.</p>
    <div class="btn-group">
      <a href="signup.php">Get Started</a>
      <a href="login.php">Login</a>
    </div>
  </section>

  <!-- Features -->
  <section class="features">
    <h3 data-aos="zoom-in">✨ Features</h3>
    <div class="feature-grid">
      <div class="feature" data-aos="fade-up" data-aos-delay="100">
        <i class="bi bi-ui-checks-grid"></i>
        <h4>Drag & Drop Builder</h4>
        <p>Design forms easily with a simple UI. Add inputs, checkboxes, radios, and more.</p>
      </div>
      <div class="feature" data-aos="fade-up" data-aos-delay="300">
        <i class="bi bi-link-45deg"></i>
        <h4>Share Form Links</h4>
        <p>Every form gets a public URL you can share with anyone.</p>
      </div>
      <div class="feature" data-aos="fade-up" data-aos-delay="500">
        <i class="bi bi-envelope-fill"></i>
        <h4>Email Submissions</h4>
        <p>All submissions are stored and sent directly to the user’s email.</p>
      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="how-it-works">
    <h3 data-aos="fade-up">🚀 How It Works</h3>
    <div class="steps">
      <div class="step" data-aos="fade-right">
        <h4>1. Sign Up & Login</h4>
        <p>Create an account and access your dashboard.</p>
      </div>
      <div class="step" data-aos="fade-left">
        <h4>2. Build Your Form</h4>
        <p>Use our drag & drop tools to create a customized form.</p>
      </div>
      <div class="step" data-aos="fade-right">
        <h4>3. Share & Collect</h4>
        <p>Share the form link and get responses in your inbox instantly.</p>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
 

  <!-- Footer -->
  <div class="footer">
        <p>"FormBuilderPro made it so easy to collect event registrations. The drag & drop UI is super smooth and intuitive!" </p>

    &copy; <?= date('Y') ?> FormBuilderPro. Built with ❤️ for form creators.
  </div>

  <!-- AOS Animation Script -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true,
      duration: 1000
    });
  </script>
</body>
</html>
