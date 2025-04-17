<?php
/**
 * For Sale East Ayrshire page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Properties For Sale in East Ayrshire';
$additionalCss = ['selling'];

// Include header
include('includes/header.php');

// In a real application, you would fetch this data from a database
// This is just sample data for demonstration
$properties = [
    [
        'id' => 201,
        'title' => 'Detached House in Kilmarnock',
        'location' => 'London Road, Kilmarnock',
        'area' => 'Kilmarnock',
        'bedrooms' => 4,
        'bathrooms' => 2,
        'type' => 'Detached',
        'postcode' => 'KA3',
        'price' => 265000,
        'status' => 'FOR SALE',
        'image' => '/img/property-sale5.jpg',
        'region' => 'East Ayrshire'
    ],
    [
        'id' => 202,
        'title' => 'Semi-Detached House in Stewarton',
        'location' => 'Lainshaw Street, Stewarton',
        'area' => 'Stewarton',
        'bedrooms' => 3,
        'bathrooms' => 1,
        'type' => 'Semi-Detached',
        'postcode' => 'KA3',
        'price' => 195000,
        'status' => 'FOR SALE',
        'image' => '/img/property-sale6.jpg',
        'region' => 'East Ayrshire'
    ],
    [
        'id' => 203,
        'title' => 'Terraced House in Cumnock',
        'location' => 'Townhead Street, Cumnock',
        'area' => 'Cumnock',
        'bedrooms' => 2,
        'bathrooms' => 1,
        'type' => 'Terraced',
        'postcode' => 'KA18',
        'price' => 115000,
        'status' => 'UNDER OFFER',
        'image' => '/img/property-sale7.jpg',
        'region' => 'East Ayrshire'
    ],
    [
        'id' => 204,
        'title' => 'Bungalow in Darvel',
        'location' => 'East Main Street, Darvel',
        'area' => 'Darvel',
        'bedrooms' => 3,
        'bathrooms' => 2,
        'type' => 'Bungalow',
        'postcode' => 'KA17',
        'price' => 225000,
        'status' => 'FOR SALE',
        'image' => '/img/property-sale8.jpg',
        'region' => 'East Ayrshire'
    ]
];
?>

<!-- For Sale East Ayrshire Section -->
<section class="sale-section">
  <div class="sale-container">
    <h1 class="sale-heading">FOR SALE IN EAST AYRSHIRE</h1>
    <p class="sale-subheading">
      Browse our selection of properties for sale in East Ayrshire, including Kilmarnock, Stewarton, Cumnock, and surrounding areas.
    </p>
    
    <!-- Filter Section -->
    <div class="filter-section">
      <form action="/for-sale-east-ayrshire.php" method="get" class="properties-filter">
        <div class="filter-group">
          <label for="area">Area</label>
          <select id="area" name="area">
            <option value="">All Areas</option>
            <option value="Kilmarnock">Kilmarnock</option>
            <option value="Stewarton">Stewarton</option>
            <option value="Cumnock">Cumnock</option>
            <option value="Darvel">Darvel</option>
            <option value="Galston">Galston</option>
          </select>
        </div>
        
        <div class="filter-group">
          <label for="bedrooms">Bedrooms</label>
          <select id="bedrooms" name="bedrooms">
            <option value="">Any</option>
            <option value="1">1+</option>
            <option value="2">2+</option>
            <option value="3">3+</option>
            <option value="4">4+</option>
          </select>
        </div>
        
        <div class="filter-group">
          <label for="price">Max Price</label>
          <select id="price" name="price">
            <option value="">Any</option>
            <option value="150000">£150,000</option>
            <option value="200000">£200,000</option>
            <option value="250000">£250,000</option>
            <option value="300000">£300,000</option>
            <option value="400000">£400,000+</option>
          </select>
        </div>
        
        <div class="filter-group">
          <label for="property-type">Property Type</label>
          <select id="property-type" name="type">
            <option value="">All Types</option>
            <option value="Apartment">Apartment</option>
            <option value="Terraced">Terraced</option>
            <option value="Semi-Detached">Semi-Detached</option>
            <option value="Detached">Detached</option>
            <option value="Bungalow">Bungalow</option>
          </select>
        </div>
        
        <button type="submit" class="filter-button">Apply Filters</button>
      </form>
    </div>
    
    <div class="properties-list">
      <?php foreach($properties as $property): ?>
        <div class="property-item">
          <div class="property-image-container">
            <img src="<?php echo $property['image']; ?>" alt="<?php echo $property['title']; ?>" class="property-image">
            <?php if($property['status']): ?>
              <div class="property-status"><?php echo $property['status']; ?></div>
            <?php endif; ?>
          </div>
          <div class="property-details">
            <h2 class="property-title"><?php echo $property['title']; ?></h2>
            <p class="property-location"><?php echo $property['location']; ?></p>
            
            <div class="property-features">
              <div class="property-feature">
                <i class="fas fa-map-marker-alt"></i> <?php echo $property['area']; ?>
              </div>
              <div class="property-feature">
                <i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?> Bedrooms
              </div>
              <div class="property-feature">
                <i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?> Bathroom
              </div>
              <div class="property-feature">
                <i class="fas fa-home"></i> <?php echo $property['type']; ?>
              </div>
              <div class="property-feature">
                <i class="fas fa-map-pin"></i> <?php echo $property['postcode']; ?>
              </div>
            </div>
            
            <div class="property-price-container">
              <div class="property-price">
                £<?php echo number_format($property['price']); ?>
              </div>
              <a href="/property-sale-details.php?id=<?php echo $property['id']; ?>" class="property-button">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
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