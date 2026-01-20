<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Question - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/loginStyles.css">
</head>
<body>

    <header>
        <div class="overlay">
            <h1>CampusKart</h1>
            <h4>Security Check (Step 2/3)</h4>
        </div>
    </header>

    <section class="login-container">
        <form action="<?php echo URLROOT; ?>/users/verify_security" method="POST">
            <h3>Security Question</h3>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Answer the question you set during registration.
            </p>

            <?php if(!empty($data['error_msg'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    <?php echo $data['error_msg']; ?>
                </div>
            <?php endif; ?>

            <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">

            <label>Question: <strong>What is your favorite book?</strong></label>
            <input type="text" name="security_answer" placeholder="Enter your answer" required autocomplete="off">

            <button class="login-btn" type="submit">Verify Answer</button>

            <div class="Ending">
                <a href="<?php echo URLROOT; ?>/users/login">Cancel</a>
            </div>
        </form>
    </section>

</body>
</html>