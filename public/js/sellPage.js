const fileInput = document.getElementById('file-upload');
const errorMsg = document.getElementById('file-error');
const previewImg = document.getElementById('preview-img');
const uploadContent = document.getElementById('upload-content');

if(fileInput) {
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        
        // 1. If a file is selected
        if (file) {
            // Get file extension
            const fileType = file.type;
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            // 2. Validate Type
            if (!validTypes.includes(fileType)) {
                // INVALID: Show Red Error
                errorMsg.style.display = 'block';
                
                // Reset Input & Preview
                this.value = ''; 
                previewImg.style.display = 'none';
                uploadContent.style.display = 'block'; // Show text again
            } else {
                // VALID: Hide Error
                errorMsg.style.display = 'none';

                // 3. Show Real-time Preview
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block'; // Show Image
                    uploadContent.style.display = 'none'; // Hide "Drag & Drop" text
                }
                
                reader.readAsDataURL(file);
            }
        } else {
            // If user cancels selection, reset view
            previewImg.style.display = 'none';
            uploadContent.style.display = 'block';
            errorMsg.style.display = 'none';
        }
    });
}