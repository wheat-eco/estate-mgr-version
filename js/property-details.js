/**
 * Property Details Page JavaScript
 */
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