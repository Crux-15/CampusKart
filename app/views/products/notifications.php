<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/notificationPage.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/pages/index" class="logo">CampusKart</a>
            <ul class="nav-links">
                <li><a href="<?php echo URLROOT; ?>/pages/index">Back to Home</a></li>
            </ul>
            <div class="user-actions">
                <div class="profile-dropdown">
                    <?php if(!empty($data['user']->profile_image)): ?>
                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['user']->profile_image; ?>" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid white; cursor: pointer;">
                    <?php else: ?>
                        <div class="avatar-circle">
                            <?php echo substr($data['user']->fullname, 0, 1); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="dropdown-menu">
                        <div class="user-info">
                            <strong><?php echo $data['user']->fullname; ?></strong>
                            <small>Student</small>
                        </div>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/account" class="menu-item"><span class="icon">&#128100;</span> Account</a>
                        <a href="<?php echo URLROOT; ?>/products/listings" class="menu-item"><span class="icon">&#128230;</span> My Listings</a>
                        <a href="<?php echo URLROOT; ?>/products/notifications" class="menu-item"><span class="icon">&#128276;</span> Notifications</a>
                        <a href="<?php echo URLROOT; ?>/users/messages" class="menu-item"><span class="icon">&#128172;</span> Messages</a>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout"><span class="icon">&#128682;</span> Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="notif-container">
        
        <div class="notif-header">
            <h2>New Arrivals</h2>
        </div>

        <?php if(empty($data['notifications'])): ?>
            <div class="empty-state">
                <h3>All caught up!</h3>
                <p>No new listings from other students yet.</p>
            </div>
        <?php else: ?>
            
            <?php foreach($data['notifications'] as $item): ?>
                <a href="<?php echo URLROOT; ?>/products/show/<?php echo $item->id; ?>" class="notif-row">
                    
                    <div class="notif-text">
                        A new <b><?php echo $item->title; ?></b> is up for sale
                    </div>

                    <div class="notif-time">
                        <?php echo date('M d', strtotime($item->created_at)); ?>
                    </div>

                </a>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</body>
</html>