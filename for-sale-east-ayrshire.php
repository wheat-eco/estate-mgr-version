<?php
/**
 * For Sale East Ayrshire page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Properties For Sale in East Ayrshire';
$additionalCss = ['selling'];

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

// Get filter parameters
$area = isset($_GET['area']) ? $_GET['area'] : '';
$bedrooms = isset($_GET['bedrooms']) ? intval($_GET['bedrooms']) : 0;
$price = isset($_GET['price']) ? intval($_GET['price']) : 0;
$propertyType = isset($_GET['type']) ? $_GET['type'] : '';

// Build query based on filters
$query = "SELECT p.*, a.name as area_name, 
          (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1) as image 
          FROM properties p 
          JOIN areas a ON p.area_id = a.id 
          JOIN regions r ON a.region_id = r.id 
          WHERE p.property_category = 'sale' AND r.id = ?";

$params = [$regionId];
$types = "i";

if (!empty($area)) {
    $query .= " AND a.name = ?";
    $params[] = $area;
    $types .= "s";
}

if ($bedrooms > 0) {
    $query .= " AND p.bedrooms >= ?";
    $params[] = $bedrooms;
    $types .= "i";
}

if ($price > 0) {
    $query .= " AND p.price <= ?";
    $params[] = $price;
    $types .= "i";
}

if (!empty($propertyType)) {
    $query .= " AND p.property_type = ?";
    $params[] = $propertyType;
    $types .= "s";
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
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

// Get areas for filter dropdown
$areasQuery = "SELECT a.name FROM areas a JOIN regions r ON a.region_id = r.id WHERE r.name = 'East Ayrshire' ORDER BY a.name";
$areasResult = $conn->query($areasQuery);
$areas = [];

if ($areasResult && $areasResult->num_rows > 0) {
    while ($row = $areasResult->fetch_assoc()) {
        $areas[] = $row['name'];
    }
}
?>

<!-- For Sale East Ayrshire Section -->
<section class="sale-section">
  <div class="sale-container">
    <h1 class="sale-heading">FOR SALE IN EAST AYRSHIRE</h1>
    <p class="sale-subheading">
      Browse our selection of properties for sale in East Ayrshire, including Kilmarnock, Stewarton, Cumnock, and surrounding areas.
      We currently have <?php echo $propertyCount; ?> properties available.
    </p>
    
    <!-- Filter Section -->
    <div class="filter-section">
      <form action="/for-sale-east-ayrshire.php" method="get" class="properties-filter">
        <div class="filter-group">
          <label for="area">Area</label>
          <select id="area" name="area">
            <option value="">All Areas</option>
            <?php foreach($areas as $areaName): ?>
              <option value="<?php echo $areaName; ?>" <?php echo ($area == $areaName) ? 'selected' : ''; ?>>
                <?php echo $areaName; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="filter-group">
          <label for="bedrooms">Bedrooms</label>
          <select id="bedrooms" name="bedrooms">
            <option value="">Any</option>
            <option value="1" <?php echo ($bedrooms == 1) ? 'selected' : ''; ?>>1+</option>
            <option value="2" <?php echo ($bedrooms == 2) ? 'selected' : ''; ?>>2+</option>
            <option value="3" <?php echo ($bedrooms == 3) ? 'selected' : ''; ?>>3+</option>
            <option value="4" <?php echo ($bedrooms == 4) ? 'selected' : ''; ?>>4+</option>
          </select>
        </div>
        
        <div class="filter-group">
          <label for="price">Max Price</label>
          <select id="price" name="price">
            <option value="">Any</option>
            <option value="150000" <?php echo ($price == 150000) ? 'selected' : ''; ?>>£150,000</option>
            <option value="200000" <?php echo ($price == 200000) ? 'selected' : ''; ?>>£200,000</option>
            <option value="250000" <?php echo ($price == 250000) ? 'selected' : ''; ?>>£250,000</option>
            <option value="300000" <?php echo ($price == 300000) ? 'selected' : ''; ?>>£300,000</option>
            <option value="400000" <?php echo ($price == 400000) ? 'selected' : ''; ?>>£400,000+</option>
          </select>
        </div>
        
        <div class="filter-group">
          <label for="property-type">Property Type</label>
          <select id="property-type" name="type">
            <option value="">All Types</option>
            <option value="Apartment" <?php echo ($propertyType == 'Apartment') ? 'selected' : ''; ?>>Apartment</option>
            <option value="Terraced" <?php echo ($propertyType == 'Terraced') ? 'selected' : ''; ?>>Terraced</option>
            <option value="Semi-Detached" <?php echo ($propertyType == 'Semi-Detached') ? 'selected' : ''; ?>>Semi-Detached</option>
            <option value="Detached" <?php echo ($propertyType == 'Detached') ? 'selected' : ''; ?>>Detached</option>
            <option value="Bungalow" <?php echo ($propertyType == 'Bungalow') ? 'selected' : ''; ?>>Bungalow</option>
          </select>
        </div>
        
        <button type="submit" class="filter-button">Apply Filters</button>
      </form>
    </div>
    
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
              </div>
              
              <div class="property-price-container">
                <div class="property-price">
                  £<?php echo number_format($property['price'], 0); ?>
                </div>
                <a href="/property-details.php?id=<?php echo $property['id']; ?>" class="property-button">View Details</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-properties">
          <p>No properties currently available in East Ayrshire matching your criteria. Please adjust your filters or check back soon.</p>
        </div>
      <?php endif; ?>
    </div>
    
    <div class="sale-cta">
      <p>Looking to sell your property in East Ayrshire? Contact our sales team for a free valuation.</p>
      <a href="/valuation.php" class="btn btn-primary">Get a Free Valuation</a>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>