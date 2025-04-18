<?php
/**
 * Footer component for Love View Estate website
 */
?>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-grid">
          <div class="footer-column brand-column">
            <img
              src="/img/love-view-logo.png"
              alt="Love View Estate"
              class="footer-logo"
            />
            <p>
              Love View Estate offers premium real estate services with a focus
              on exceptional properties and client satisfaction.
            </p>
            <div class="footer-social">
              <a href="#" class="social-link" aria-label="Facebook"
                ><i class="fab fa-facebook-f"></i
              ></a>
              <a href="#" class="social-link" aria-label="Twitter"
                ><i class="fab fa-x-twitter"></i
              ></a>
              <a href="#" class="social-link" aria-label="Instagram"
                ><i class="fab fa-instagram"></i
              ></a>
              <a href="#" class="social-link" aria-label="Email"
                ><i class="fas fa-envelope"></i
              ></a>
            </div>
          </div>

          <div class="footer-column">
            <h3>Quick Links</h3>
            <ul class="footer-links">
              <li><a href="/index.php">Home</a></li>
              <li><a href="/our-story.php">About Us</a></li>
              <li><a href="/properties.php">Properties</a></li>
              <li><a href="/services.php">Services</a></li>
              <li><a href="/contact.php">Contact</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h3>Properties</h3>
            <ul class="footer-links">
              <li><a href="/for-sale-north-ayrshire.php">North Ayrshire For Sale</a></li>
              <li><a href="/for-sale-east-ayrshire.php">East Ayrshire For Sale</a></li>
              <li><a href="/to-rent-north-ayrshire.php">North Ayrshire To Rent</a></li>
              <li><a href="/to-rent-east-ayrshire.php">East Ayrshire To Rent</a></li>
              <li><a href="/valuation.php">Property Valuation</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h3>Contact Us</h3>
            <address class="footer-contact">
              <p>
                <i class="fas fa-map-marker-alt"></i> 123 Estate Avenue,
                Ayrshire
              </p>
              <p><i class="fas fa-phone"></i> +44 1234 567890</p>
              <p><i class="fas fa-envelope"></i> info@loveviewestate.com</p>
            </address>
            <div class="newsletter">
              <h4>Subscribe to our newsletter</h4>
              <form class="newsletter-form" action="/subscribe.php" method="post">
                <input type="email" name="email" placeholder="Your email address" required />
                <button type="submit" class="btn-submit">Subscribe</button>
              </form>
            </div>
          </div>
        </div>

        <div class="footer-bottom">
          <p>&copy; <?php echo date('Y'); ?> Love View Estate. All rights reserved.</p>
          <div class="footer-bottom-links">
            <a href="/privacy-policy.php">Privacy Policy</a>
            <a href="/terms-of-service.php">Terms of Service</a>
            <a href="/cookies-policy.php">Cookies Policy</a>
          </div>
        </div>
      </div>
    </footer>

    <script src="/js/main.js"></script>
  </body>
</html>