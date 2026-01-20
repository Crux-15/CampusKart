<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending User Requests - CampusKart Admin</title>
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
        
        <div class="dashboard-header">
            <h2>New Student Requests</h2>
            <p>Verify details before approving access</p>
        </div>

        <?php if(empty($data['users'])): ?>
            
            <div class="admin-card placeholder" style="height: auto; padding: 50px;">
                <div class="empty-msg">No pending requests at the moment.</div>
                <a href="<?php echo URLROOT; ?>/admin/index" style="color: rgb(53,90,255);">Go Back</a>
            </div>

        <?php else: ?>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Student Details</th>
                            <th>Academic Info</th>
                            <th>Contact Info</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['users'] as $user): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $user->fullname; ?></strong><br>
                                    <span style="font-size: 12px; color: #666; background: #eee; padding: 2px 6px; border-radius: 4px;">
                                        <?php echo $user->gender; ?>
                                    </span>
                                </td>

                                <td>
                                    <span style="color: rgb(53,90,255); font-weight: bold;">
                                        <?php echo $user->student_id; ?>
                                    </span><br>
                                    <small><?php echo $user->department; ?> (Batch <?php echo $user->batch; ?>)</small>
                                </td>

                                <td>
                                    <div>&#9993; <?php echo $user->email; ?></div>
                                    <div style="margin-top: 4px; font-weight: 500;">
                                        &#128222; <?php echo $user->mobile; ?>
                                    </div>
                                </td>

                                <td style="color: #666; font-size: 13px;">
                                    <?php echo date('M d, Y', strtotime($user->created_at)); ?><br>
                                    <?php echo date('h:i A', strtotime($user->created_at)); ?>
                                </td>

                                <td>
                                    <a href="<?php echo URLROOT; ?>/admin/approve_user/<?php echo $user->id; ?>" 
                                       class="btn-action btn-approve"
                                       onclick="return confirm('Approve <?php echo $user->fullname; ?>?');">
                                       &#10003; Approve
                                    </a>

                                    <a href="<?php echo URLROOT; ?>/admin/delete_user/<?php echo $user->id; ?>" 
                                       class="btn-action btn-reject"
                                       onclick="return confirm('Permanently delete <?php echo $user->fullname; ?>?');">
                                       &#10005; Reject
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>