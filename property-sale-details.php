<?php
/**
* Property Sale Details Page for Love View Estate
*/

// Get property ID from URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// In a real application, you would fetch this data from a database based on the ID
// This is just sample data for demonstration
$properties = [
   101 => [
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
       'available_date' => 'Immediate',
       'image' => '/img/property-sale1.jpg',
       'region' => 'North Ayrshire',
       'description' => 'A beautifully presented four-bedroom detached house in the popular Pennyburn area of Kilwinning. This spacious family home offers generous living accommodation comprising an entrance hallway, large lounge with bay window, separate dining room, modern fitted kitchen with integrated appliances, utility room, and downstairs WC. Upstairs, there are four good-sized bedrooms, with the master bedroom benefiting from an en-suite shower room, and a family bathroom. The property features gas central heating, double glazing, and a security alarm system. Externally, there is a well-maintained garden to the front and rear, a driveway providing off-street parking, and a detached garage.',
       'features' => [
           'Gas Central Heating',
           'Double Glazing',
           'Detached Garage',
           'Modern Kitchen',
           'En-suite Master Bedroom',
           'Security Alarm System',
           'Off-street Parking',
           'Well-maintained Gardens'
       ],
       'epc_rating' => 'C',
       'council_tax' => 'F',
       'garden' => 'Front and Rear',
       'parking' => 'Driveway and Garage',
       'gallery' => [
           '/img/property-sale1.jpg',
           '/img/property-sale1-2.jpg',
           '/img/property-sale1-3.jpg',
           '/img/property-sale1-4.jpg',
           '/img/property-sale1-5.jpg'
       ]
   ],
   201 => [
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
       'available_date' => 'Immediate',
       'image' => '/img/property-sale5.jpg',
       'region' => 'East Ayrshire',
       'description' => 'An impressive four-bedroom detached villa situated in a sought-after residential area of Kilmarnock. This well-presented family home offers spacious and versatile accommodation over two levels. The ground floor comprises an entrance hallway, bright and airy lounge, formal dining room, modern fitted kitchen with breakfast area, utility room, and a downstairs WC. Upstairs, there are four double bedrooms, with the master bedroom featuring an en-suite shower room, and a family bathroom. The property benefits from gas central heating, double glazing, and a security alarm system. Externally, there are well-maintained gardens to the front and rear, a driveway providing off-street parking for multiple vehicles, and an integral garage.',
       'features' => [
           'Gas Central Heating',
           'Double Glazing',
           'Integral Garage',
           'Modern Kitchen',
           'En-suite Master Bedroom',
           'Security Alarm System',
           'Off-street Parking',
           'Well-maintained Gardens'
       ],
       'epc_rating' => 'C',
       'council_tax' => 'F',
       'garden' => 'Front and Rear',
       'parking' => 'Driveway and Garage',
       'gallery' => [
           '/img/property-sale5.jpg',
           '/img/property-sale5-2.jpg',
           '/img/property-sale5-3.jpg',
           '/img/property-sale5-4.jpg',
           '/img/property-sale5-5.jpg'
       ]
   ],
   // Add more properties as needed
];

// Check if property exists
if (!isset($properties[$property_id])) {
   // Redirect to properties page if property not found
   header('Location: /for-sale-north-ayrshire.php');
   exit;
}

$property = $properties[$property_id];

// Page specific variables
$pageTitle = $property['title'];
$additionalCss = ['selling'];

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
         £<?php echo number_format($property['price']); ?>
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
           <span class="sidebar-detail-label">Availability</span>
           <span class="sidebar-detail-value"><?php echo $property['available_date']; ?></span>
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
           <span class="sidebar-detail-label">Garden</span>
           <span class="sidebar-detail-value"><?php echo $property['garden']; ?></span>
         </div>
         <div class="sidebar-detail">
           <span class="sidebar-detail-label">Parking</span>
           <span class="sidebar-detail-value"><?php echo $property['parking']; ?></span>
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

<!-- Additional CSS for Property Details -->
<style>
/* Property Details Styles */
.property-detail-section {
  padding: 2rem 0 4rem;
}

.property-detail-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.property-gallery {
  margin-bottom: 2rem;
}

