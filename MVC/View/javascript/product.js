/* ========================================
   PRODUCT PAGE JAVASCRIPT - Animations & Interactions
   ======================================== */

// Filter products by category
function filterProducts(category) {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    filterBtns.forEach(btn => {
        btn.classList.remove('active');
    });

    event.target.classList.add('active');

    productCards.forEach(card => {
        const productCategory = card.getAttribute('data-category');
        
        if (category === 'all' || productCategory === category) {
            card.style.animation = 'none';
            setTimeout(() => {
                card.style.display = 'block';
                card.style.animation = 'fadeInScale .3s ease';
            }, 10);
        } else {
            card.style.display = 'none';
        }
    });
}

// Add to cart animation
function addToCart(productId) {
    const card = document.querySelector(`[data-product-id="${productId}"]`);
    const btn = card.querySelector('.btn-add-cart');

    // Button animation
    btn.textContent = '✓ Added!';
    btn.style.background = 'var(--mauve)';
    btn.disabled = true;

    setTimeout(() => {
        btn.textContent = 'Add to Cart';
        btn.style.background = 'linear-gradient(135deg, var(--rose), var(--mauve))';
        btn.disabled = false;
    }, 2000);

    // Ripple effect
    createRipple(btn);

    // Update cart count (you'll implement via PHP)
    console.log('Item added to cart:', productId);
}

// Favorite toggle animation
function toggleFavorite(event) {
    event.stopPropagation();
    const btn = event.target;
    const isFavorited = btn.classList.contains('favorited');

    if (isFavorited) {
        btn.textContent = '♡';
        btn.classList.remove('favorited');
        btn.style.animation = 'heartBeat .3s ease';
    } else {
        btn.textContent = '♥';
        btn.classList.add('favorited');
        btn.style.animation = 'heartFill .3s ease';
    }

    setTimeout(() => {
        btn.style.animation = 'none';
    }, 300);
}

// Ripple effect on buttons
function createRipple(button) {
    const ripple = document.createElement('span');
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple');

    button.appendChild(ripple);
    setTimeout(() => ripple.remove(), 600);
}

// Product card click animation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-favorite') && !e.target.closest('.btn-add-cart')) {
                const productId = this.getAttribute('data-product-id');
                viewProduct(productId);
            }
        });

        // Favorite button
        card.querySelector('.btn-favorite')?.addEventListener('click', toggleFavorite);

        // Add to cart button
        card.querySelector('.btn-add-cart')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const productId = card.getAttribute('data-product-id');
            addToCart(productId);
        });
    });

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', filterProducts);
    });
});

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes heartFill {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.1); }
        50% { transform: scale(1); }
    }

    .ripple {
        position: absolute;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        transform: scale(0);
        animation: rippleEffect 600ms ease-out;
        pointer-events: none;
    }

    @keyframes rippleEffect {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

console.log('Product page initialized');
