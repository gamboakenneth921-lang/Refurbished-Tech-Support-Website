<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="header">
            <h1>Welcome Back</h1>
            <p>Sign in to continue</p>
        </div>

        <!-- Form -->
        <form method="POST" action="login_process.php">
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" placeholder="you@example.com" name="email">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="••••••••" name="password">
            </div>

            <!-- Forgot Password -->
            <div class="forgot-password">
                <a href="#">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">Sign In</button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>OR</span>
        </div>

        <!-- Sign Up Link -->
        <div class="signup-link">
            <p>Don't have an account? <a href="#">Sign Up</a></p>
        </div>
    </div>
</body>
</html>