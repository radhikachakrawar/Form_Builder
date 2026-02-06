document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(e) {
        const fileInputs = document.querySelectorAll("input[type='file']");
        for (let input of fileInputs) {
            if (input.files.length > 0) {
                const file = input.files[0];
                const allowedTypes = ["application/pdf"];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    alert("❌ Only PDF files are allowed.");
                    return false;
                }

                if (file.size > maxSize) {
                    e.preventDefault();
                    alert("❌ File too large. Max 5MB allowed.");
                    return false;
                }
            }
        }
    });
});
