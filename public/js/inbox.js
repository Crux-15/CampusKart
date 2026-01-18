document.addEventListener('DOMContentLoaded', function() {
    
    const forms = document.querySelectorAll('.reply-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // STOP THE REFRESH!

            const formData = new FormData(this);
            const inputField = this.querySelector('.reply-input');
            const submitBtn = this.querySelector('.reply-btn');
            
            // Find the chat body related to THIS form
            const card = this.closest('.conversation-card');
            const chatBody = card.querySelector('.conversation-body');

            submitBtn.disabled = true;
            submitBtn.innerText = '...';

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Create HTML for new message
                    const newBubble = `
                        <div class="msg-row me">
                            <div class="msg-bubble" style="animation: fadeIn 0.3s; background: rgb(53, 90, 255); color: white; border-radius: 15px 0 15px 15px; padding: 12px 18px; max-width: 70%; margin-left: auto;">
                                <div class="msg-meta" style="color: rgba(255,255,255,0.8);">
                                    <span class="product-badge" style="background: rgba(255,255,255,0.2);">Just Now</span>
                                    <span>${data.time}</span>
                                </div>
                                ${data.message}
                            </div>
                        </div>
                    `;

                    // Add to chat
                    chatBody.insertAdjacentHTML('beforeend', newBubble);
                    chatBody.scrollTop = chatBody.scrollHeight;
                    inputField.value = ''; // Clear input
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error. Check console.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Send';
            });
        });
    });

    // --- DELETE CONVERSATION LOGIC ---
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop refresh

            // 1. Show Confirmation Popup
            // Standard browser confirm gives "OK/Cancel". 
            // "OK" counts as "Yes".
            const choice = confirm("Are you sure to delete the whole message?");

            if (choice) { // User clicked "OK" (Yes)
                
                const formData = new FormData(this);
                const card = this.closest('.conversation-card');

                // 2. Send Delete Request
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        // 3. Remove the conversation card from screen visually
                        card.style.transition = "opacity 0.5s";
                        card.style.opacity = "0";
                        setTimeout(() => card.remove(), 500);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => console.error(err));
            } 
            // If choice is false (User clicked Cancel/No), we do nothing.
        });
    });
});