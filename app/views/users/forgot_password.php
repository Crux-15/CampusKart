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
            <h4>Reset Password</h4>
        </div>
    </header>

    <section class="login-container">
        <form action="<?php echo URLROOT; ?>/users/send_otp" method="POST">
            <h3>Find Account</h3>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Enter your university email to search for your account.
            </p>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="student@university.edu" required>

            <button class="login-btn" type="submit">Search</button>

            <div class="Ending">
                <a href="<?php echo URLROOT; ?>/users/login" id="Forget">Back to Login</a>
            </div>
        </form>
    </section>

</body>
</html>