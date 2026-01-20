<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CampusKart</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminStyles.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/admin/index" class="logo">CampusKart Admin</a>

            <div class="nav-links" style="display: flex; align-items: center;">

                <a href="<?php echo URLROOT; ?>/pages/index" class="sell-btn" style="border: 1px solid white; color: white; margin-right: 20px; text-decoration: none;">
                    Student View
                </a>

                <div class="profile-dropdown">
                    <div class="profile-btn">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo $_SESSION['user_name']; ?></span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem; margin-left: 5px;"></i>
                    </div>

                    <div class="dropdown-menu">
                        <a href="<?php echo URLROOT; ?>/admin/account">
                            <i class="fas fa-user-cog"></i> Account
                        </a>
                        <a href="<?php echo URLROOT; ?>/admin/notifications">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                        <div style="height: 1px; background: #eee;"></div>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <div class="dashboard-container">

        <div class="dashboard-header">
            <h2>Admin Control Panel</h2>
            <p>Manage users, products, and site security</p>
        </div>

        <div class="admin-grid">

            <a href="<?php echo URLROOT; ?>/admin/users" class="admin-card">
                <div class="card-icon">&#128101;</div>
                <div class="card-title">User List</div>
            </a>

            <a href="<?php echo URLROOT; ?>/admin/pending_users" class="admin-card">
                <div class="card-icon">&#128100;?</div>
                <div class="card-title">User Requests</div>
                <?php if (isset($data['pending_users_count']) && $data['pending_users_count'] > 0): ?>
                    <div class="card-count"><?php echo $data['pending_users_count']; ?> Pending</div>
                <?php endif; ?>
            </a>

            <a href="<?php echo URLROOT; ?>/admin/pending_products" class="admin-card">
                <div class="card-icon">&#128722;</div>
                <div class="card-title">Product Requests</div>
                <?php if (isset($data['pending_products_count']) && $data['pending_products_count'] > 0): ?>
                    <div class="card-count"><?php echo $data['pending_products_count']; ?> Pending</div>
                <?php endif; ?>
            </a>

            <a href="<?php echo URLROOT; ?>/admin/create_admin" class="admin-card">
                <div class="card-icon">&#128110;</div>
                <div class="card-title">Create Admin</div>
            </a>

            <a href="<?php echo URLROOT; ?>/admin/products" class="admin-card">
                <div class="card-icon">&#128230;</div>
                <div class="card-title">Product List</div>
            </a>

        </div>
    </div>

</body>

</html>