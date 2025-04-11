// Mobile Menu Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const menuOverlay = document.querySelector('.mobile-menu-overlay');
    const menuClose = document.querySelector('.mobile-menu-close');
    const body = document.body;
  
    if (menuToggle) {
      menuToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        mobileMenu.classList.toggle('active');
        menuOverlay.classList.toggle('active');
        body.classList.toggle('menu-open');
      });
    }
  
    if (menuClose) {
      menuClose.addEventListener('click', function() {
        menuToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
        body.classList.remove('menu-open');
      });
    }
  
    if (menuOverlay) {
      menuOverlay.addEventListener('click', function() {
        menuToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
        body.classList.remove('menu-open');
      });
    }
  
    // Mobile dropdown toggle
    const mobileDropdowns = document.querySelectorAll('.mobile-dropdown > a');
    if (mobileDropdowns.length > 0) {
      mobileDropdowns.forEach(item => {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          this.parentElement.classList.toggle('open');
        });
      });
    }
  
    // Header scroll effect
    const header = document.querySelector('.header');
    if (header) {
      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      });
    }
  
    // Auto-rotating carousel
    const carouselSlides = document.querySelectorAll('.carousel-slide');
    if (carouselSlides.length > 0) {
      let currentSlide = 0;
      
      // Function to show the current slide
      function showSlide(index) {
        // Remove active class from all slides
        carouselSlides.forEach(slide => {
          slide.classList.remove('active');
        });
        
        // Add active class to current slide
        carouselSlides[index].classList.add('active');
      }
      
      // Function to move to the next slide
      function nextSlide() {
        currentSlide = (currentSlide + 1) % carouselSlides.length;
        showSlide(currentSlide);
      }
      
      // Start with the first slide
      showSlide(currentSlide);
      
      // Auto-rotate slides every 5 seconds
      setInterval(nextSlide, 5000);
    }
  });