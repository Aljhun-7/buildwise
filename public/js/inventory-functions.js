/**
 * BuildWise Inventory Management - Image & Activity Log Functions
 * Add this script to your user dashboard blade file before </body>
 */

// Image Preview Function
function previewImage(event, type) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(`${type}_image_preview`);
            const container = preview.parentElement;
            const placeholder = container.querySelector('.image-placeholder');
            const removeBtn = container.querySelector('.image-remove-btn');
            
            preview.src = e.target.result;
            preview.classList.add('active');
            placeholder.style.display = 'none';
            removeBtn.classList.add('active');
            container.classList.add('has-image');
        }
        reader.readAsDataURL(file);
    }
}

// Remove Image Function
function removeImage(event, type) {
    event.stopPropagation();
    
    const preview = document.getElementById(`${type}_image_preview`);
    const container = preview.parentElement;
    const placeholder = container.querySelector('.image-placeholder');
    const removeBtn = container.querySelector('.image-remove-btn');
    const fileInput = document.getElementById(`${type}_image`);
    
    preview.src = '';
    preview.classList.remove('active');
    placeholder.style.display = 'block';
    removeBtn.classList.remove('active');
    container.classList.remove('has-image');
    fileInput.value = '';
    
    // For edit modal, mark image for removal
    if (type === 'edit') {
        document.getElementById('edit_remove_image').value = '1';
    }
}

// Show Full Image Modal
function showImageModal(imageSrc, productName) {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.style.cursor = 'pointer';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 900px;" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2 class="modal-title">${productName}</h2>
                <button class="modal-close" onclick="this.closest('.modal').remove()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <img src="${imageSrc}" alt="${productName}" style="width: 100%; height: auto; max-height: 70vh; object-fit: contain; border-radius: 0 0 1.25rem 1.25rem;">
            </div>
        </div>
    `;
    modal.onclick = function() { modal.remove(); };
    document.body.appendChild(modal);
}

// Show Activity Log
function showActivityLog(productId) {
    fetch(`/products/${productId}/activity-log`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayActivityLogModal(data.product, data.logs.data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load activity log.');
    });
}

// Display Activity Log Modal
function displayActivityLogModal(product, logs) {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.id = 'activityLogModal';
    
    let logsHtml = '';
    if (logs && logs.length > 0) {
        logs.forEach(log => {
            const iconSvg = getActivityIconSvg(log.action);
            const userName = log.user ? log.user.name : 'System';
            logsHtml += `
                <div class="activity-log-item">
                    <div class="activity-icon ${log.action}">
                        ${iconSvg}
                    </div>
                    <div class="activity-details">
                        <div class="activity-action">${getActionLabel(log.action)}</div>
                        <div class="activity-description">${log.description}</div>
                        <div class="activity-meta">
                            <span style="display: flex; align-items: center; gap: 0.25rem;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                ${userName}
                            </span>
                            <span style="display: flex; align-items: center; gap: 0.25rem;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                ${formatDateTime(log.created_at)}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        logsHtml = `
            <div style="text-align: center; padding: 3rem 2rem; color: var(--neutral-500);">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 60px; height: 60px; margin: 0 auto 1rem; stroke: var(--neutral-300);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>No activity recorded yet</p>
            </div>
        `;
    }
    
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2 class="modal-title">Activity Log: ${product.name}</h2>
                <button class="modal-close" onclick="closeActivityLogModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body" style="padding: 0; max-height: 500px; overflow-y: auto;">
                ${logsHtml}
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeActivityLogModal()">Close</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Close Activity Log Modal
function closeActivityLogModal() {
    const modal = document.getElementById('activityLogModal');
    if (modal) {
        modal.remove();
    }
}

// Get Activity Icon SVG
function getActivityIconSvg(action) {
    const icons = {
        created: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>',
        updated: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>',
        archived: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>',
        restored: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>'
    };
    return icons[action] || icons.updated;
}

// Get Action Label
function getActionLabel(action) {
    const labels = {
        created: 'Added Product',
        updated: 'Updated Product',
        archived: 'Archived Product',
        restored: 'Restored Product'
    };
    return labels[action] || action.charAt(0).toUpperCase() + action.slice(1);
}

// Format DateTime
function formatDateTime(dateString) {
    if (window.buildWiseLogsRealtime) {
        return window.buildWiseLogsRealtime.formatRelativeOrAbsolute(dateString);
    }

    const date = new Date(dateString);
    return Number.isNaN(date.getTime()) ? '' : date.toLocaleString();
}

// Enhanced openEditModal with image support
function openEditModal(product) {
    document.getElementById('editModal').classList.add('active');
    document.getElementById('editForm').action = `/products/${product.id}`;
    document.getElementById('edit_name').value = product.name;
    document.getElementById('edit_sku').value = product.sku;
    document.getElementById('edit_category').value = product.category || '';
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_quantity').value = product.quantity;
    document.getElementById('edit_description').value = product.description || '';
    document.getElementById('edit_remove_image').value = '0';
    
    // Reset file input
    document.getElementById('edit_image').value = '';
    
    // Show existing image if available
    const preview = document.getElementById('edit_image_preview');
    const container = preview.parentElement;
    const placeholder = container.querySelector('.image-placeholder');
    const removeBtn = container.querySelector('.image-remove-btn');
    
    if (product.image_path) {
        preview.src = `/storage/${product.image_path}`;
        preview.classList.add('active');
        placeholder.style.display = 'none';
        removeBtn.classList.add('active');
        container.classList.add('has-image');
    } else {
        preview.src = '';
        preview.classList.remove('active');
        placeholder.style.display = 'block';
        removeBtn.classList.remove('active');
        container.classList.remove('has-image');
    }
}

// Handle form submissions with FormData for file uploads
document.addEventListener('DOMContentLoaded', function() {
    const parseResponseData = async (response) => {
        const text = await response.text();
        let data = null;
        try {
            data = text ? JSON.parse(text) : null;
        } catch (e) {
            data = null;
        }
        return { response, data };
    };

    // Add Form Submit
    const addForm = document.getElementById('addForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(parseResponseData)
            .then(({ response, data }) => {
                // Support both JSON API responses and redirect/HTML responses.
                if ((data && data.success) || response.redirected || response.ok) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to add product'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the product.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
    
    // Edit Form Submit
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Updating...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(parseResponseData)
            .then(({ response, data }) => {
                // Support both JSON API responses and redirect/HTML responses.
                if ((data && data.success) || response.redirected || response.ok) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update product'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the product.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
});
