/**
 * BuildWise Inventory System V2 - Main JavaScript
 * Handles sidebar navigation, category filtering, cart processing, and low stock alerts
 */

// Global state
let currentCategory = 'all';
let sidebarOpen = false;
const appBaseUrl = (document.querySelector('meta[name="app-url"]')?.content || window.location.origin).replace(/\/+$/, '');

function resolveUrl(path) {
    if (!path) return appBaseUrl;
    if (path.startsWith('http')) return path;
    return appBaseUrl + '/' + path.replace(/^\/+/, '');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeCategoryDropdown();
    checkLowStockAlerts();
    initializeCart();
    initializeLogoutModal();
});

/**
 * Sidebar Functions
 */
function initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    sidebarOpen = !sidebarOpen;

    if (sidebarOpen) {
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    } else {
        closeSidebar();
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    sidebarOpen = false;
    sidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

/**
 * Logout Modal Functions
 */
function initializeLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    if (!logoutModal) return;

    window.openLogoutModal = function() {
        logoutModal.classList.add('active');
    };

    window.closeLogoutModal = function() {
        logoutModal.classList.remove('active');
    };

    logoutModal.addEventListener('click', function(event) {
        if (event.target === logoutModal) {
            closeLogoutModal();
        }
    });
}

/**
 * Category Dropdown Functions
 */
function initializeCategoryDropdown() {
    const categoryToggle = document.querySelector('.category-toggle');
    const categoryDropdown = document.querySelector('.category-dropdown');

    if (categoryToggle && categoryDropdown) {
        categoryToggle.addEventListener('click', function(e) {
            e.preventDefault();
            categoryDropdown.classList.toggle('active');
            categoryToggle.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!categoryToggle.contains(e.target) && !categoryDropdown.contains(e.target)) {
                categoryDropdown.classList.remove('active');
                categoryToggle.classList.remove('active');
            }
        });
    }
}

function filterByCategory(category) {
    currentCategory = category;

    // Update URL without page reload
    const url = new URL(window.location);
    if (category === 'all') {
        url.searchParams.delete('category');
    } else {
        url.searchParams.set('category', category);
    }
    window.history.pushState({}, '', url);

    // Reload page to filter products
    window.location.reload();
}

/**
 * Low Stock Alert Functions
 */
function checkLowStockAlerts() {
    const lowStockProducts = document.querySelectorAll('[data-stock-status="low"], [data-stock-status="out"]');
    const lowStockCount = lowStockProducts.length;

    // Update sidebar notification badge
    const badge = document.getElementById('lowStockBadge');
    if (badge && lowStockCount > 0) {
        badge.textContent = lowStockCount;
        badge.style.display = 'flex';
    }

    // Show low stock notification banner
    if (lowStockCount > 0) {
        showLowStockBanner(lowStockCount);
    }
}

function showLowStockBanner(count) {
    const banner = document.getElementById('lowStockBanner');
    if (!banner) return;

    banner.innerHTML = `
        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(217, 119, 6, 0.1); border-left: 4px solid var(--warning); border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: var(--warning);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div style="flex: 1;">
                <strong style="color: var(--warning);">Low Stock Alert</strong>
                <p style="margin: 0.25rem 0 0; color: var(--neutral-600); font-size: 0.9rem;">
                    ${count} product${count > 1 ? 's' : ''} ${count > 1 ? 'are' : 'is'} running low on stock or out of stock.
                </p>
            </div>
            <a href="${resolveUrl('dashboard/overview#low-stock')}" style="color: var(--primary); text-decoration: none; font-weight: 600; white-space: nowrap;">
                View Details ->
            </a>
        </div>
    `;
}

/**
 * Cart Functions
 */
function initializeCart() {
    const inventoryButton = document.getElementById('inventoryButton');
    if (!inventoryButton) {
        return;
    }

    refreshCartBadge();

    inventoryButton.addEventListener('click', openCartModal);

    document.addEventListener('click', function(event) {
        const addBtn = event.target.closest('[data-action="add-to-cart"]');
        if (!addBtn) return;

        const productId = parseInt(addBtn.dataset.productId);
        const productName = addBtn.dataset.productName;
        const productPrice = parseFloat(addBtn.dataset.productPrice);
        const availableStock = parseInt(addBtn.dataset.productStock);

        openAddToCartModal(productId, productName, productPrice, availableStock);
    });
}

