<?php
/**
 * Property Details Page for Love View Estate
 */

// Include database connection
require_once('includes/db.php');

// Get property ID from URL
$propertyId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no property ID provided, redirect to home page
if ($propertyId <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch property details
$query = "SELECT p.*, a.name as area_name, r.name as region_name 
          FROM properties p 
          JOIN areas a ON p.area_id = a.id 
          JOIN regions r ON a.region_id = r.id 
          WHERE p.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $propertyId);
$stmt->execute();
$result = $stmt->get_result();

// If property not found, redirect to home page
if ($result->num_rows == 0) {
    header('Location: index.php');
    exit;
}

// Get property data
$property = $result->fetch_assoc();

// Get property images
$imagesQuery = "SELECT * FROM property_images WHERE property_id = ? ORDER BY is_featured DESC";
$imagesStmt = $conn->prepare($imagesQuery);
$imagesStmt->bind_param("i", $propertyId);
$imagesStmt->execute();
$imagesResult = $imagesStmt->get_result();

$images = [];
while ($image = $imagesResult->fetch_assoc()) {
    $images[] = $image;
}

// Get property documents
$documentsQuery = "SELECT * FROM property_documents WHERE property_id = ?";
$documentsStmt = $conn->prepare($documentsQuery);
$documentsStmt->bind_param("i", $propertyId);
$documentsStmt->execute();
$documentsResult = $documentsStmt->get_result();

$documents = [];
while ($document = $documentsResult->fetch_assoc()) {
    $documents[] = $document;
}

// Get property features
$featuresQuery = "SELECT * FROM property_features WHERE property_id = ?";
$featuresStmt = $conn->prepare($featuresQuery);
$featuresStmt->bind_param("i", $propertyId);
$featuresStmt->execute();
$featuresResult = $featuresStmt->get_result();

$features = [];
while ($feature = $featuresResult->fetch_assoc()) {
    $features[] = $feature;
}

// Page specific variables
$pageTitle = $property['title'];
$additionalCss = ['property-details'];

// Include header
include('includes/header.php');
?>

<!-- Property Details Section -->
<section class="property-details-section">
  <div class="property-container">
    <div class="property-header">
      <div class="property-title-area">
        <h1 class="property-title"><?php echo $property['title']; ?></h1>
        <p class="property-location"><?php echo $property['location']; ?></p>
      </div>
      <div class="property-price-area">
        <div class="property-price">
          £<?php echo number_format($property['price'], 0); ?>
          <?php if ($property['property_category'] == 'rent'): ?>
            <span class="pcm">PCM</span>
          <?php endif; ?>
        </div>
        <div class="property-status"><?php echo $property['status']; ?></div>
      </div>
    </div>
    
    <!-- Property Gallery -->
    <div class="property-gallery">
      <?php if (count($images) > 0): ?>
        <div class="main-image">
          <img src="<?php echo $images[0]['image_path']; ?>" alt="<?php echo $property['title']; ?>" id="mainImage">
        </div>
        <?php if (count($images) > 1): ?>
          <div class="thumbnail-container">
            <?php foreach($images as $index => $image): ?>
              <div class="thumbnail" onclick="changeMainImage('<?php echo $image['image_path']; ?>')">
                <img src="<?php echo $image['image_path']; ?>" alt="Thumbnail <?php echo $index + 1; ?>">
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="main-image">
          <img src="/img/property-placeholder.jpg" alt="<?php echo $property['title']; ?>">
        </div>
      <?php endif; ?>
    </div>
    
    <!-- Property Features -->
    <div class="property-features-grid">
      <div class="feature">
        <i class="fas fa-map-marker-alt"></i>
        <span><?php echo $property['area_name']; ?>, <?php echo $property['region_name']; ?></span>
      </div>
      <div class="feature">
        <i class="fas fa-bed"></i>
        <span><?php echo $property['bedrooms']; ?> Bedrooms</span>
      </div>
      <div class="feature">
        <i class="fas fa-bath"></i>
        <span><?php echo $property['bathrooms']; ?> Bathroom<?php echo $property['bathrooms'] > 1 ? 's' : ''; ?></span>
      </div>
      <div class="feature">
        <i class="fas fa-home"></i>
        <span><?php echo $property['property_type']; ?></span>
      </div>
      <div class="feature">
        <i class="fas fa-map-pin"></i>
        <span><?php echo $property['postcode']; ?></span>
      </div>
      <?php if(!empty($property['available_date'])): ?>
      <div class="feature">
        <i class="fas fa-calendar"></i>
        <span>Available: <?php echo date('d-m-Y', strtotime($property['available_date'])); ?></span>
      </div>
      <?php endif; ?>
    </div>
    
    <!-- Property Description -->
    <div class="property-description">
      <h2>Description</h2>
      <div class="description-content">
        <?php echo nl2br($property['description']); ?>
      </div>
    </div>
    
    <?php if (count($features) > 0): ?>
    <!-- Property Additional Features -->
    <div class="property-additional-features">
      <h2>Additional Features</h2>
      <ul class="features-list">
        <?php foreach($features as $feature): ?>
          <li><i class="fas fa-check"></i> <?php echo $feature['feature_name']; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    
    <?php if (count($documents) > 0): ?>
    <!-- Property Documents -->
    <div class="property-documents">
      <h2>Documents</h2>
      <ul class="documents-list">
        <?php foreach($documents as $document): ?>
          <li>
            <a href="<?php echo $document['document_path']; ?>" target="_blank">
              <i class="fas fa-file-pdf"></i> <?php echo $document['document_name']; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    
    <!-- Viewing Request Form -->
    <div class="viewing-request">
      <h2>Request a Viewing</h2>
      <form id="viewingForm" action="process-viewing.php" method="post">
        <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
        <input type="hidden" name="property_title" value="<?php echo $property['title']; ?>">
        
        <div class="form-row">
          <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="tel" id="phone" name="phone" required>
          </div>
          <div class="form-group">
            <label for="preferred_date">Preferred Date</label>
            <input type="date" id="preferred_date" name="preferred_date">
          </div>
        </div>
        
        <div class="form-group">
          <label for="message">Additional Information</label>
          <textarea id="message" name="message" rows="4"></textarea>
        </div>
        
        <button type="submit" class="submit-button">Request Viewing</button>
      </form>
    </div>
  </div>
</section>

<script>
function changeMainImage(src) {
  document.getElementById('mainImage').src = src;
}
</script>

<?php
// Include footer
include('includes/footer.php');
?>