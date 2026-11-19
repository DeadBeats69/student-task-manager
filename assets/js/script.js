// Confirm delete and other interactions
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation modals
    const deleteButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#deleteModal"]');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const itemName = this.getAttribute('data-item-name');
            const modalTarget = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalTarget);
            if (modal && itemName) {
                const nameSpan = modal.querySelector('.delete-item-name');
                if (nameSpan) {
                    nameSpan.textContent = itemName;
                }
            }
        });
    });

    // Handle clickable task rows to open detail modal
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-task-row');
        // Do not trigger row click if clicking links, buttons, form controls, or action buttons cell
        if (row && !e.target.closest('a, button, .btn, form, input, select, textarea, .action-buttons-cell, [data-bs-toggle="modal"]')) {
            const targetModal = row.getAttribute('data-task-modal') || row.getAttribute('data-bs-target');
            if (targetModal) {
                const modalEl = document.querySelector(targetModal);
                if (modalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
            }
        }
    });
});

