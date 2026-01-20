<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusKart - Home</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/pages/index" class="logo">CampusKart</a>

            <form action="<?php echo URLROOT; ?>/pages/index" method="GET" class="search-form">
                <div class="search-bar">
                    <input type="text" id="main-search" placeholder="Search for books, tools..." name="search">
                    <button type="submit">
                        &#128269;
                    </button>
                </div>
            </form>

            <ul class="nav-links">
                <li><a href="<?php echo URLROOT; ?>/pages/index" class="active">Home</a></li>
                <li><a href="<?php echo URLROOT; ?>/users/messages">Messages</a></li>
                <li><a href="<?php echo URLROOT; ?>/products/notifications">Notifications</a></li>
                <li><a href="#"> </a></li>
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
                        <a href="<?php echo URLROOT; ?>/users/account" class="menu-item"><span class="icon">&#128100;</span> Account</a>
                        <a href="<?php echo URLROOT; ?>/products/listings" class="menu-item"><span class="icon">&#128230;</span> My Listings</a>
                        <a href="<?php echo URLROOT; ?>/products/notifications" class="menu-item"><span class="icon">&#128276;</span> Notifications</a>
                        <a href="<?php echo URLROOT; ?>/users/messages" class="menu-item"><span class="icon">&#128172;</span> Messages</a>
                        <hr>

                        <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <a href="<?php echo URLROOT; ?>/admin/index" class="menu-item" style="color: red; font-weight: bold; background-color: #fff0f0;">
                                <span class="icon">&#128736;</span> Admin Panel
                            </a>
                            <hr>
                        <?php endif; ?>

                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout"><span class="icon">&#128682;</span> Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-container">

        <main class="feed-section">
            <div class="feed-header">
                <h2>Products</h2>
            </div>

            <div class="product-grid">
                <?php require APPROOT . '/views/products/results.php'; ?>
            </div>
        </main>

        <aside class="sidebar-section">
            <div class="filter-box">
                <div class="sidebar-header">
                    <h3>Filter Results</h3>
                </div>

                <form id="filter-form">
                    <div class="filter-group">
                        <label>Sort by Date</label>
                        <select name="date_sort" class="filter-select">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Price Range (Tk)</label>
                        <div class="price-inputs">
                            <input type="number" name="min_price" placeholder="Min">
                            <span>-</span>
                            <input type="number" name="max_price" placeholder="Max">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Category</label>
                        <div class="radio-list">
                            <label><input type="radio" name="cat" value="All" checked> All</label>
                            <label><input type="radio" name="cat" value="Books"> Books</label>
                            <label><input type="radio" name="cat" value="Stationery"> Stationery</label>
                            <label><input type="radio" name="cat" value="Electronics"> Electronics</label>
                            <label><input type="radio" name="cat" value="Accessories"> Accessories</label>
                            <label><input type="radio" name="cat" value="Furniture"> Furniture</label>
                            <label><input type="radio" name="cat" value="Others"> Home Rent</label>
                        </div>
                    </div>

                    <button type="submit" class="apply-btn">Apply Filters</button>
                    <a href="<?php echo URLROOT; ?>/pages/index" class="reset-link" id="reset-filter">Reset</a>
                </form>
            </div>
        </aside>

    </div>

    <script src="<?php echo URLROOT; ?>/js/inbox.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo URLROOT; ?>/js/search.js?v=<?php echo time(); ?>"></script>

</body>

</html>