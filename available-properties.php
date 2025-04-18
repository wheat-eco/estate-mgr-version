<?php
/**
 * All Available Rental Properties for Love View Estate
 */

// Page specific variables
$pageTitle = 'Available Rental Properties';
$additionalCss = ['rental-properties'];

// Include database connection
require_once 'includes/db.php';

// Get all available rental properties
$propertiesQuery = "SELECT p.*, a.name as area_name, r.name as region_name, 
                   (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1) as image 
                   FROM properties p 
                   JOIN areas a ON p.area_id = a.id 
                   JOIN regions r ON a.region_id = r.id 
                   WHERE p.property_category = 'rent' 
                   ORDER BY p.created_at DESC";
$propertiesResult = $conn->query($propertiesQuery);

// Include header
include('includes/header.php');
?>

<!-- Available Properties Section -->
<section class="rental-section">
  <div class="rental-container">
    <h1 class="rental-heading">AVAILABLE PROPERTIES</h1>
    <p class="rental-subheading">
      Browse our current selection of available rental properties across Ayrshire
    </p>
    
    <div class="properties-list">
      <?php if ($propertiesResult->num_rows > 0): ?>
        <?php while ($property = $propertiesResult->fetch_assoc()): ?>
          <div class="property-item">
            <div class="property-image-container">
              <?php if ($property['image']): ?>
                <img src="<?php echo $property['image']; ?>" alt="<?php echo $property['title']; ?>" class="property-image">
              <?php else: ?>
                <img src="/img/property-placeholder.jpg" alt="<?php echo $property['title']; ?>" class="property-image">
              <?php endif; ?>
              <?php if($property['status']): ?>
                <div class="property-status"><?php echo $property['status']; ?></div>
              <?php endif; ?>
            </div>
            <div class="property-details">
              <h2 class="property-title"><?php echo $property['title']; ?></h2>
              <p class="property-location"><?php echo $property['location']; ?> (<?php echo $property['region_name']; ?>)</p>
              
              <div class="property-features">
                <div class="property-feature">
                  <i class="fas fa-map-marker-alt"></i> <?php echo $property['area_name']; ?>
                </div>
                <div class="property-feature">
                  <i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?> Bedrooms
                </div>
                <div class="property-feature">
                  <i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?> Bathroom<?php echo $property['bathrooms'] > 1 ? 's' : ''; ?>
                </div>
                <div class="property-feature">
                  <i class="fas fa-home"></i> <?php echo $property['property_type']; ?>
                </div>
                <div class="property-feature">
                  <i class="fas fa-map-pin"></i> <?php echo $property['postcode']; ?>
                </div>
                <div class="property-feature">
                  <i class="fas fa-calendar"></i> <?php echo date('d-m-Y', strtotime($property['available_date'])); ?>
                </div>
              </div>
              
              <div class="property-price-container">
                <div class="property-price">
                  £<?php echo $property['price']; ?> <span class="pcm">PCM</span>
                </div>
                <a href="/property-details.php?id=<?php echo $property['id']; ?>" class="property-button">More Info</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="no-properties">
          <p>No rental properties available at the moment. Please check back soon.</p>
        </div>
      <?php endif; ?>
    </div>
    
    <div class="rental-cta">
      <p>Can't find what you're looking for? Contact our rental team for assistance.</p>
      <a href="/contact.php" class="btn btn-primary">Contact Us</a>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>