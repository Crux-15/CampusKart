<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/sellpageStyles.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/pages/index" class="logo">CampusKart</a>
            <ul class="nav-links">
                <li><a href="<?php echo URLROOT; ?>/products/listings">Back to Listings</a></li>
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

    <div class="main-container">
        <div class="sell-container">
            <div class="form-header">
                <h2>Edit Listing</h2>
            </div>

            <form action="<?php echo URLROOT; ?>/products/edit/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">
                
                <div class="form-section">
                    <label>Product Title</label>
                    <input type="text" name="title" value="<?php echo $data['title']; ?>" required>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Price (Tk)</label>
                            <input type="number" name="price" value="<?php echo $data['price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" required>
                                <option value="<?php echo $data['category']; ?>" selected><?php echo $data['category']; ?></option>
                                <option value="Books">Books</option>                                
                                <option value="Stationery">Stationery</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Furniture">Furniture</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <label>Description</label>
                    <textarea name="description" rows="5" required><?php echo $data['description']; ?></textarea>
                </div>

                <div class="form-section">
                    <label>Change Photo (Optional)</label>
                    <div class="upload-box">
                        <input type="file" name="product_image" accept="image/*">
                        <p>Click to upload new image</p>
                    </div>
                    <?php if(!empty($data['image']) && $data['image'] != 'no_image.png'): ?>
                        <p style="font-size: 13px; color: green; margin-top: 5px;">&#10003; Current Image exists</p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-ad-btn">Update Listing</button>
            </form>
        </div>
    </div>


</body>
</html>