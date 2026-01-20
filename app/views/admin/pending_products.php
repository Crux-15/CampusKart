<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Product Requests - CampusKart Admin</title>
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
        
        <div class="dashboard-header">
            <h2>Ad Moderation Queue</h2>
            <p>Review items before they go live on the marketplace</p>
        </div>

        <?php if(empty($data['products'])): ?>
            
            <div class="admin-card placeholder" style="height: auto; padding: 50px;">
                <div class="empty-msg">No pending ads. Great job!</div>
                <a href="<?php echo URLROOT; ?>/admin/index" style="color: rgb(53,90,255);">Go Back</a>
            </div>

        <?php else: ?>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Details</th>
                            <th>Seller Info</th>
                            <th>Date Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['products'] as $product): ?>
                            <tr>
                                <td>
                                    <?php if($product->image != 'no_image.png'): ?>
                                        <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>" class="admin-product-img">
                                    <?php else: ?>
                                        <div class="admin-product-img" style="background:#eee; display:flex; align-items:center; justify-content:center; font-size:10px; color:#999;">No Img</div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <strong><?php echo $product->title; ?></strong><br>
                                    <span style="color: rgb(53,90,255); font-weight: bold;">
                                        <?php echo $product->price; ?> Tk
                                    </span><br>
                                    <small style="color:#777;"><?php echo $product->category; ?></small>
                                </td>

                                <td>
                                    <div style="font-weight: 500;"><?php echo $product->fullname; ?></div>
                                    <small style="color: #666;">ID: <?php echo $product->student_id; ?></small>
                                </td>

                                <td style="color: #666; font-size: 13px;">
                                    <?php echo date('M d', strtotime($product->created_at)); ?><br>
                                    <?php echo date('h:i A', strtotime($product->created_at)); ?>
                                </td>

                                <td>
                                    <a href="<?php echo URLROOT; ?>/admin/approve_product/<?php echo $product->id; ?>" 
                                       class="btn-action btn-approve"
                                       onclick="return confirm('Approve this ad?');">
                                       &#10003; Approve
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/admin/delete_product/<?php echo $product->id; ?>" 
                                       class="btn-action btn-reject"
                                       onclick="return confirm('Delete this ad permanently?');">
                                       &#10005; Reject
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>

</body>
</html> 