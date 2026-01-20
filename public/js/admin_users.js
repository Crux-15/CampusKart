// admin_users.js
const searchInput = document.getElementById('userSearch');
const tableBody = document.getElementById('userTableBody');

// We use the variable 'rootUrl' which is defined in the PHP view
searchInput.addEventListener('keyup', function () {
    const searchTerm = this.value;

    // Prepare JSON data
    const payload = { query: searchTerm };

    fetch(rootUrl + '/admin/search_users_json', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
        .then(response => response.json())
        .then(users => {
            // Clear the table
            tableBody.innerHTML = '';

            if (users.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:#777;">No students found with that ID.</td></tr>';
                return;
            }

            // Rebuild the table rows
            users.forEach(user => {
                // Determine Status Color Class
                const statusClass = (user.status === 'approved') ? 'status-approved' : 'status-pending';

                const row = `
                <tr>
                    <td>
                        <strong>${user.fullname}</strong><br>
                        <small style="color:#777;">${user.gender}</small>
                    </td>
                    <td>
                        <span style="color:rgb(53,90,255); font-weight:bold;">${user.student_id}</span><br>
                        <small>${user.department}</small>
                    </td>
                    <td>
                        <div>&#9993; ${user.email}</div>
                        <div style="margin-top:4px;">&#128222; ${user.mobile}</div>
                    </td>
                    <td>
                        <span class="status-badge ${statusClass}">
                            ${user.status}
                        </span>
                    </td>
                    <td>
                        <a href="${rootUrl}/admin/delete_user/${user.id}" 
                            class="btn-action btn-reject"
                            onclick="return confirm('Are you sure? This will PERMANENTLY remove this user from the database.');">
                            Delete
                        </a>
                    </td>
                </tr>x
            `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
});