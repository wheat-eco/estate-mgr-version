<?php
/**
 * East Ayrshire Rental Properties for Love View Estate
 */

// Page specific variables
$pageTitle = 'East Ayrshire Rental Properties';
$additionalCss = ['rental-properties'];

// Include database connection
require_once('includes/db.php');

// Include header
include('includes/header.php');

// Get East Ayrshire region ID
$regionQuery = "SELECT id FROM regions WHERE name = 'East Ayrshire'";
$regionResult = $conn->query($regionQuery);
$regionId = 0;

if ($regionResult && $regionResult->num_rows > 0) {
    $regionId = $regionResult->fetch_assoc()['id'];
}

// Fetch properties from database
$query = "SELECT p.*, a.name as area_name, 
          (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1) as image 
          FROM properties p 
          JOIN areas a ON p.area_id = a.id 
          JOIN regions r ON a.region_id = r.id 
          WHERE p.property_category = 'rent' AND r.id = ?
          ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $regionId);
$stmt->execute();
$result = $stmt->get_result();

$properties = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
}

// Count properties
$propertyCount = count($properties);
?>

<!-- East Ayrshire Rentals Section -->
<section class="rental-section">
  <div class="rental-container">
    <h1 class="rental-heading">TO RENT EAST AYRSHIRE</h1>
    <p class="rental-subheading">
      Discover our selection of rental properties in East Ayrshire. We currently have <?php echo $propertyCount; ?> properties available.
    </p>
    
    <div class="properties-list">
      <?php if ($propertyCount > 0): ?>
        <?php foreach($properties as $property): ?>
          <div class="property-item">
            <div class="property-image-container">
              <?php if(!empty($property['image'])): ?>
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
              <p class="property-location"><?php echo $property['location']; ?></p>
              
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
                <?php if(!empty($property['available_date'])): ?>
                <div class="property-feature">
                  <i class="fas fa-calendar"></i> <?php echo date('d-m-Y', strtotime($property['available_date'])); ?>
                </div>
                <?php endif; ?>
              </div>
              
              <div class="property-price-container">
                <div class="property-price">
                  £<?php echo number_format($property['price'], 0); ?> <span class="pcm">PCM</span>
                </div>
                <a href="/property-details.php?id=<?php echo $property['id']; ?>" class="property-button">More Info</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-properties">
          <p>No properties currently available in East Ayrshire. Please check back soon or contact us for more information.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>