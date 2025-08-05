// Main JavaScript for Dream Mobile website

document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.getElementById('main-header');
    const handleScroll = () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };
    
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
    
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Offset for header
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        });
    });
    
    // Scroll animations
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    const checkIfInView = () => {
        const windowHeight = window.innerHeight;
        const windowTopPosition = window.scrollY;
        const windowBottomPosition = windowTopPosition + windowHeight;
        
        animatedElements.forEach(element => {
            const elementHeight = element.offsetHeight;
            const elementTopPosition = element.getBoundingClientRect().top + windowTopPosition;
            const elementBottomPosition = elementTopPosition + elementHeight;
            
            // Check if element is in viewport
            if ((elementBottomPosition >= windowTopPosition) && 
                (elementTopPosition <= windowBottomPosition - 100)) {
                element.classList.add('visible');
            }
        });
    };
    
    // Run once on page load with a small delay to allow page render
    setTimeout(checkIfInView, 100);
    
    // Run on scroll
    window.addEventListener('scroll', checkIfInView);
    
    // Image loading animation
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        // Add loading class
        img.classList.add('loading');
        
        // Remove loading class when image is loaded
        img.addEventListener('load', function() {
            this.classList.remove('loading');
            this.classList.add('loaded');
        });
        
        // If the image is already cached, add loaded class immediately
        if (img.complete) {
            img.classList.remove('loading');
            img.classList.add('loaded');
        }
    });
    
    // Add active class to current navigation item
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href').split('/').pop();
        
        if (currentPage === linkPage || 
            (currentPage === '' && linkPage === '/') ||
            (currentPage === '/' && linkPage === '')) {
            link.classList.add('text-blue-400');
        }
    });
    
    // Parallax effect on scroll
    const parallaxElements = document.querySelectorAll('.parallax');
    
    const handleParallax = () => {
        const scrollPosition = window.scrollY;
        
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-speed') || 0.2;
            element.style.transform = `translateY(${scrollPosition * speed}px)`;
        });
    };
    
    window.addEventListener('scroll', handleParallax);
}); 