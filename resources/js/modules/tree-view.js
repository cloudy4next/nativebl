document.addEventListener("DOMContentLoaded", function() {
    const parentCheckboxes = document.querySelectorAll('.parent-checkbox');
    const childCheckboxes = document.querySelectorAll('.child-checkbox');

    parentCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            const parentId = this.getAttribute('data-parent-id');
            const relatedChildCheckboxes = document.querySelectorAll(`.child-checkbox[data-parent-id="${parentId}"]`);

            relatedChildCheckboxes.forEach(function(childCheckbox) {
                childCheckbox.checked = this.checked;
            }, this);
        });
    });

    childCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            const parentId = this.getAttribute('data-parent-id');
            const parentCheckbox = document.querySelector(`.parent-checkbox[data-parent-id="${parentId}"]`);
            const allChildCheckboxes = document.querySelectorAll(`.child-checkbox[data-parent-id="${parentId}"]`);

            parentCheckbox.checked = Array.from(allChildCheckboxes).every(function(child) {
                return child.checked;
            });
        });
    });
});
