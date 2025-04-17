<?php
/**
 * Rental Valuation Form for Love View Estate
 */

// Page specific variables
$pageTitle = 'Rental Valuation';
$additionalCss = ['valuation'];

// Include header
include('includes/header.php');
?>

<!-- Valuation Form Section -->
<section class="valuation-form-section">
  <div class="valuation-container">
    <div class="valuation-form-wrapper">
      <div class="valuation-form-left">
        <h2>What's my property worth?</h2>
        <form class="valuation-form" action="/submit-rental-valuation.php" method="post">
          <div class="form-group">
            <label for="postcode">Post Code<span class="required">*</span>:</label>
            <div class="postcode-input-group">
              <input type="text" id="postcode" name="postcode" placeholder="e.g. KW1 1AB" required />
              <button type="button" class="find-address-btn">Find Address</button>
            </div>
          </div>
          
          <div class="form-group">
            <label for="bedrooms">Bedrooms<span class="required">*</span>:</label>
            <select id="bedrooms" name="bedrooms" required>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5+">5+</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="property-type">Property Type<span class="required">*</span>:</label>
            <select id="property-type" name="property_type" required>
              <option value="Flats">Flats</option>
              <option value="Terraced">Terraced</option>
              <option value="Semi-Detached">Semi-Detached</option>
              <option value="Detached">Detached</option>
              <option value="Bungalow">Bungalow</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="full-name">Full Name<span class="required">*</span>:</label>
            <input type="text" id="full-name" name="full_name" placeholder="Your full name" required />
          </div>
          
          <div class="form-group">
            <label for="email">Email<span class="required">*</span>:</label>
            <input type="email" id="email" name="email" placeholder="Your email" required />
          </div>
          
          <div class="form-group">
            <label for="phone">Phone<span class="required">*</span>:</label>
            <input type="tel" id="phone" name="phone" placeholder="Your phone number" required />
          </div>
          
          <div class="form-group">
            <label for="move-date">When do you plan to move?<span class="required">*</span>:</label>
            <select id="move-date" name="move_date" required>
              <option value="">Please Select</option>
              <option value="0-3 months">0-3 months</option>
              <option value="3-6 months">3-6 months</option>
              <option value="6-12 months">6-12 months</option>
              <option value="1+ year">1+ year</option>
              <option value="Just curious">Just curious</option>
            </select>
          </div>
          
          <div class="form-group checkbox-group">
            <label class="checkbox-container">
              <input type="checkbox" name="marketing_consent" required />
              <span class="checkmark"></span>
              <span class="checkbox-text">
                Please tick this box if you are happy to be contacted by us for a FREE bespoke valuation of your property and relevant services. You may withdraw your consent to our processing your personal data for marketing purposes at any time, you may do this by emailing us at info@loveviewestate.com
              </span>
            </label>
          </div>
          
          <div class="form-group checkbox-group">
            <label class="checkbox-container">
              <input type="checkbox" name="terms_consent" required />
              <span class="checkmark"></span>
              <span class="checkbox-text">
                Please tick this box if you have read and accept our <a href="/terms-of-service.php">Terms</a> and <a href="/privacy-policy.php">Privacy Policy</a>.
              </span>
            </label>
          </div>
          
          <div class="form-actions">
            <a href="/valuation.php" class="btn-secondary">
              <i class="fas fa-arrow-left"></i> Back to home
            </a>
            <button type="submit" class="btn-primary">Next</button>
          </div>
        </form>
      </div>
      
      <div class="valuation-form-right">
        <h2>What happens next?</h2>
        <ul class="next-steps">
          <li>
            <i class="fas fa-check-circle"></i>
            <span>Secure a bespoke valuation of your home</span>
          </li>
          <li>
            <i class="fas fa-check-circle"></i>
            <span>Use local experts unique market knowledge</span>
          </li>
          <li>
            <i class="fas fa-check-circle"></i>
            <span>Find out how to maximise your property's rental potential</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php
// Include footer
include('includes/footer.php');
?>