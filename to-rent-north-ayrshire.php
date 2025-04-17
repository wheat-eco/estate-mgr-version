<?php
/**
 * North Ayrshire Rental Properties for Love View Estate
 */

// Page specific variables
$pageTitle = 'North Ayrshire Rental Properties';
$additionalCss = ['rental-properties'];

// Include header
include('includes/header.php');

// In a real application, you would fetch this data from a database
// This is just sample data for demonstration
$properties = [
    [
        'id' => 1,
        'title' => 'Bridgend, Kilwinning',
        'location' => 'Bridgend, Kilwinning',
        'area' => 'Kilwinning',
        'bedrooms' => 3,
        'bathrooms' => 1,
        'type' => 'Terraced',
        'postcode' => 'KA13',
        'price' => 800,
        'status' => 'LET AGREED',
        'available_date' => '28-02-2025',
        'image' => '/img/property1.jpg'
    ],
    [
        'id' => 2,
        'title' => 'Modern Apartment in Saltcoats',
        'location' => 'Harbour Street, Saltcoats',
        'area' => 'Saltcoats',
        'bedrooms' => 2,
        'bathrooms' => 1,
        'type' => 'Apartment',
        'postcode' => 'KA21',
        'price' => 650,
        'status' => 'AVAILABLE',
        'available_date' => '15-03-2025',
        'image' => '/img/property2.jpg'
    ],
    [
        'id' => 3,
        'title' => 'Family Home in Irvine',
        'location' => 'Castlepark, Irvine',
        'area' => 'Irvine',
        'bedrooms' => 4,
        'bathrooms' => 2,
        'type' => 'Detached',
        'postcode' => 'KA12',
        'price' => 950,
        'status' => 'AVAILABLE',
        'available_date' => '01-04-2025',
        'image' => '/img/property3.jpg'
    ]
];
?>

<!-- North Ayrshire Rentals Section -->
<section class="rental-section">
  <div class="rental-container">
    <h1 class="rental-heading">TO RENT NORTH AYRSHIRE</h1>
    <p class="rental-subheading">
      Please find below a selection of our rental properties in North Ayrshire
    </p>
    
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
              <div class="property-feature">
                <i class="fas fa-calendar"></i> <?php echo $property['available_date']; ?>
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
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>