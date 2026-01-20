<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Specific tweaks for the form to look like Registration */
        .form-container {
            max-width: 600px; /* Wider to fit everything nicely */
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .form-row {
            display: flex;
            gap: 20px;
        }
        .col {
            flex: 1;
        }
        .header-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>

    <header style="background-color: #5dade2; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; color: white;">
        <div style="font-size: 24px; font-weight: bold;">CampusKart Admin</div>
        <div>
            <a href="<?php echo URLROOT; ?>/admin/index" style="background: white; color: #5dade2; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </header>

    <div class="form-container">
        <h2 class="header-title">Create New Admin Account</h2>
        
        <form action="<?php echo URLROOT; ?>/admin/create_admin" method="POST">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo $data['fullname']; ?>" required>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" required>
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="col form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo $data['mobile']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label>Student/Admin ID</label>
                    <input type="text" name="student_id" class="form-control <?php echo (!empty($data['student_id_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['student_id']; ?>" required>
                    <span class="invalid-feedback"><?php echo $data['student_id_err']; ?></span>
                </div>
                <div class="col form-group">
                    <label>Batch (Optional)</label>
                    <input type="text" name="batch" class="form-control" value="<?php echo $data['batch']; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label>Department</label>
                    <select name="department" class="form-control">
                        <option value="CSE" <?php echo ($data['department'] == 'CSE') ? 'selected' : ''; ?>>CSE</option>
                        <option value="EEE" <?php echo ($data['department'] == 'EEE') ? 'selected' : ''; ?>>EEE</option>
                        <option value="BBA" <?php echo ($data['department'] == 'BBA') ? 'selected' : ''; ?>>BBA</option>
                        <option value="Admin Panel" <?php echo ($data['department'] == 'Admin Panel') ? 'selected' : ''; ?>>Admin Panel</option>
                    </select>
                </div>
                <div class="col form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Male" <?php echo ($data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" required>
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>
                <div class="col form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" required>
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label>Security Question (Favorite Book)</label>
                <input type="text" name="security_answer" class="form-control" placeholder="e.g. Harry Potter" value="<?php echo $data['security_answer']; ?>" required>
            </div>

            <button type="submit" class="btn-submit">Create Admin</button>
        </form>
    </div>

</body>
</html>