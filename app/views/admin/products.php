<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products - CampusKart Admin</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminStyles.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/admin/index" class="logo">CampusKart Admin</a>
            <div class="user-actions">
                <a href="<?php echo URLROOT; ?>/admin/index" class="sell-btn">Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        
        <div class="dashboard-header header-flex">
            <div class="header-info">
                <h2 class="page-title">Product Database</h2>
                <p class="page-subtitle">Manage all active listings</p>
            </div>
            
            <div class="search-container">
                <input type="text" id="productSearch" class="admin-search-input" placeholder="Search by Product Name...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="th-image">Image</th>
                        <th>Product Details</th>
                        <th>Seller Info</th>
                        <th>Status / Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php foreach($data['products'] as $product): ?>
                        <tr>
                            <td>
                                <?php if($product->image != 'no_image.png'): ?>
                                    <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>" class="admin-product-img">
                                <?php else: ?>
                                    <div class="admin-product-img img-placeholder">No Img</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo $product->title; ?></strong><br>
                                <span class="price-tag">
                                    <?php echo $product->price; ?> Tk
                                </span>
                            </td>
                            <td>
                                <div class="seller-name"><?php echo $product->fullname; ?></div>
                                <small class="text-muted">ID: <?php echo isset($product->student_id) ? $product->student_id : 'N/A'; ?></small>
                            </td>
                            <td>
                                <span class="status-badge <?php echo ($product->status == 'approved') ? 'status-approved' : 'status-pending'; ?>">
                                    <?php echo $product->status; ?>
                                </span><br>
                                <small class="text-light"><?php echo $product->category; ?></small>
                            </td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/admin/delete_product/<?php echo $product->id; ?>" 
                                   class="btn-action btn-reject"
                                   onclick="return confirm('Permanently delete this product?');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        const rootUrl = "<?php echo URLROOT; ?>";
    </script>
    
    <script src="<?php echo URLROOT; ?>/js/admin_products.js"></script>

</body>
</html>