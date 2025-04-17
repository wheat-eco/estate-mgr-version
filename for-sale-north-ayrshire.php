<?php
/**
 * For Sale North Ayrshire page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Properties For Sale in North Ayrshire';
$additionalCss = ['selling'];

// Include header
include('includes/header.php');

// In a real application, you would fetch this data from a database
// This is just sample data for demonstration
$properties = [
    [
        'id' => 101,
        'title' => 'Detached House in Kilwinning',
        'location' => 'Pennyburn, Kilwinning',
        'area' => 'Kilwinning',
        'bedrooms' => 4,
        'bathrooms' => 2,
        'type' => 'Detached',
        'postcode' => 'KA13',
        'price' => 275000,
        'status' => 'FOR SALE',
        'image' => '/img/property-sale1.jpg',
        'region' => 'North Ayrshire'
    ],
    [
        'id' => 102,
        'title' => 'Semi-Detached House in Irvine',
        'location' => 'Bourtreehill, Irvine',
        'area' => 'Irvine',
        'bedrooms' => 3,
        'bathrooms' => 1,
        'type' => 'Semi-Detached',
        'postcode' => 'KA11',
        'price' => 185000,
        'status' => 'FOR SALE',
        'image' => '/img/property-sale2.jpg',
        'region' => 'North Ayrshire'
    ],
    [
        'id' => 103,
        'title' => 'Apartment in Saltcoats',
        'location' => 'Harbour Street, Saltcoats',
        'area' => 'Saltcoats',
        'bedrooms' => 2,
        'bathrooms' => 1,
        'type' => 'Apartment',
        'postcode' => 'KA21',
        'price' => 125000,
        'status' => 'UNDER OFFER',
        'image' => '/img/property-sale3.jpg',
        'region' => 'North Ayrshire'
    ],
    [
        'id' => 104,
        'title' => 'Bungalow in Largs',
        'location' => 'Brisbane Road, Largs',
        'area' => 'Largs',
        'bedrooms' => 3,
        'bathrooms' => 2,
        'type' => 'Bungalow',
        'postcode' => 'KA30',
        'price' => 295000,
        'status' => 'FOR SALE',
        'image' => '/img/property-sale4.jpg',
        'region' => 'North Ayrshire'
    ]
];
?>

<!-- For Sale North Ayrshire Section -->
<section class="sale-section">
  <div class="sale-container">
    <h1 class="sale-heading">FOR SALE IN NORTH AYRSHIRE</h1>
    <p class="sale-subheading">
      Browse our selection of properties for sale in North Ayrshire, including Kilwinning, Irvine, Saltcoats, and surrounding areas.
    </p>
    
    <!-- Filter Section -->
    <div class="filter-section">
      <form action="/for-sale-north-ayrshire.php" method="get" class="properties-filter">
        <div class="filter-group">
          <label for="area">Area</label>
          <select id="area" name="area">
            <option value="">All Areas</option>
            <option value="Kilwinning">Kilwinning</option>
            <option value="Irvine">Irvine</option>
            <option value="Saltcoats">Saltcoats</option>
            <option value="Largs">Largs</option>
            <option value="Ardrossan">Ardrossan</option>
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
      <p>Looking to sell your property in North Ayrshire? Contact our sales team for a free valuation.</p>
      <a href="/valuation.php" class="btn btn-primary">Get a Free Valuation</a>
    </div>
  </div>
</section>

<!-- CSS for Sale Pages -->
<style>
/* Sale Pages Styles */
.sale-section {
  padding: 2rem 0 4rem;
}

.sale-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.sale-heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #323232;
  text-align: center;
  margin-bottom: 1rem;
}

.sale-subheading {
  font-size: 1.1rem;
  color: #666;
  text-align: center;
  margin-bottom: 2.5rem;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
}

/* Property List Styling */
.properties-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.property-item {
  background-color: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.property-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.property-image-container {
  position: relative;
  height: 220px;
  overflow: hidden;
}

.property-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.property-item:hover .property-image {
  transform: scale(1.05);
}

.property-status {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background-color: #e4b611;
  color: #323232;
  font-weight: 600;
  font-size: 0.8rem;
  padding: 0.5rem 1rem;
  border-radius: 50px;
}

.property-details {
  padding: 1.5rem;
}

.property-title {
  font-size: 1.3rem;
  font-weight: 600;
  color: #323232;
  margin-bottom: 0.5rem;
}

.property-location {
  font-size: 1rem;
  color: #666;
  margin-bottom: 1.5rem;
}

.property-features {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.property-feature {
  font-size: 0.9rem;
  color: #666;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.property-feature i {
  color: #e4b611;
}

.property-price-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.property-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: #323232;
}

.property-button {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background-color: #e4b611;
  color: #323232;
  font-weight: 600;
  text-decoration: none;
  border-radius: 50px;
  transition: all 0.3s ease;
}

.property-button:hover {
  background-color: #f5c728;
  transform: translateY(-2px);
}

/* CTA Section */
.sale-cta {
  background-color: #f9f9f9;
  border-radius: 12px;
  padding: 2.5rem;
  margin-top: 4rem;
  text-align: center;
  border: 1px solid #eee;
}

.sale-cta p {
  font-size: 1.2rem;
  margin-bottom: 1.5rem;
  color: #323232;
}

.btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  text-decoration: none;
  border-radius: 50px;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #e4b611;
  color: #323232;
}

.btn-primary:hover {
  background-color: #f5c728;
  transform: translateY(-2px);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
  .properties-list {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .properties-list {
    grid-template-columns: 1fr;
  }
  
  .sale-heading {
    font-size: 2rem;
  }
  
  .property-features {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .property-price-container {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }
}
</style>

<?php
// Include footer
include('includes/footer.php');
?>