.main-image {
  width: 100%;
  height: 500px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 1rem;
}

.thumbnail-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1rem;
}

.thumbnail {
  width: 100%;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.thumbnail:hover, .thumbnail.active {
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.property-detail-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
}

.property-description-section h1 {
  font-size: 2rem;
  font-weight: 700;
  color: #323232;
  margin-bottom: 0.5rem;
}

.property-location {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 1.5rem;
}

.property-status-badge {
  display: inline-block;
  background-color: #e4b611;
  color: #323232;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 0.5rem 1rem;
  border-radius: 50px;
  margin-bottom: 1.5rem;
}

.property-description-section h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #323232;
  margin-bottom: 1rem;
}

.property-description-section p {
  font-size: 1rem;
  color: #666;
  line-height: 1.8;
  margin-bottom: 2rem;
}

.property-features-section h3 {
  font-size: 1.3rem;
  font-weight: 600;
  color: #323232;
  margin-bottom: 1rem;
}

.features-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.feature-item {
  font-size: 1rem;
  color: #666;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.feature-item i {
  color: #e4b611;
}

.property-sidebar {
  background-color: #f9f9f9;
  border-radius: 12px;
  padding: 2rem;
  height: fit-content;
}

.property-sidebar-price {
  font-size: 2rem;
  font-weight: 700;
  color: #323232;
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #eee;
}

.property-sidebar-details {
  margin-bottom: 2rem;
}

.sidebar-detail {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #eee;
}

.sidebar-detail-label {
  font-size: 0.95rem;
  color: #666;
}

.sidebar-detail-value {
  font-size: 0.95rem;
  font-weight: 600;
  color: #323232;
}

.contact-agent-section h3 {
  font-size: 1.3rem;
  font-weight: 600;
  color: #323232;
  margin-bottom: 1rem;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.contact-form input,
.contact-form textarea {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 0.95rem;
}

.contact-form textarea {
  min-height: 100px;
  resize: vertical;
}

.contact-form button {
  padding: 0.75rem 1.5rem;
  background-color: #e4b611;
  color: #323232;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.contact-form button:hover {
  background-color: #f5c728;
}

.back-to-listings {
  margin-top: 3rem;
  text-align: center;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
  .property-detail-content {
    grid-template-columns: 1fr;
  }
  
  .thumbnail-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .main-image {
    height: 400px;
  }
}

@media (max-width: 768px) {
  .features-list {
    grid-template-columns: 1fr;
  }
  
  .thumbnail-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .main-image {
    height: 300px;
  }
}
</style>

<!-- JavaScript for Property Gallery -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Gallery functionality
  const mainImage = document.querySelector('.main-image');
  const thumbnails = document.querySelectorAll('.thumbnail');
  
  if (thumbnails.length > 0) {
    thumbnails.forEach(thumbnail => {
      thumbnail.addEventListener('click', function() {
        // Update main image src with clicked thumbnail src
        mainImage.src = this.src;
        
        // Remove active class from all thumbnails
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        
        // Add active class to clicked thumbnail
        this.classList.add('active');
      });
    });
    
    // Set first thumbnail as active by default
    thumbnails[0].classList.add('active');
  }
  
  // Form validation
  const contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      const nameInput = this.querySelector('input[name="name"]');
      const emailInput = this.querySelector('input[name="email"]');
      const phoneInput = this.querySelector('input[name="phone"]');
      
      let isValid = true;
      
      // Simple validation
      if (nameInput.value.trim() === '') {
        nameInput.classList.add('error');
        isValid = false;
      } else {
        nameInput.classList.remove('error');
      }
      
      if (emailInput.value.trim() === '' || !isValidEmail(emailInput.value)) {
        emailInput.classList.add('error');
        isValid = false;
      } else {
        emailInput.classList.remove('error');
      }
      
      if (phoneInput.value.trim() === '') {
        phoneInput.classList.add('error');
        isValid = false;
      } else {
        phoneInput.classList.remove('error');
      }
      
      if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields correctly.');
      }
    });
  }
  
  // Email validation helper
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
});
</script>

<?php
// Include footer
include('includes/footer.php');
?>