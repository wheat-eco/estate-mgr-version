<?php
/**
 * Home page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Home';
$additionalCss = ['welcome', 'carousel', 'valuations', 'services', 'partners'];

// Include header
include('includes/header.php');
?>

<!-- Hero Section with Carousel -->
<section class="hero">
  <div class="carousel-container">
    <div class="carousel">
      <div
        class="carousel-slide active"
        style="background-image: url('img/1.jpg')"
      ></div>
      <div
        class="carousel-slide"
        style="background-image: url('img/2.jpg')"
      ></div>
      <div
        class="carousel-slide"
        style="background-image: url('img/3.jpg')"
      ></div>
    </div>
  </div>
  <div class="hero-content">
    <h1>Welcome to Love View Estate</h1>
    <p>Your premier real estate partner for exceptional properties</p>
    <div class="hero-buttons">
      <a href="for-sale-north-ayrshire.php" class="btn btn-primary">Explore Properties</a>
      <a href="contact.php" class="btn btn-secondary">Contact Us</a>
    </div>
  </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section">
  <div class="welcome-container">
    <h2 class="welcome-heading">
      WELCOME TO LOVE VIEW ESTATE LETTING & SALES
    </h2>
    <h3 class="welcome-subheading">
      AWARD WINNING SPECIALISTS IN BOTH ESTATE AGENCY & LETTINGS.
    </h3>
    <p class="welcome-text">
      At Love View Estate we take pride in the service that we provide to
      our clients, always working with our clients best interest & with
      integrity. Our friendly, experienced team are on hand 7 days a week
      to assist you every step of the way. With 2 Branches in strategic
      locations in North Ayrshire (Saltcoats) & East Ayrshire (Kilmarnock)
      we have Ayrshire covered.
    </p>
  </div>
</section>

<!-- Valuations Section -->
<section class="valuations-section">
  <div class="valuations-container">
    <div class="valuations-image">
      <img src="img/2.jpg" alt="Interior of a modern property" />
    </div>
    <div class="valuations-content">
      <h2 class="valuations-heading">
        Valuations for Selling or Renting
      </h2>
      <p class="valuations-text">
        At Love View Estate we offer free valuations whether you are
        looking to Sell or Rent out your property. Our team are on hand to
        provide knowledgable advice on the Ayrshire property market.
      </p>
      <p class="valuations-cta">
        If you have a property in Ayrshire Contact us to arrange your free
        no obligation property valuation
      </p>
      <a href="valuation.php" class="btn btn-dark">Request Valuation</a>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="services-section">
  <div class="services-overlay"></div>
  <div class="services-container">
    <div class="services-grid">
      <a href="for-sale-north-ayrshire.php" class="service-card">
        <div
          class="service-image"
          style="
            background-image: url('img/1.jpg');
          "
        >
          <div class="service-overlay"></div>
          <h3 class="service-title">FOR SALE</h3>
          <div class="service-arrow">
            <i class="fas fa-arrow-right"></i>
          </div>
        </div>
      </a>

      <a href="for-rent.php" class="service-card">
        <div
          class="service-image"
          style="
            background-image: url('img/2.jpg');
          "
        >
          <div class="service-overlay"></div>
          <h3 class="service-title">TO LET</h3>
          <div class="service-arrow">
            <i class="fas fa-arrow-right"></i>
          </div>
        </div>
      </a>

      <a href="rental-guide.php" class="service-card">
        <div
          class="service-image"
          style="
            background-image: url('img/3.jpg');
          "
        >
          <div class="service-overlay"></div>
          <h3 class="service-title">RENTING</h3>
          <div class="service-arrow">
            <i class="fas fa-arrow-right"></i>
          </div>
        </div>
      </a>

      <a href="selling.php" class="service-card">
        <div
          class="service-image"
          style="
            background-image: url('img/4.jpg');
          "
        >
          <div class="service-overlay"></div>
          <h3 class="service-title">SELLING</h3>
          <div class="service-arrow">
            <i class="fas fa-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- Partners Section -->
<section class="partners-section">
  <div class="partners-container">
    <div class="partners-grid">
      <a
        href="https://www.rightmove.co.uk/"
        target="_blank"
        rel="noopener noreferrer"
        class="partner-logo"
      >
        <img
          src="img/1.jpg"
          alt="Rightmove"
        />
      </a>
      <a
        href="https://www.zoopla.co.uk/"
        target="_blank"
        rel="noopener noreferrer"
        class="partner-logo"
      >
        <img
          src="img/4.jpg"
          alt="Zoopla"
        />
      </a>
      <a
        href="https://www.theprs.co.uk/"
        target="_blank"
        rel="noopener noreferrer"
        class="partner-logo"
      >
        <img
          src="img/4.jpg"
          alt="Property Redress Scheme"
        />
      </a>
      <a
        href="https://www.fsb.org.uk/"
        target="_blank"
        rel="noopener noreferrer"
        class="partner-logo"
      >
        <img
          src="img/3.jpg"
          alt="Federation of Small Businesses"
        />
      </a>
      <a
        href="https://www.ayrshire-chamber.org/"
        target="_blank"
        rel="noopener noreferrer"
        class="partner-logo"
      >
        <img
          src="img/2.jpg"
          alt="Ayrshire Chamber of Commerce and Industry"
        />
      </a>
      <a
        href="https://www.safedepositsscotland.com/"
        target="_blank"
        rel="noopener noreferrer"
        class="partner-logo"
      >
        <img
          src="img/1.jpg"
          alt="SafeDeposits Scotland"
        />
      </a>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>