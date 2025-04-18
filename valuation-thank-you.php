<?php
/**
 * Valuation Thank You Page for Love View Estate
 */

// Page specific variables
$pageTitle = 'Thank You for Your Valuation Request';
$additionalCss = ['valuation'];

// Include header
include('includes/header.php');
?>

<!-- Thank You Section -->
<section class="thank-you-section">
  <div class="container">
    <div class="thank-you-content">
      <div class="thank-you-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      
      <h1>Thank You for Your Valuation Request</h1>
      
      <p>We have received your valuation request and one of our property experts will be in touch with you shortly.</p>
      
      <div class="next-steps">
        <h2>What happens next?</h2>
        <ul>
          <li>
            <i class="fas fa-phone"></i>
            <div>
              <h3>We'll call you</h3>
              <p>One of our property experts will call you within 24 hours to discuss your valuation.</p>
            </div>
          </li>
          <li>
            <i class="fas fa-home"></i>
            <div>
              <h3>Property visit</h3>
              <p>We'll arrange a convenient time to visit your property for a detailed assessment.</p>
            </div>
          </li>
          <li>
            <i class="fas fa-file-alt"></i>
            <div>
              <h3>Detailed valuation</h3>
              <p>You'll receive a comprehensive valuation report with our expert recommendations.</p>
            </div>
          </li>
        </ul>
      </div>
      
      <div class="cta-buttons">
        <a href="/index.php" class="btn btn-primary">Return to Home</a>
        <a href="/contact.php" class="btn btn-secondary">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<style>
  .thank-you-section {
    padding: 80px 0;
    background-color: #f9f9f9;
  }
  
  .thank-you-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }
  
  .thank-you-icon {
    font-size: 80px;
    color: #4CAF50;
    margin-bottom: 20px;
  }
  
  .thank-you-content h1 {
    margin-bottom: 20px;
    color: #323232;
  }
  
  .thank-you-content p {
    font-size: 18px;
    margin-bottom: 40px;
    color: #666;
  }
  
  .next-steps {
    text-align: left;
    margin: 40px 0;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
  }
  
  .next-steps h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #323232;
  }
  
  .next-steps ul {
    list-style: none;
    padding: 0;
  }
  
  .next-steps li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
  }
  
  .next-steps li i {
    font-size: 24px;
    color: #e4b611;
    margin-right: 20px;
    margin-top: 5px;
  }
  
  .next-steps li h3 {
    margin: 0 0 5px 0;
    color: #323232;
  }
  
  .next-steps li p {
    margin: 0;
    font-size: 16px;
  }
  
  .cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
  }
  
  .btn {
    padding: 12px 30px;
    border-radius: 4px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  .btn-primary {
    background-color: #e4b611;
    color: #323232;
  }
  
  .btn-secondary {
    background-color: #323232;
    color: #fff;
  }
  
  .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }
</style>

<?php
// Include footer
include('includes/footer.php');
?>