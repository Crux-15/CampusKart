<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusKart - Sell Item</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/sellpageStyles.css">
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
                        <a href="<?php echo URLROOT; ?>/products/notifications" class="menu-item">
                            <span class="icon">&#128276;</span> Notifications
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/messages" class="menu-item"><span class="icon">&#128172;</span> Messages</a>
                        </a>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout">
                            <span class="icon">&#128682;</span> Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="sell-container">
            <div class="form-header">
                <h2>Rehome Your Essentials</h2>
                <p>Seamlessly trade your unused items with the student community</p>
            </div>

            <form action="<?php echo URLROOT; ?>/products/add" method="POST" enctype="multipart/form-data">

                <div class="form-section">
                    <h3>Item Details</h3>

                    <label>Product Title</label>
                    <input type="text" name="title" placeholder="e.g. Casio fx-991ES Plus Calculator" required>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Price (Tk)</label>
                            <input type="number" name="price" placeholder="e.g. 500" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="Books">Books</option>
                                <option value="Stationery">Stationery</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Others">Home Rent</option>
                            </select>
                        </div>
                    </div>

                    <label>Condition</label>
                    <div class="radio-group">
                        <label><input type="radio" name="condition" value="New"> Brand New</label>
                        <label><input type="radio" name="condition" value="Used" checked> Used (Like New)</label>
                        <label><input type="radio" name="condition" value="Old"> Used (Fair)</label>
                    </div>
                </div>

                <div class="form-section">
                    <label>Description</label>
                    <textarea name="description" rows="5"
                        placeholder="Describe your item... (e.g. Used for 1 semester, minor scratches)"
                        required></textarea>
                </div>

                <div class="form-section">
                    <label>Upload Photos (Max 1)</label>

                    <div class="upload-box" id="drop-area">
                        <input type="file" name="product_image" id="file-upload" accept=".jpg, .jpeg, .png">

                        <div class="upload-content" id="upload-content">
                            <p>Drag & drop or click to upload</p>
                            <small style="color:#888;">(.jpg, .png, .jpeg only)</small>
                        </div>

                        <img id="preview-img" src="" alt="Image Preview" style="display: none; max-width: 100%; max-height: 200px; margin-top: 10px;">
                    </div>

                    <p class="error-msg" id="file-error">
                        &#9888; Invalid file format! Please upload a JPG, JPEG, or PNG image.
                    </p>
                </div>

                <button type="submit" class="submit-ad-btn">Sell Now</button>

            </form>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/sellPage.js"></script>

</body>

</html>