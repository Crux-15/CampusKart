// Get elements
const modal = document.getElementById("messageModal");
const btn = document.querySelector(".btn-message"); // The "Send Message" button
const span = document.querySelector(".close-modal"); // The "X" button

// Only run this logic if the "Send Message" button exists on the page
if (btn && modal && span) {
    
    // Open Modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Close Modal (X button)
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close Modal (Click outside)
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}