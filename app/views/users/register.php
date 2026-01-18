<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusKart - Create Account</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/signupStyles.css">
</head>

<body>

    <header>
        <div class="overlay">
            <h1>CampusKart</h1>
            <h4>Join the Community</h4>
        </div>
    </header>

    <section class="signup-container">
        <form action="<?php echo URLROOT; ?>/users/register" method="POST">
            <h3>Sign Up</h3>

            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" 
                       value="<?php echo $data['fullname']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">University Email</label>
                <input type="email" id="email" name="email" placeholder="student@university.edu" 
                       value="<?php echo $data['email']; ?>" required>
                <span style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                    <?php echo $data['email_err']; ?>
                </span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="studentid">Student ID</label>
                    <input type="text" id="studentid" name="student_id" placeholder="XX-XXXXX-X" 
                           value="<?php echo $data['student_id']; ?>" required>
                    <span style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                        <?php echo $data['student_id_err']; ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" id="mobile" name="mobile" placeholder="01XXXXXXXXX" 
                           value="<?php echo $data['mobile']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="" disabled <?php echo empty($data['department']) ? 'selected' : ''; ?>>Select Dept</option>
                        <option value="CSE" <?php echo $data['department'] == 'CSE' ? 'selected' : ''; ?>>CSE</option>
                        <option value="EEE" <?php echo $data['department'] == 'EEE' ? 'selected' : ''; ?>>EEE</option>
                        <option value="BBA" <?php echo $data['department'] == 'BBA' ? 'selected' : ''; ?>>BBA</option>
                        <option value="Arch" <?php echo $data['department'] == 'Arch' ? 'selected' : ''; ?>>Architecture</option>
                        <option value="Law" <?php echo $data['department'] == 'Law' ? 'selected' : ''; ?>>Law</option>
                        <option value="English" <?php echo $data['department'] == 'English' ? 'selected' : ''; ?>>English</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="batch">Batch / Semester</label>
                    <input type="text" id="batch" name="batch" placeholder="e.g. 22-1" 
                           value="<?php echo $data['batch']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="gender" value="Male" 
                        <?php echo ($data['gender'] == 'Male') ? 'checked' : ''; ?> required> Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female"
                        <?php echo ($data['gender'] == 'Female') ? 'checked' : ''; ?>> Female
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                        <?php echo $data['password_err']; ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Retype" required>
                    <span style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                        <?php echo $data['confirm_password_err']; ?>
                    </span>
                </div>
            </div>

            <button class="signup-btn" type="submit">Register</button>

            <div class="Ending">
                <span>Already have an account?</span>
                <a href="<?php echo URLROOT; ?>/users/login" id="Login">Login here</a>
            </div>
        </form>
    </section>
</body>
</html>