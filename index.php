<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Health Record System</title>
    <!-- Add any additional styles or link to a CSS file if needed -->
    <style>
                    body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f0f2f5;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }
            .container {
                background-color: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 400px;
                width: 100%;
            }
            input {
                width: 100%;
                padding: 10px;
                margin: 5px 0 20px 0;
                display: inline-block;
                border: 1px solid #ccc;
                box-sizing: border-box;
            }
            .logo img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 15px;
    }

    .logo h2 {
        color: #1877f2;
        font-size: 24px;
        margin: 0;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        text-align: left;
        margin-bottom: 8px;
        color: #606770;
    }

    input {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #dddfe2;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #1877f2;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }
    button:hover{
	opacity: .7;
}
.error {
   background: #F2DEDE;
   color: #A94442;
   padding: 10px;
   width: 95%;
   border-radius: 5px;
   margin: 20px auto;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="logo.png" alt="Logo - image">
            <h2>Athletic Medical Record System</h2>
        </div>
        <h2>Login</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <form action="login.php" method="post" onsubmit="return validateForm()">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="password" required>
            <p id="password-error" style="color: red;"></p>
            <div id="error-container"></div>
            <button type="submit">Login</button>
        </form>

        <script>
            function validateForm() {
                var emailInput = document.getElementById('email');
                var passwordInput = document.getElementById('password');
                var passwordError = document.getElementById('password-error');

                // Basic email format validation
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value.trim())) {
                    alert('Please enter a valid email address.');
                    return false;
                }

                if (passwordInput.value.trim() === '') {
                    passwordError.textContent = 'Password cannot be empty.';
                    return false;
                } else {
                    passwordError.textContent = ''; // Clear previous error message
                }

                return true;
            }
        </script>
    </div>
</body>
</html>
