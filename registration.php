<?php

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom right, #eff6ff, #e0e7ff);
        }

        .signup-container {
            max-width: 400px;
            width: 100%;
            margin: 20px;
            padding: 40px 30px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            color: #111827;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #374151;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 8px;
            margin-top: 3px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin-bottom: 0;
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
        }

        .checkbox-group a {
            color: #2563eb;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            color: #1d4ed8;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #1d4ed8;
        }

        .divider {
            position: relative;
            margin: 24px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            border-top: 1px solid #d1d5db;
        }

        .divider span {
            position: relative;
            display: inline-block;
            background-color: white;
            padding: 0 8px;
            font-size: 14px;
            color: #6b7280;
            left: 50%;
            transform: translateX(-50%);
        }

        .signin-link {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .signin-link a {
            color: #2563eb;
            text-decoration: none;
        }

        .signin-link a:hover {
            color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <!-- Header -->
        <div class="header">
            <h1>Create Account</h1>
            <p>Sign up to get started</p>
        </div>

        <!-- Form -->
        <form method="POST" action="register_process.php">
            <!-- Full Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" placeholder="John Doe" name="fullname">
            </div>

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

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" placeholder="••••••••" name="confirm_password">
            </div>

            <!-- Terms and Conditions -->
            <div class="checkbox-group">
                <input type="checkbox" id="terms">
                <label for="terms">
                    I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">Sign Up</button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>OR</span>
        </div>

        <!-- Sign In Link -->
        <div class="signin-link">
            <p>Already have an account? <a href="#">Sign In</a></p>
        </div>
    </div>
</body>
</html>