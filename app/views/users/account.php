<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/profileStyles.css">
    <style>
        /* Extra styles for form inputs */
        .readonly-input {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #6c757d;
            cursor: default;
            font-weight: 500;
        }

        .editable-input {
            border: 1px solid rgb(53, 90, 255);
            background-color: #fff;
            box-shadow: 0 0 5px rgba(53, 90, 255, 0.1);
        }

        #photo-upload {
            display: none;
        }

        /* Plus Icon Trigger */
        .upload-trigger {
            position: absolute;
            bottom: 5px;
            right: 10px;
            background: rgb(53, 90, 255);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 24px;
            font-weight: 400;
            border: 3px solid white;
            transition: transform 0.2s;
            line-height: 0;
            padding-bottom: 4px;
        }

        .upload-trigger:hover {
            transform: scale(1.1);
            background-color: #000;
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
                        <a href="<?php echo URLROOT; ?>/users/messages" class="menu-item"><span class="icon">&#128172;</span> Messages</a>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/products/notifications" class="menu-item">
                            <span class="icon">&#128276;</span> Notifications
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout">
                            <span class="icon">&#128682;</span> Sign Out
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <div class="profile-container">

        <form action="<?php echo URLROOT; ?>/users/account" method="POST" enctype="multipart/form-data" style="display: contents;">

            <aside class="profile-card">
                <div style="position: relative; width: 120px; margin: 0 auto 20px;">

                    <?php if (!empty($data['user']->profile_image)): ?>
                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['user']->profile_image; ?>"
                            style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid #f0f8ff;">
                    <?php else: ?>
                        <div class="profile-avatar-large">
                            <?php echo substr($data['user']->fullname, 0, 1); ?>
                        </div>
                    <?php endif; ?>

                    <label for="photo-upload" class="upload-trigger">+</label>
                    <input type="file" name="profile_photo" id="photo-upload" accept="image/*" onchange="this.form.submit()">
                </div>

                <h2><?php echo $data['user']->fullname; ?></h2>
                <p><?php echo $data['user']->email; ?></p>
                <span class="role-badge">Student Account</span>
            </aside>

            <main class="details-card">
                <div class="details-header">
                    <h3>Personal Information</h3>
                    <button type="submit" class="edit-btn">Save Changes</button>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo $data['user']->fullname; ?>" class="value readonly-input" style="width:100%; padding:8px; border-radius:5px;" readonly>
                    </div>

                    <div class="info-item">
                        <label>Student ID</label>
                        <input type="text" value="<?php echo $data['user']->student_id; ?>" class="value readonly-input" style="width:100%; padding:8px; border-radius:5px;" readonly>
                    </div>

                    <div class="info-item">
                        <label>Gender</label>
                        <input type="text" value="<?php echo $data['user']->gender; ?>" class="value readonly-input" style="width:100%; padding:8px; border-radius:5px;" readonly>
                    </div>

                    <div class="info-item">
                        <label>University Email</label>
                        <input type="text" value="<?php echo $data['user']->email; ?>" class="value readonly-input" style="width:100%; padding:8px; border-radius:5px;" readonly>
                    </div>

                    <div class="info-item">
                        <label style="color: rgb(53, 90, 255);">Department</label>
                        <input type="text" name="department" value="<?php echo $data['user']->department; ?>" class="value readonly-input" style="width:100%; padding:8px; border-radius:5px;" readonly>
                    </div>

                    <div class="info-item">
                        <label style="color: rgb(53, 90, 255);">Batch / Semester</label>
                        <input type="text" name="batch" value="<?php echo $data['user']->batch; ?>" class="value readonly-input" style="width:100%; padding:8px; border-radius:5px;">
                    </div>

                    <div class="info-item">
                        <label style="color: rgb(53, 90, 255);">Mobile Number</label>
                        <input type="text" name="mobile" value="<?php echo $data['user']->mobile; ?>" class="value editable-input" style="width:100%; padding:8px; border-radius:5px;">
                    </div>

                    <div class="info-item">
                        <label style="color: rgb(53, 90, 255);">Secondary Email (Optional)</label>
                        <input type="email" name="secondary_email" placeholder="Add backup email"
                            value="<?php echo isset($data['user']->secondary_email) ? $data['user']->secondary_email : ''; ?>"
                            class="value editable-input" style="width:100%; padding:8px; border-radius:5px;">
                    </div>

                </div>
            </main>
        </form>

    </div>
</body>

</html>