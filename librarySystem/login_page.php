<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Community Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="images/logo.jpg" alt="Community Library Logo" class="logo">
        <h1>Member Login</h1>
    </header>

    <main>
        <div class="form-container card">
            <h2 class="form-title">Login to Your Account</h2>
            
            <?php 
                if (isset($_SESSION['error'])): 
            ?>

                <div class="alert alert-error">
                    <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php 
                        echo htmlspecialchars($_SESSION['message']); 
                        unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-block">Login</button>
            </form>

            <div class="text-center mt-20">
                <p>Don't have an account? <a href="signup.html">Sign up here</a></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Community Library. All rights reserved.</p>
    </footer>
</body>
</html>