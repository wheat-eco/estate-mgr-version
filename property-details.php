<?php
/**
 * Property Details Page for Love View Estate
 */

// Get property ID from URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// In a real application, you would fetch this data from a database based on the ID
// This is just sample data for demonstration
$properties = [
    1 => [
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
        'image' => '/img/property1.jpg',
        'region' => 'North Ayrshire',
        'description' => 'A well-presented three-bedroom terraced house in the popular Bridgend area of Kilwinning. The property comprises an entrance hallway, spacious lounge, modern fitted kitchen, three good-sized bedrooms, and a family bathroom. The property benefits from gas central heating, double glazing, and a private garden to the rear. Conveniently located for local amenities, schools, and transport links.',
        'features' => [
            'Gas Central Heating',
            'Double Glazing',
            'Private Garden',
            'Modern Kitchen',
            'Close to Schools',
            'Good Transport Links',
            'On-street Parking',
            'Recently Decorated'
        ],
        'epc_rating' => 'C',
        'council_tax' => 'B',
        'deposit' => 800,
        'furnished' => 'Unfurnished',
        'pets' => 'Considered',
        'smokers' => 'No',
        'gallery' => [
            '/img/property1.jpg',
            '/img/property1-2.jpg',
            '/img/property1-3.jpg',
            '/img/property1-4.jpg',
            '/img/property1-5.jpg'
        ]
    ],
    // Add more properties as needed
];

// Check if property exists
if (!isset($properties[$property_id])) {
    // Redirect to properties page if property not found
    header('Location: /for-rent.php');
    exit;
}

$property = $properties[$property_id];

// Page specific variables
$pageTitle = $property['title'];
$additionalCss = ['rental-properties'];

// Include header
include('includes/header.php');
?>

<!-- Property Details Section -->
<section class="property-detail-section">
  <div class="property-detail-container">
    <!-- Property Gallery -->
    <div class="property-gallery">
      <img src="<?php echo $property['image']; ?>" alt="<?php echo $property['title']; ?>" class="main-image">
      
      <?php if(isset($property['gallery']) && count($property['gallery']) > 0): ?>
        <div class="thumbnail-grid">
          <?php foreach($property['gallery'] as $image): ?>
            <img src="<?php echo $image; ?>" alt="<?php echo $property['title']; ?>" class="thumbnail">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    
    <!-- Property Content -->
    <div class="property-detail-content">
      <!-- Left Column - Description and Features -->
      <div class="property-description-section">
        <h1><?php echo $property['title']; ?></h1>
        <p class="property-location"><?php echo $property['location']; ?> (<?php echo $property['region']; ?>)</p>
        
        <?php if($property['status']): ?>
          <div class="property-status-badge"><?php echo $property['status']; ?></div>
        <?php endif; ?>
        
        <h2>Property Description</h2>
        <p><?php echo $property['description']; ?></p>
        
        <div class="property-features-section">
          <h3>Key Features</h3>
          <div class="features-list">
            <?php foreach($property['features'] as $feature): ?>
              <div class="feature-item">
                <i class="fas fa-check"></i> <?php echo $feature; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      
      <!-- Right Column - Sidebar with Price and Details -->
      <div class="property-sidebar">
        <div class="property-sidebar-price">
          £<?php echo $property['price']; ?> <span class="pcm">PCM</span>
        </div>
        
        <div class="property-sidebar-details">
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Bedrooms</span>
            <span class="sidebar-detail-value"><?php echo $property['bedrooms']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Bathrooms</span>
            <span class="sidebar-detail-value"><?php echo $property['bathrooms']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Property Type</span>
            <span class="sidebar-detail-value"><?php echo $property['type']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Postcode</span>
            <span class="sidebar-detail-value"><?php echo $property['postcode']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Available From</span>
            <span class="sidebar-detail-value"><?php echo $property['available_date']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Furnished</span>
            <span class="sidebar-detail-value"><?php echo $property['furnished']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">EPC Rating</span>
            <span class="sidebar-detail-value"><?php echo $property['epc_rating']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Council Tax Band</span>
            <span class="sidebar-detail-value"><?php echo $property['council_tax']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Deposit</span>
            <span class="sidebar-detail-value">£<?php echo $property['deposit']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Pets</span>
            <span class="sidebar-detail-value"><?php echo $property['pets']; ?></span>
          </div>
          <div class="sidebar-detail">
            <span class="sidebar-detail-label">Smokers</span>
            <span class="sidebar-detail-value"><?php echo $property['smokers']; ?></span>
          </div>
        </div>
        
        <div class="contact-agent-section">
          <h3>Arrange a Viewing</h3>
          <form class="contact-form" action="/submit-viewing.php" method="post">
            <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="tel" name="phone" placeholder="Your Phone Number" required>
            <textarea name="message" placeholder="Message (Optional)"></textarea>
            <button type="submit">Request Viewing</button>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Back to Listings Button -->
    <div class="back-to-listings">
      <a href="javascript:history.back()" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Listings
      </a>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>