<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['product']->title; ?> - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/productDetails.css">
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
                        <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <a href="<?php echo URLROOT; ?>/admin/index" class="menu-item" style="color: red; font-weight: bold; background-color: #fff0f0;">
                                <span class="icon">&#128736;</span> Admin Panel
                            </a>
                            <hr>
                        <?php endif; ?>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout"><span class="icon">&#128682;</span> Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="product-container">
        
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>/pages/index">Home</a>
            <span>></span>
            <a href="#"><?php echo $data['product']->category; ?></a>
            <span>></span>
            <?php echo $data['product']->title; ?>
        </div>

        <div class="details-grid">
            
            <div class="product-image-box">
                <?php if($data['product']->image != 'no_image.png'): ?>
                    <img src="<?php echo URLROOT; ?>/img/<?php echo $data['product']->image; ?>" alt="Product Image">
                <?php else: ?>
                    <img src="<?php echo URLROOT; ?>/img/no_image.png" style="opacity: 0.5;" alt="No Image">
                <?php endif; ?>
            </div>

            <div class="product-info-box">
                
                <div>
                    <h1 class="product-title"><?php echo $data['product']->title; ?></h1>
                    <div class="product-meta" style="margin-top: 10px;">
                        <span class="category-tag"><?php echo $data['product']->category; ?></span>
                        <span class="condition-tag">Used (Good)</span> 
                    </div>
                </div>

                <div class="product-price">
                    <?php echo $data['product']->price; ?> Tk
                </div>

                <div class="product-desc">
                    <strong>Description:</strong><br>
                    <?php echo nl2br($data['product']->description); ?>
                </div>

                <div class="seller-card">
                    <div class="seller-header">
                        <?php if(!empty($data['product']->seller_image)): ?>
                            <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['product']->seller_image; ?>" class="seller-avatar">
                        <?php else: ?>
                            <div class="avatar-circle" style="width: 50px; height: 50px; font-size: 20px;">
                                <?php echo substr($data['product']->fullname, 0, 1); ?>
                            </div>
                        <?php endif; ?>

                        <div class="seller-details">
                            <h4><?php echo $data['product']->fullname; ?></h4>
                            <p><?php echo $data['product']->department; ?> | Batch <?php echo $data['product']->batch; ?></p>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <?php if($_SESSION['user_id'] != $data['product']->seller_id): ?>
                            <form action="<?php echo URLROOT; ?>/products/toggleInterest/<?php echo $data['product']->id; ?>" method="POST">
                                <?php if($data['isInterested']): ?>
                                    <button type="submit" class="btn-interest active" style="width:100%">
                                        <span>&#10006;</span> Remove Request
                                    </button>
                                    <small style="text-align: center; display: block; color: green; margin-top: 5px;">
                                        &#10003; Seller has your number
                                    </small>
                                <?php else: ?>
                                    <button type="submit" class="btn-interest" style="width:100%">
                                        <span>&#10084;</span> I'm Interested
                                    </button>
                                <?php endif; ?>
                            </form>

                            <button class="btn-message">
                                <span>&#9993;</span> Send Message
                            </button>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $data['product']->id; ?>" class="btn-message" style="text-align:center;">
                                &#9998; Edit My Listing
                            </a>
                            <div class="interested-section">
                                <h4 style="margin-top: 0; color: #333;">Interested Buyers</h4>
                                <?php if(empty($data['interestedBuyers'])): ?>
                                    <p style="color: #777; font-size: 14px;">No interest shown yet.</p>
                                <?php else: ?>
                                    <?php foreach($data['interestedBuyers'] as $buyer): ?>
                                        <div class="buyer-item">
                                            <div class="buyer-info">
                                                <div class="avatar-circle" style="width: 30px; height: 30px; font-size: 14px;">
                                                    <?php echo substr($buyer->fullname, 0, 1); ?>
                                                </div>
                                                <span><?php echo $buyer->fullname; ?></span>
                                            </div>
                                            <div class="buyer-phone">&#128222; <?php echo $buyer->mobile; ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div> </div>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 style="margin-top:0; font-family:'Times New Roman', serif;">Contact Seller</h2>
            <p style="font-size:14px; color:#666;">Write a message to <strong><?php echo $data['product']->fullname; ?></strong> about this item.</p>
            
            <form action="<?php echo URLROOT; ?>/products/message/<?php echo $data['product']->id; ?>" method="POST">
                <textarea name="message_body" class="modal-textarea" placeholder="Hi, is this still available? I am interested..." required></textarea>
                <button type="submit" class="btn-send-real">Send Message</button>
            </form>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/productDetails.js"></script>


</body>
</html>