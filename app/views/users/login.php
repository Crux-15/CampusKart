<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusKart - Sign In</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/loginStyles.css">
</head>

<body>

    <header>
        <div class="overlay">
            <h1>CampusKart</h1>
            <h4>Smart Trading, Campus Living</h4>
        </div>
    </header>

    <section class="login-container">
        <form action="<?php echo URLROOT; ?>/users/login" method="POST">
            <h3>Sign In</h3>

            <?php if (!empty($data['password_err'])): ?>
                <div style="color: red; text-align: center; margin-bottom: 10px; font-weight: bold;">
                    <?php echo $data['password_err']; ?>
                </div>
            <?php endif; ?>

            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" placeholder="Enter your ID"
                value="<?php echo (!empty($data['username'])) ? $data['username'] : ''; ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <div class="options">
                <label class="remember-me">
                    <input type="checkbox" name="remember-me"> Remember Me
                </label>
            </div>

            <button class="login-btn" type="submit">Login</button>

            <div class="Ending">
                <a href="<?php echo URLROOT; ?>/users/forgot_password" id="Forget">Forgot Password?</a>
                <span> </span>
                <a href="<?php echo URLROOT; ?>/users/register" id="SignUp">Sign Up</a>
            </div>
        </form>
    </section>
</body>

</html>