function openAddToCartModal(productId, productName, productPrice, availableStock) {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.id = 'addToCartModal';

    modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2 class="modal-title">Add To Selection</h2>
                <button class="modal-close" onclick="closeAddToCartModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="addToCartForm" onsubmit="addToCart(event, ${productId})">
                <div class="modal-body">
                    <div style="background: var(--neutral-50); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                        <div style="font-weight: 700; color: var(--neutral-800); margin-bottom: 0.5rem;">${productName}</div>
                        <div style="display: flex; justify-content: space-between; color: var(--neutral-600); font-size: 0.9rem;">
                            <span>Unit Price:</span>
                            <span style="font-weight: 600;">PHP ${productPrice.toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: var(--neutral-600); font-size: 0.9rem; margin-top: 0.25rem;">
                            <span>Available Stock:</span>
                            <span style="font-weight: 600; color: ${availableStock < 10 ? 'var(--warning)' : 'var(--success)'};">${availableStock} units</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cart_quantity">Quantity *</label>
                        <input
                            type="number"
                            id="cart_quantity"
                            name="quantity"
                            min="1"
                            max="${availableStock}"
                            value="1"
                            required
                        >
                        ${availableStock === 0 ? '<small style="color: var(--error); margin-top: 0.5rem; display: block;">Out of stock</small>' : ''}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddToCartModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" ${availableStock === 0 ? 'disabled' : ''}>Select</button>
                </div>
            </form>
        </div>
    `;

    document.body.appendChild(modal);
}

function closeAddToCartModal() {
    const modal = document.getElementById('addToCartModal');
    if (modal) {
        modal.remove();
    }
}

function addToCart(event, productId) {
    event.preventDefault();
    const form = event.target;
    const quantity = parseInt(form.quantity.value);
    const submitBtn = form.querySelector('button[type="submit"]');

    submitBtn.disabled = true;
    submitBtn.textContent = 'Adding...';

    cartRequest('cart/add', { product_id: productId, quantity: quantity })
        .then(data => {
            if (data.success) {
                closeAddToCartModal();
                updateCartBadge(data.cart);
                showNotification('Added to selection.', 'success');
            } else {
                showNotification(data.message || 'Failed to add', 'error');
            }
        })
        .catch(() => {
            showNotification('An error occurred while adding', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add To Selection';
        });
}

function openCartModal() {
    cartRequest('cart', null, 'GET')
        .then(data => {
            if (!data.success) {
                showNotification('Failed to load.', 'error');
                return;
            }
            renderCartModal(data.cart);
        })
        .catch(() => showNotification('Failed to load.', 'error'));
}

function renderCartModal(cart) {
    closeCartModal();

    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.id = 'cartModal';

    const itemsHtml = cart.items.length
        ? cart.items.map(item => {
            const price = Number(item.price).toFixed(2);
            const lineTotal = Number(item.line_total).toFixed(2);
            const imageHtml = item.image_path
                ? `<img src="${resolveUrl(`storage/${item.image_path}`)}" alt="${escapeHtml(item.name)}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 0.5rem;">`
                : `<div style="width: 48px; height: 48px; border-radius: 0.5rem; background: var(--neutral-100); display: flex; align-items: center; justify-content: center; color: var(--neutral-400);">?</div>`;

            return `
                <div class="cart-item">
                    <div class="cart-item-media">${imageHtml}</div>
                    <div class="cart-item-info">
                        <div class="cart-item-name">${escapeHtml(item.name)}</div>
                        <div class="cart-item-meta">PHP ${price} <br> Stock ${item.stock}</div>
                    </div>
                    <div class="cart-item-actions">
                        <input type="number" min="1" max="${item.stock}" value="${item.quantity}" data-cart-qty="${item.product_id}" class="cart-qty-input">
                        <div class="cart-item-total">PHP ${lineTotal}</div>
                        <button class="cart-remove" data-cart-remove="${item.product_id}">Remove</button>
                    </div>
                </div>
            `;
        }).join('')
        : '<div class="cart-empty">Selection is empty.</div>';

    const subtotal = Number(cart.subtotal).toFixed(2);

    modal.innerHTML = `
        <div class="modal-content" style="max-width: 720px;">
            <div class="modal-header">
                <h2 class="modal-title">Selection</h2>
                <button class="modal-close" onclick="closeCartModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="cart-list">${itemsHtml}</div>
                <div class="cart-notes">
                    <label for="cart_notes">Notes (Optional)</label>
                    <textarea id="cart_notes" rows="3" placeholder="Add any notes for this order..."></textarea>
                </div>
                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <span>Total Items</span>
                        <span>${cart.total_items}</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <span>PHP ${subtotal}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="clearCart()">Clear</button>
                <button type="button" class="btn btn-primary" onclick="checkoutCart()" ${cart.items.length ? '' : 'disabled'}>Store</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeCartModal();
        }
    });

    modal.querySelectorAll('[data-cart-qty]').forEach(input => {
        input.addEventListener('change', function() {
            const productId = parseInt(this.dataset.cartQty);
            const quantity = parseInt(this.value);
            updateCartItem(productId, quantity);
        });
    });

    modal.querySelectorAll('[data-cart-remove]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = parseInt(this.dataset.cartRemove);
            removeCartItem(productId);
        });
    });
}

