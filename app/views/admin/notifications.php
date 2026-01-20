<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin Panel</title>
    
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminNotifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/admin/index" class="logo">CampusKart Admin</a>
            
            <div class="user-actions">
                <a href="<?php echo URLROOT; ?>/admin/index" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="notif-container">
        
        <h2 class="page-title">
            <i class="fas fa-bell" style="color: #f1c40f;"></i> New Notifications
        </h2>

        <?php if(empty($data['users']) && empty($data['products'])): ?>
            
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h3>All Caught Up!</h3>
                <p>There are no pending requests at this time.</p>
            </div>

        <?php else: ?>

            <?php foreach($data['users'] as $user): ?>
                <div class="notif-card type-user">
                    <div class="notif-icon bg-user">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="notif-content">
                        <div class="notif-title">New User Registration</div>
                        <div class="notif-desc">
                            <strong><?php echo $user->fullname; ?></strong> (<?php echo $user->department; ?>) wants to join.
                        </div>
                    </div>
                    <div class="notif-action">
                        <a href="<?php echo URLROOT; ?>/admin/pending_users" class="btn-review user">Review Request</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php foreach($data['products'] as $product): ?>
                <div class="notif-card type-product">
                    <div class="notif-icon bg-product">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="notif-content">
                        <div class="notif-title">New Product Listing</div>
                        <div class="notif-desc">
                            New item <strong>"<?php echo $product->title; ?>"</strong> added in <?php echo $product->category; ?>.
                        </div>
                    </div>
                    <div class="notif-action">
                        <a href="<?php echo URLROOT; ?>/admin/pending_products" class="btn-review product">Review Request</a>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>
</html>