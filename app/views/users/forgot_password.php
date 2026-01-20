<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/loginStyles.css">
</head>
<body>

    <header>
        <div class="overlay">
            <h1>CampusKart</h1>
            <h4>Reset Password (Step 1/3)</h4>
        </div>
    </header>

    <section class="login-container">
        <form action="<?php echo URLROOT; ?>/users/forgot_password" method="POST">
            <h3>Find Your Account</h3>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Please enter your details to verify your identity.
            </p>
            
            <?php if(!empty($data['error_msg'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    <?php echo $data['error_msg']; ?>
                </div>
            <?php endif; ?>

            <label>Full Name</label>
            <input type="text" name="fullname" placeholder="Enter your full name" required>

            <label>Student ID</label>
            <input type="text" name="student_id" placeholder="XX-XXXXX-X" required>

            <label>Email Address</label>
            <input type="email" name="email" placeholder="student@university.edu" required>

            <button class="login-btn" type="submit">Verify Identity</button>

            <div class="Ending">
                <a href="<?php echo URLROOT; ?>/users/login" id="Forget">Back to Login</a>
            </div>
        </form>
    </section>

</body>
</html>