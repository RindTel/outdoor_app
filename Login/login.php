<?php require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <form id="loginForm" class="login-form" novalidate>
            <label for="loginUser">UserName</label>
            <input type="text" id="loginUser" name="username" required>
            <div id="loginUserError" class="error"></div>

            <label for="loginPassword">Password</label>
            <input type="password" id="loginPassword" name="password" required>
            <div id="loginPasswordError" class="error"></div>

            <button type="submit">Login</button>
        </form>

        <p id="loginSuccess" class="success"></p>

        <p class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>

    <script>
        const loginUserRe = /^[a-zA-Z][a-zA-Z0-9_]{2,14}$/;
        const loginPasswordRe = /^.{6,}$/;

        const loginForm = document.getElementById("loginForm");
        const loginUser = document.getElementById("loginUser");
        const loginPassword = document.getElementById("loginPassword");

        const loginUserError = document.getElementById("loginUserError");
        const loginPasswordError = document.getElementById("loginPasswordError");
        const loginSuccess = document.getElementById("loginSuccess");

        function cleanLoginErrors() {
            loginUserError.textContent = "";
            loginPasswordError.textContent = "";
            loginSuccess.textContent = "";
        }

        function validateLogin() {
            let valid = true;
            cleanLoginErrors();

            if (!loginUserRe.test(loginUser.value.trim())) {
                loginUserError.textContent = "Username apo passwordi i pasaktë.";
                valid = false;
            }

            if (!loginPasswordRe.test(loginPassword.value.trim())) {
                loginPasswordError.textContent = "Password duhet të ketë minimum 6 karaktere.";
                valid = false;
            }

            return valid;
        }

        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            if (validateLogin()) {
                try {
                    const response = await fetch('../api/login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            username: loginUser.value.trim(),
                            password: loginPassword.value.trim()
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        loginSuccess.textContent = "Mirë se erdhe!";
                        setTimeout(() => {
                            if (data.role === 'admin') {
                                window.location.href = "../Pages/Admin/dashboard.php";
                            } else {
                                window.location.href = "../Pages/Home/home.php";
                            }
                        }, 500);
                    } else {
                        loginUserError.textContent = data.message || "Username apo passwordi i pasaktë.";
                    }
                } catch (error) {
                    loginUserError.textContent = "Error: Could not connect to server.";
                    console.error('Error:', error);
                }
            }
        });

        loginUser.addEventListener("input", () => {
            if (loginUserRe.test(loginUser.value.trim())) {
                loginUserError.textContent = "";
            }
        });

        loginPassword.addEventListener("input", () => {
            if (loginPasswordRe.test(loginPassword.value.trim())) {
                loginPasswordError.textContent = "";
            }
        });
    </script>

</body>
</html>
