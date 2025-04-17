<?php
/**
 * For Rent main page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Properties For Rent';
$additionalCss = ['rental-properties'];

// Include header
include('includes/header.php');
?>

<!-- Rental Main Section -->
<section class="rental-section">
  <div class="rental-container">
    <h1 class="rental-heading">FOR RENT</h1>
    <p class="rental-subheading">
      At Love View Estate, we offer a wide range of rental properties throughout Ayrshire. 
      Whether you're looking for a cozy apartment, a family home, or a luxury property, 
      our experienced team can help you find the perfect place to call home.
    </p>
    
    <div class="rental-locations">
      <!-- North Ayrshire Location Card -->
      <a href="/to-rent-north-ayrshire.php" class="location-card">
        <div class="location-image">
          <img src="/img/north-ayrshire.jpg" alt="North Ayrshire Properties">
        </div>
        <div class="location-content">
          <h2 class="location-title">To Rent North Ayrshire</h2>
          <p class="location-description">
            Discover our selection of rental properties in North Ayrshire, including Saltcoats, 
            Kilwinning, Irvine, and surrounding areas.
          </p>
          <span class="location-button">View Properties</span>
        </div>
      </a>
      
      <!-- East Ayrshire Location Card -->
      <a href="/to-rent-east-ayrshire.php" class="location-card">
        <div class="location-image">
          <img src="/img/east-ayrshire.jpg" alt="East Ayrshire Properties">
        </div>
        <div class="location-content">
          <h2 class="location-title">To Rent East Ayrshire</h2>
          <p class="location-description">
            Browse our range of rental properties in East Ayrshire, including Kilmarnock, 
            Cumnock, Stewarton, and surrounding areas.
          </p>
          <span class="location-button">View Properties</span>
        </div>
      </a>
    </div>
    
    <div class="rental-process">
      <h2>Renting with Love View Estate</h2>
      <p>
        Our rental process is designed to be straightforward and hassle-free. We understand that 
        finding the right rental property is an important decision, and our experienced team is 
        here to guide you through every step of the process.
      </p>
      <p>
        To view our available properties, select a location above or visit our 
        <a href="/available-properties.php">Available Properties</a> page to see all current listings.
      </p>
      <p>
        For more information about renting with Love View Estate, please visit our 
        <a href="/rental-guide.php">Rental Guide</a> or <a href="/contact.php">contact us</a> directly.
      </p>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>