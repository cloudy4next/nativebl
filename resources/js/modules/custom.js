document.addEventListener("DOMContentLoaded", function () {
    const selectField = document.getElementById("GrantType");
    const passwordInput = document.querySelector('input[type="password"]');

    // Check if both elements exist in the DOM
    if (selectField && passwordInput) {
        const selectedValue = localStorage.getItem("selectedValue");

        if (selectedValue) {
            selectField.value = selectedValue;
        }

        function handleDropdownChange() {
            if (selectField.value === "bl_active_directory") {
                passwordInput.disabled = true;
                passwordInput.value = "";
            } else {
                passwordInput.disabled = false;
            }

            localStorage.setItem("selectedValue", selectField.value);
        }

        selectField.addEventListener("change", handleDropdownChange);

        // Run the change handler once on page load to set the correct state
        handleDropdownChange();
    } else {
        // console.error("The select field or password input does not exist in the DOM.");
    }
});
