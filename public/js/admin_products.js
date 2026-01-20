// admin_products.js
const searchInput = document.getElementById('productSearch');
const tableBody = document.getElementById('productTableBody');

searchInput.addEventListener('keyup', function() {
    const searchTerm = this.value;
    const payload = { query: searchTerm };

    fetch(rootUrl + '/admin/search_products_json', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(products => {
        tableBody.innerHTML = '';

        if(products.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:#777;">No products found.</td></tr>';
            return;
        }

        products.forEach(product => {
            // Determine Status Color
            const statusClass = (product.status === 'approved') ? 'status-approved' : 'status-pending';
            
            // Image Logic
            let imgHtml = '';
            if(product.image && product.image !== 'no_image.png') {
                imgHtml = `<img src="${rootUrl}/img/${product.image}" class="admin-product-img">`;
            } else {
                imgHtml = `<div class="admin-product-img" style="background:#eee; display:flex; align-items:center; justify-content:center; font-size:10px; color:#999;">No Img</div>`;
            }

            const row = `
                <tr>
                    <td>${imgHtml}</td>
                    <td>
                        <strong>${product.title}</strong><br>
                        <small style="color:rgb(53,90,255); font-weight:bold;">${product.price} Tk</small>
                    </td>
                    <td>
                        <div style="font-weight:500;">${product.fullname}</div>
                        <small style="color:#666;">ID: ${product.student_id}</small>
                    </td>
                    <td>
                        <span class="status-badge ${statusClass}">
                            ${product.status}
                        </span><br>
                        <small style="color:#777;">${product.category}</small>
                    </td>
                    <td>
                        <a href="${rootUrl}/admin/delete_product/${product.id}" 
                           class="btn-action btn-reject"
                           onclick="return confirm('Permanently delete this product?');">
                           Delete
                        </a>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    })
    .catch(error => console.error('Error:', error));
});