<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - CampusKart</title>
    
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminAccountStyles.css">
    
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

    <div class="account-container">
        
        <div class="profile-sidebar">
            <?php if(!empty($data['user']->profile_image)): ?>
                <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['user']->profile_image; ?>" class="profile-img" alt="Profile">
            <?php else: ?>
                <img src="https://via.placeholder.com/150" class="profile-img" alt="Profile">
            <?php endif; ?>
            
            <div class="user-name"><?php echo $data['user']->fullname; ?></div>
            <div class="user-role">Administrator</div>
            
            <div class="user-email-badge">
                <?php echo $data['user']->email; ?>
            </div>
        </div>

        <div class="profile-content">
            <h2 class="form-header">Profile Settings</h2>
            
            <form action="<?php echo URLROOT; ?>/admin/account" method="POST" enctype="multipart/form-data" id="profileForm">
                
                <div class="form-group">
                    <label>Change Profile Photo</label>
                    
                    <input type="file" name="profile_photo" id="photoInput" class="form-control" style="padding: 9px;" accept=".jpg, .jpeg, .png">
                    
                    <small id="photoError" style="color: #e74c3c; display: none; margin-top: 5px; font-weight: bold;">
                        <i class="fas fa-exclamation-circle"></i> Invalid file! Only JPG, JPEG, and PNG are allowed.
                    </small>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control read-only" value="<?php echo $data['user']->fullname; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo $data['user']->mobile; ?>">
                </div>

                <div class="form-group">
                    <label>Secondary Email (Recovery)</label>
                    <input type="email" name="secondary_email" class="form-control" value="<?php echo $data['user']->secondary_email; ?>">
                </div>

                <div class="form-group">
                    <label>Admin ID</label>
                    <input type="text" class="form-control read-only" value="<?php echo $data['user']->student_id; ?>" readonly>
                </div>

                <button type="submit" class="btn-main" id="saveBtn">Save Changes</button>
            </form>
        </div>

    </div>

    <script src="<?php echo URLROOT; ?>/js/admin_account.js"></script>

</body>
</html>