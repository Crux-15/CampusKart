<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/loginStyles.css">
</head>
<body>

    <header>
        <div class="overlay">
            <h1>CampusKart</h1>
            <h4>Set New Password (Step 3/3)</h4>
        </div>
    </header>

    <section class="login-container">
        <form action="<?php echo URLROOT; ?>/users/new_password" method="POST">
            <h3>Create New Password</h3>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Please create a strong password for your account.
            </p>

            <?php if(!empty($data['error_msg'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    <?php echo $data['error_msg']; ?>
                </div>
            <?php endif; ?>

            <label>New Password</label>
            <input type="password" name="password" placeholder="Min 6 characters" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Retype password" required>

            <button class="login-btn" type="submit">Update Password</button>
        </form>
    </section>

</body>
</html>