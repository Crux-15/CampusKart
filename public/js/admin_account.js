document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photoInput');
    const photoError = document.getElementById('photoError');
    const saveBtn = document.getElementById('saveBtn');

    // Only run if elements exist to prevent errors
    if(photoInput && photoError && saveBtn) {
        
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                const fileType = file.type;
                // List of allowed MIME types
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                if (!validTypes.includes(fileType)) {
                    // --- INVALID FILE ---
                    
                    // 1. Show Error Message
                    photoError.style.display = 'block';
                    
                    // 2. Clear the input (so the bad file isn't uploaded)
                    this.value = ''; 
                    
                    // 3. Disable the Save Button
                    saveBtn.disabled = true;
                    saveBtn.style.opacity = '0.5';
                    saveBtn.style.cursor = 'not-allowed';
                } else {
                    // --- VALID FILE ---
                    
                    // 1. Hide Error
                    photoError.style.display = 'none';
                    
                    // 2. Enable Save Button
                    saveBtn.disabled = false;
                    saveBtn.style.opacity = '1';
                    saveBtn.style.cursor = 'pointer';
                }
            }
        });
    }
});