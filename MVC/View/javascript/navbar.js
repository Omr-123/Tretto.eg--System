/* ========================================
   NAVBAR JAVASCRIPT - Animations & Interactions
   ======================================== */

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 60) {
        navbar.classList.add('sc');
    } else {
        navbar.classList.remove('sc');
    }
});

// Update active nav link based on current page
function updateActiveNavLink(page) {
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.classList.remove('active');
    });
    const activeLink = document.getElementById(`nl-${page}`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

// Badge update animations
function updateBadge(badgeElement, newValue) {
    if (newValue === 0) {
        badgeElement.style.display = 'none';
    } else {
        badgeElement.style.display = 'flex';
        badgeElement.textContent = newValue;
        // Add pop animation
        badgeElement.style.animation = 'none';
        setTimeout(() => {
            badgeElement.style.animation = 'badgePop .3s ease';
        }, 10);
    }
}

// Badge pop animation
const style = document.createElement('style');
style.textContent = `
    @keyframes badgePop {
        0% { transform: scale(0.8); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);

// Navigation button hover effects
document.querySelectorAll('.nav-icon, .btn-nav').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
    });
    btn.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});

// Smooth scroll to top when logo is clicked
document.querySelector('.logo')?.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

console.log('Navbar initialized');
