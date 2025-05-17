document.addEventListener('DOMContentLoaded', function() { // Wait till the page is ready before starting the script
    // Toggle forum button with 'M' key
    let forumVisible = false; // Forum button is initially hidden
    document.addEventListener('keydown', function(e) { // Listens for any key being pressed
        if (e.key.toLowerCase() === 'm') { // If the pressed key is "M" (Case doesn't matter since we convert it to lowercase)
            forumVisible = !forumVisible; // Toggle the forum visibility
            const forumBtn = document.querySelector('.forum-btn'); // Finds the forum button using its class name
            // If the forum button is visible, show it; otherwise, hide it
            if (forumVisible) {
                forumBtn.style.display = 'block';
                setTimeout(() => {
                    forumBtn.classList.add('show');
                }, 10);
            } else { // If the button is not visible, hide it
                forumBtn.classList.remove('show');
                setTimeout(() => {
                    forumBtn.style.display = 'none';
                }, 300);
            }
        }
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) { // If the page is scrolled more than 50 pixels
            // Add a class to the navbar to change its style
            document.querySelector('.navbar').classList.add('scrolled');
        } else {
            document.querySelector('.navbar').classList.remove('scrolled');
        }
    });

    // Smooth scroll for nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if(href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if(target) {
                    window.scrollTo({
                        top: target.offsetTop - 70,
                        behavior: 'smooth'
                    });
                    
                    // Update URL without jumping
                    history.pushState(null, null, href);
                }
            }
        });
    });
});

// Parallax effect on hero section
document.addEventListener('mousemove', function(e) { // Listen for mouse movement
    // Get the hero section and calculate the x and y axis based on mouse position
    const hero = document.querySelector('.hero-content');
    const xAxis = (window.innerWidth / 2 - e.pageX) / 30;
    const yAxis = (window.innerHeight / 2 - e.pageY) / 30;
    hero.style.transform = `translate(${xAxis}px, ${yAxis}px)`; // Moves the hero content to create a 3D effect
});

// Scroll to next section
document.querySelector('.scroll-indicator').addEventListener('click', function() {
    window.scrollTo({
        top: window.innerHeight,
        behavior: 'smooth'
    });
});

// CTA button hover effect (Slightly enlarges the button when hovered, and resets when the mouse leaves)
document.querySelector('.cta-btn').addEventListener('mouseover', function() {
    this.style.transform = 'scale(1.05)';
});

document.querySelector('.cta-btn').addEventListener('mouseout', function() {
    this.style.transform = 'scale(1)';
});

// Initialize ScrollReveal
ScrollReveal().reveal('.hero-title', { 
    delay: 300,
    distance: '50px',
    origin: 'top',
    reset: true
});

// Timeline reveal animation
const timelineItems = document.querySelectorAll('.timeline-item');

const timelineObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting) {
            entry.target.classList.add('revealed');
        }
    });
}, { threshold: 0.5 });

timelineItems.forEach(item => {
    timelineObserver.observe(item);
});

// Codex interaction
document.querySelectorAll('.tenet-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.querySelector('.tenet-hover').style.width = '100%';
    });
    
    item.addEventListener('mouseleave', function() {
        this.querySelector('.tenet-hover').style.width = '0';
    });
});

// Codex navigation
let currentPage = 0;
const codexPages = document.querySelectorAll('.codex-page');

document.querySelectorAll('.codex-arrow').forEach(arrow => {
    arrow.addEventListener('click', function() {
        codexPages[currentPage].classList.remove('active');
        currentPage = this.classList.contains('next') ? 
            Math.min(currentPage + 1, codexPages.length - 1) : 
            Math.max(currentPage - 1, 0);
        codexPages[currentPage].classList.add('active');
    });
});

// Blog card hover effects (Cards rise up and image zooms when hovered)
document.querySelectorAll('.blog-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
        this.querySelector('.blog-image').style.transform = 'scale(1.1)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.querySelector('.blog-image').style.transform = 'scale(1)';
    });
});

// Blog link hover animation (Moves the arrow when you hover over blog links)
document.querySelectorAll('.blog-link').forEach(link => {
    link.addEventListener('mouseenter', function() {
        this.querySelector('span').style.marginLeft = '10px';
    });
    
    link.addEventListener('mouseleave', function() {
        this.querySelector('span').style.marginLeft = '5px';
    });
});

// Form input animations (On focus, it shows a full-width underline. If empty after losing focus, hides it)
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.querySelector('.input-underline').style.width = '100%';
    });

    input.addEventListener('blur', function() {
        if (this.value === '') {
            this.parentElement.querySelector('.input-underline').style.width = '0';
        }
    });
});

// Submit Button hover effect (When hovering over the dark button, the icon slides in and becomes visible)
const submitButton = document.querySelector('.btn-dark');
submitButton.addEventListener('mouseenter', function() {
    this.querySelector('.btn-icon').style.transform = 'translateX(0)';
    this.querySelector('.btn-icon').style.opacity = '1';
});

submitButton.addEventListener('mouseleave', function() {
    this.querySelector('.btn-icon').style.transform = 'translateX(-20px)';
    this.querySelector('.btn-icon').style.opacity = '0';
});



