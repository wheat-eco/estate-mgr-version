<?php
/**
 * Valuation page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Free Property Valuation';
$additionalCss = ['valuation'];

// Include header
include('includes/header.php');
?>

<!-- Valuation Hero Section -->
<section class="valuation-hero">
  <div class="valuation-container">
    <div class="valuation-hero-content">
      <h1>Free Instant Online Valuation</h1>
      <p>We offer free instant online sales and rental valuations of your property, simply click the button on this page to get your results. Do remember that every property is different and we would always strongly advise you to take up our free no-obligation valuation in person by one of our experts to give you an accurate valuation of your property.</p>
    </div>
    <div class="valuation-hero-image">
      <img src="/img/team-photo.jpg" alt="Love View Estate Team" />
    </div>
  </div>
</section>

<!-- Valuation Options Section -->
<section class="valuation-options-section">
  <div class="valuation-container">
    <div class="valuation-options-wrapper">
      <a href="/sales-valuation.php" class="valuation-option-card">
        <h2>Sales Valuation</h2>
        <i class="fas fa-arrow-right"></i>
      </a>
      <a href="/rental-valuation.php" class="valuation-option-card">
        <h2>Rental Valuation</h2>
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>
    
    <div class="back-to-home">
      <a href="/" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to home
      </a>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>