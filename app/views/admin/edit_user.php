<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - CampusKart Admin</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adminStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container { max-width: 600px; margin: 40px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .form-row { display: flex; gap: 20px; }
        .col { flex: 1; }
        .btn-update { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn-update:hover { background: #2ecc71; }
    </style>
</head>
<body>

    <header style="background-color: #5dade2; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; color: white;">
        <div style="font-size: 24px; font-weight: bold;">Edit User</div>
        <a href="<?php echo URLROOT; ?>/admin/users" style="background: white; color: #5dade2; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            <i class="fas fa-arrow-left"></i> Cancel
        </a>
    </header>

    <div class="form-container">
        <form action="<?php echo URLROOT; ?>/admin/edit_user/<?php echo $data['id']; ?>" method="POST">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo $data['fullname']; ?>" required>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
                </div>
                <div class="col form-group">
                    <label>Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo $data['mobile']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="col form-group">
                    <label>ID</label>
                    <input type="text" name="student_id" class="form-control" value="<?php echo $data['student_id']; ?>" required>
                </div>
                <div class="col form-group">
                    <label>Batch</label>
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
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="student" <?php echo ($data['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Male" <?php echo ($data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <button type="submit" class="btn-update">Update User Info</button>
        </form>
    </div>

</body>
</html>