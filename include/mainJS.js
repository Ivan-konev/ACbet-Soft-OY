document.addEventListener('DOMContentLoaded', function () {
    // Check if we are on create or edit pages
    const currentPage = window.location.pathname;

    // Only run the script if on create-product.php or edit-product.php
    if (currentPage.includes('create-product.php') || currentPage.includes('edit-product.php')) {
        
        const selectBox = document.getElementById('selectBox');
        const checkboxList = document.getElementById('checkboxList');
        const selectedDisplay = document.getElementById('selectedGenresDisplay');

        // Toggle dropdown visibility
        selectBox.addEventListener('click', function () {
            checkboxList.style.display = checkboxList.style.display === 'block' ? 'none' : 'block';
        });

        // Update display when a checkbox is checked/unchecked
        checkboxList.addEventListener('change', () => {
            const checked = checkboxList.querySelectorAll('input:checked');
            const selected = Array.from(checked).map(cb => cb.value);
            selectedDisplay.textContent = selected.length ? selected.join(', ') : 'Select genres';
        });

        // Close dropdown if clicked outside
        document.addEventListener('click', (e) => {
            if (!selectBox.contains(e.target)) {
                checkboxList.style.display = 'none';
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.select-book');
    const selectAll = document.getElementById('select-all');
    const bulkPanel = document.getElementById('bulk-edit-panel');
    const selectedIdsInput = document.getElementById('selected-ids');

    function updatePanelVisibility() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        if (selected.length > 0) {
            bulkPanel.style.display = 'block';
            selectedIdsInput.value = selected.map(cb => cb.value).join(',');
        } else {
            bulkPanel.style.display = 'none';
            selectedIdsInput.value = '';
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updatePanelVisibility));
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updatePanelVisibility();
    });
});