function closeCartModal() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.remove();
    }
}

function updateCartItem(productId, quantity) {
    cartRequest('cart/update', { product_id: productId, quantity: quantity })
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cart);
                renderCartModal(data.cart);
            } else {
                showNotification(data.message || 'Failed to update.', 'error');
                openCartModal();
            }
        })
        .catch(() => showNotification('Failed to update.', 'error'));
}

function removeCartItem(productId) {
    cartRequest('cart/remove', { product_id: productId })
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cart);
                renderCartModal(data.cart);
            } else {
                showNotification(data.message || 'Failed to remove item.', 'error');
            }
        })
        .catch(() => showNotification('Failed to remove item.', 'error'));
}

function clearCart() {
    cartRequest('cart/clear', {})
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cart);
                renderCartModal(data.cart);
            } else {
                showNotification(data.message || 'Failed to clear.', 'error');
            }
        })
        .catch(() => showNotification('Failed to clear.', 'error'));
}

function checkoutCart() {
    const notes = document.getElementById('cart_notes')?.value || '';
    const checkoutBtn = document.querySelector('#cartModal .btn.btn-primary');
    if (checkoutBtn) {
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Processing...';
    }

    cartRequest('cart/checkout', { notes: notes })
        .then(data => {
            if (data.success) {
                closeCartModal();
                updateCartBadge({ items: [], total_items: 0, subtotal: 0 });
                const label = data.order_number ? `Order ${data.order_number} completed successfully!` : 'Checkout completed successfully!';
                showNotification(label, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showNotification(data.message || 'Checkout failed.', 'error');
            }
        })
        .catch(() => showNotification('Checkout failed.', 'error'))
        .finally(() => {
            if (checkoutBtn) {
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = 'Checkout';
            }
        });
}

function refreshCartBadge() {
    cartRequest('cart', null, 'GET')
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cart);
            }
        })
        .catch(() => {});
}

function updateCartBadge(cart) {
    const badge = document.getElementById('inventoryCount');
    if (!badge) return;

    const count = cart.total_items || 0;
    badge.textContent = count;
    badge.style.display = count > 0 ? 'inline-flex' : 'none';
}

function cartRequest(url, payload, method = 'POST') {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    };

    const options = { method, headers };
    if (method !== 'GET') {
        headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(payload || {});
    }

    const endpoint = resolveUrl(url);
    return fetch(endpoint, options)
        .then(response => response.json());
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/**
 * Notification Functions
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        max-width: 400px;
    `;

    const icon = type === 'success'
        ? '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: var(--success);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        : '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: var(--error);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';

    notification.innerHTML = `
        ${icon}
        <div style="flex: 1; color: var(--neutral-800);">${message}</div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Search Functions
 */
function handleSearch(event) {
    if (event.key === 'Enter') {
        const searchValue = event.target.value.trim();
        const url = new URL(window.location);

        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }

        window.location.href = url.toString();
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
