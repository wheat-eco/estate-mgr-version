<?php
/**
 * Header component for Love View Estate website
 */
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Love View Estate' : 'Love View Estate - Premium Real Estate'; ?></title>
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/header.css" />
    <link rel="stylesheet" href="/css/footer.css" />
    <?php if(isset($additionalCss) && is_array($additionalCss)): ?>
      <?php foreach($additionalCss as $css): ?>
        <link rel="stylesheet" href="/css/<?php echo $css; ?>.css" />
      <?php endforeach; ?>
    <?php endif; ?>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      /* Reduce header height for desktop */
      @media (min-width: 992px) {
        .header {
          height: auto;
        }
        .header-container {
          padding: 0.75rem 2rem;
        }
        .logo {
          max-height: 60px; /* Reduced from original size */
        }
        .main-nav .nav-list {
          gap: 1rem; /* Reduced spacing */
        }
        .nav-link {
          padding: 0.5rem 0.75rem; /* Reduced padding */
        }
        /* Fix for header overlap */
        main {
          padding-top: 140px; /* Adjust based on your reduced header height */
        }
      }
      
      /* Ensure header is fixed at the top but maintain dark background */
      .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        /* Removed the white background-color */
        /* Using the same background as in header.css */
        background: rgba(58, 58, 58, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }
    </style>
  </head>
  <body>
    <!-- Header Section -->
    <header class="header">
      <div class="header-container">
        <div class="logo-container">
          <a href="/index.php">
            <img
              src="/img/love-view-logo.png"
              alt="Love View Estate"
              class="logo"
            />
          </a>
        </div>

        <div class="nav-container">
          <nav class="main-nav top-nav">
            <ul class="nav-list">
              <li class="nav-item dropdown">
                <a href="#" class="nav-link"
                  >ABOUT <i class="fas fa-chevron-down"></i
                ></a>
                <div class="dropdown-content">
                  <a href="/our-story.php">Our Story</a>
                  <a href="/team.php">Team</a>
                  <a href="/testimonials.php">Testimonials</a>
                </div>
              </li>
              <li class="nav-item"><a href="/selling.php" class="nav-link">SELLING</a></li>
              <li class="nav-item">
                <a href="/for-sale-north-ayrshire.php" class="nav-link">FOR SALE NORTH AYRSHIRE</a>
              </li>
              <li class="nav-item">
                <a href="/for-sale-east-ayrshire.php" class="nav-link">FOR SALE EAST AYRSHIRE</a>
              </li>
              <li class="nav-item">
                <a href="/valuation.php" class="nav-link highlight-button"
                  >INSTANT VALUATION</a
                >
              </li>
            </ul>
          </nav>
 <nav class="main-nav bottom-nav">
            <ul class="nav-list">
              <li class="nav-item">
                <a href="/financial-services.php" class="nav-link">FINANCIAL SERVICES</a>
              </li>
              <li class="nav-item">
                <a href="/landlords.php" class="nav-link">LANDLORDS</a>
              </li>
              <li class="nav-item dropdown">
                <a href="/for-rent.php" class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'for-rent') !== false || strpos($_SERVER['PHP_SELF'], 'to-rent') !== false || strpos($_SERVER['PHP_SELF'], 'available-properties') !== false) ? 'active' : ''; ?>"
                  >FOR RENT <i class="fas fa-chevron-down"></i
                ></a>
                <div class="dropdown-content">
                  <a href="/available-properties.php">Available Properties</a>
                  <a href="/to-rent-north-ayrshire.php">North Ayrshire</a>
                  <a href="/to-rent-east-ayrshire.php">East Ayrshire</a>
                  <a href="/rental-guide.php">Rental Guide</a>
                 
                </div>
              </li>
            </ul>
          </nav>
        </div>

        <div class="social-container">
          <a href="#" class="social-link" aria-label="Facebook"
            ><i class="fab fa-facebook-f"></i
          ></a>
          <a href="#" class="social-link" aria-label="Twitter"
            ><i class="fab fa-x-twitter"></i
          ></a>
          <a href="#" class="social-link" aria-label="Instagram"
            ><i class="fab fa-instagram"></i
          ></a>
          <a href="#" class="social-link" aria-label="Email"
            ><i class="fas fa-envelope"></i
          ></a>
        </div>

        <button class="mobile-menu-toggle" aria-label="Toggle menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu">
      <button class="mobile-menu-close" aria-label="Close menu">
        <i class="fas fa-times"></i>
      </button>
      <nav>
        <ul>
          <li class="mobile-dropdown">
            <a href="#">ABOUT <i class="fas fa-chevron-down"></i></a>
            <ul class="mobile-dropdown-content">
              <li><a href="/our-story.php">Our Story</a></li>
              <li><a href="/team.php">Team</a></li>
              <li><a href="/testimonials.php">Testimonials</a></li>
            </ul>
          </li>
          <li><a href="/selling.php">SELLING</a></li>
          <li><a href="/for-sale-north-ayrshire.php">FOR SALE NORTH AYRSHIRE</a></li>
          <li><a href="/for-sale-east-ayrshire.php">FOR SALE EAST AYRSHIRE</a></li>
          <li><a href="/financial-services.php">FINANCIAL SERVICES</a></li>
          <li><a href="/landlords.php">LANDLORDS</a></li>
          <li class="mobile-dropdown">
            <a href="/for-rent.php">FOR RENT <i class="fas fa-chevron-down"></i></a>
            <ul class="mobile-dropdown-content">
              <a href="/available-properties.php">Available Properties</a>
              <a href="/to-rent-north-ayrshire.php">North Ayrshire</a>
              <a href="/to-rent-east-ayrshire.php">East Ayrshire</a>
              <a href="/rental-guide.php">Rental Guide</a>
              <a href="/apply-now.php">Apply Now</a>
            </ul>
          </li>
          <li><a href="/valuation.php" class="mobile-highlight">INSTANT VALUATION</a></li>
        </ul>
      </nav>
      <div class="mobile-social">
        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
      </div>
    </div>

    <!-- Overlay for closing mobile menu when clicking outside -->
    <div class="mobile-menu-overlay"></div>

    <!-- Main Content -->
    <main>