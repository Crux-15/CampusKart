<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <style>
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-update {
            flex: 1;
            padding: 8px;
            background-color: rgb(53, 90, 255);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-weight: bold;
        }

        .btn-delete {
            flex: 1;
            padding: 8px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-update:hover {
            background-color: #000;
        }

        .feed-header h2 {
            font-family: 'Times New Roman', serif;
            border-bottom: 2px solid #78d3f0;
            display: inline-block;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/pages/index" class="logo">CampusKart</a>
            <ul class="nav-links">
                <li><a href="<?php echo URLROOT; ?>/pages/index">Back to Home</a></li>
            </ul>

            <div class="user-actions">
                <a href="<?php echo URLROOT; ?>/products/add" class="sell-btn"> + Sell</a>

                <div class="profile-dropdown">
                    <?php if (!empty($data['user']->profile_image)): ?>
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
                        <a href="<?php echo URLROOT; ?>/users/account" class="menu-item">
                            <span class="icon">&#128100;</span> Account
                        </a>
                        <a href="<?php echo URLROOT; ?>/products/listings" class="menu-item">
                            <span class="icon">&#128230;</span> My Listings
                        </a>
                        <a href="<?php echo URLROOT; ?>/products/notifications" class="menu-item">
                            <span class="icon">&#128276;</span> Notifications
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/messages" class="menu-item"><span class="icon">&#128172;</span> Messages</a>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout">
                            <span class="icon">&#128682;</span> Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-container" style="display: block;">

        <div class="feed-header">
            <h2>My Active Listings</h2>
        </div>

        <div class="product-grid">
            <?php if (empty($data['products'])): ?>
                <p style="grid-column: 1/-1; text-align: center; margin-top: 20px;">You haven't listed any items yet.</p>
            <?php else: ?>
                <?php foreach ($data['products'] as $product) : ?>
                    <div class="product-card">
                        <div class="card-image" style="background-color: #f9f9f9; padding: 0;">
                            <?php if ($product->image != 'no_image.png'): ?>
                                <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/no_image.png" style="width:100%; height:100%; object-fit:cover; opacity: 0.5;">
                            <?php endif; ?>
                        </div>
                        <div class="card-details">
                            <h4><?php echo $product->title; ?></h4>
                            <p class="price"><?php echo $product->price; ?> Tk</p>

                            <div class="btn-group">
                                <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $product->productId; ?>" class="btn-update">
                                    Update
                                </a>
                                <form action="<?php echo URLROOT; ?>/products/delete/<?php echo $product->productId; ?>" method="POST" style="flex:1;">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this item permanently?');">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/inbox.js?v=<?php echo time(); ?>"></script>

</body>

</html>