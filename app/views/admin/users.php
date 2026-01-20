<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Users - CampusKart Admin</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminStyles.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/admin/index" class="logo">CampusKart Admin</a>
            <div class="user-actions">
                <a href="<?php echo URLROOT; ?>/admin/index" class="sell-btn">Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">

        <div class="dashboard-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div style="text-align:left;">
                <h2 style="margin:0;">User Database</h2>
                <p style="margin:5px 0 0 0;">Manage all registered students</p>
            </div>

            <div class="search-container">
                <input type="text" id="userSearch" class="admin-search-input" placeholder="Search by Student ID...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>ID & Dept</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php foreach ($data['users'] as $user): ?>
                        <tr>
                            <td>
                                <strong><?php echo $user->fullname; ?></strong><br>
                                <small style="color:#777;"><?php echo $user->gender; ?></small>
                            </td>
                            <td>
                                <span style="color:rgb(53,90,255); font-weight:bold;"><?php echo $user->student_id; ?></span><br>
                                <small><?php echo $user->department; ?></small>
                            </td>
                            <td>
                                <div>&#9993; <?php echo $user->email; ?></div>
                                <div style="margin-top:4px;">&#128222; <?php echo $user->mobile; ?></div>
                            </td>
                            <td>
                                <span class="status-badge <?php echo ($user->status == 'approved') ? 'status-approved' : 'status-pending'; ?>">
                                    <?php echo $user->status; ?>
                                </span>
                            </td>
                            <td style="display:flex; gap:10px;">
                                <a href="<?php echo URLROOT; ?>/admin/edit_user/<?php echo $user->id; ?>"
                                    class="btn-action" style="background-color: #f39c12; color:white; padding:5px 10px; border-radius:4px; text-decoration:none;">
                                    Edit
                                </a>

                                <a href="<?php echo URLROOT; ?>/admin/delete_user/<?php echo $user->id; ?>"
                                    class="btn-action btn-reject"
                                    onclick="return confirm('Are you sure? This will PERMANENTLY remove this user from the database.');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        const rootUrl = "<?php echo URLROOT; ?>";
    </script>

    <script src="<?php echo URLROOT; ?>/js/admin_users.js"></script>

</body>

</html>