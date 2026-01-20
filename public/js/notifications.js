document.addEventListener('DOMContentLoaded', function() {
    
    const notifBtn = document.getElementById('notif-btn');
    const notifList = document.getElementById('notif-list');

    if(notifBtn && notifList) {
        
        // Toggle Dropdown
        notifBtn.addEventListener('click', function(e) {
            e.preventDefault();
            notifList.classList.toggle('show');

            // Only load data if we are opening it
            if(notifList.classList.contains('show')) {
                loadNotifications();
            }
        });

        // Close if clicked outside
        window.addEventListener('click', function(e) {
            if (!notifBtn.contains(e.target) && !notifList.contains(e.target)) {
                notifList.classList.remove('show');
            }
        });
    }

    function loadNotifications() {
        // Use relative path to controller
        fetch(window.location.origin + '/CampusKart/products/notifications') 
        .then(response => response.json())
        .then(data => {
            notifList.innerHTML = ''; // Clear loading text

            if(data.length > 0) {
                data.forEach(item => {
                    // Create the Link
                    const link = document.createElement('a');
                    link.className = 'notif-item';
                    link.href = window.location.origin + '/CampusKart/products/show/' + item.id;
                    
                    // The text format you asked for:
                    link.innerHTML = `A new <b>${item.title}</b> is up for sale`;
                    
                    notifList.appendChild(link);
                });
            } else {
                notifList.innerHTML = '<div class="notif-empty">No new items recently</div>';
            }
        })
        .catch(err => {
            console.error(err);
            notifList.innerHTML = '<div class="notif-empty">Error loading</div>';
        });
    }
});