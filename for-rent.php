<?php
/**
 * For Rent main page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Properties For Rent';
$additionalCss = ['rental-properties'];

// Include database connection
require_once 'includes/db.php';

// Get regions for rental properties
$regionsQuery = "SELECT r.id, r.name, r.slug, COUNT(p.id) as property_count 
                FROM regions r 
                LEFT JOIN areas a ON r.id = a.region_id 
                LEFT JOIN properties p ON a.id = p.area_id AND p.property_category = 'rent' 
                GROUP BY r.id 
                HAVING property_count > 0 
                ORDER BY r.name";
$regionsResult = $conn->query($regionsQuery);

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
      <?php if ($regionsResult->num_rows > 0): ?>
        <?php while ($region = $regionsResult->fetch_assoc()): ?>
          <!-- Region Location Card -->
          <a href="/to-rent-<?php echo $region['slug']; ?>.php" class="location-card">
            <div class="location-image">
              <img src="/img/<?php echo ($region['id'] % 2 == 0) ? '2' : '1'; ?>.jpg" 
                   alt="<?php echo $region['name']; ?> Properties">
            </div>
            <div class="location-content">
              <h2 class="location-title">To Rent <?php echo $region['name']; ?></h2>
              <p class="location-description">
                Discover our selection of rental properties in <?php echo $region['name']; ?>.
              </p>
              <span class="location-button">View Properties</span>
            </div>
          </a>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No rental properties available at the moment. Please check back soon.</p>
      <?php endif; ?>
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

<style>
.rental-process {
    background-color: #fff;
    border-radius: 12px;
    padding: 2.5rem;
    margin-top: 3rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  }
  
  .rental-process h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #323232;
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  .rental-process p {
    font-size: 1rem;
    line-height: 1.8;
    color: #666;
    margin-bottom: 1.5rem;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
  }
  
  .rental-process a {
    color: #e4b611;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  
  .rental-process a:hover {
    color: #c49a0f;
    text-decoration: underline;
  }
</style>
<?php
// Include footer
include('includes/footer.php');
?>