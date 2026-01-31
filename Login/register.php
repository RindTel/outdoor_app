<?php require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="register-container">
        <h2>Register</h2>

        <form id="registerForm" class="register-form" novalidate>

            <label for="registerUsername">Username</label>
            <input type="text" id="registerUsername" required>
            <div id="registerUsernameError" class="error"></div>

            <label for="registerEmail">Email</label>
            <input type="text" id="registerEmail" required>
            <div id="registerEmailError" class="error"></div>

            <label for="registerPassword">Password</label>
            <input type="password" id="registerPassword" required>
            <div id="registerPasswordError" class="error"></div>

            <label for="registerConfirm">Confirm Password</label>
            <input type="password" id="registerConfirm" required>
            <div id="registerConfirmError" class="error"></div>

            <button type="submit">Create Account</button>
        </form>

        <p id="registerSuccess" class="success"></p>

        <p class="login-link">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </div>

    <script>
        const usernameRe = /^[a-zA-Z][a-zA-Z0-9_]{2,14}$/;
        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRe = /^.{6,}$/;

        const registerForm = document.getElementById("registerForm");
        const registerUsername = document.getElementById("registerUsername");
        const registerEmail = document.getElementById("registerEmail");
        const registerPassword = document.getElementById("registerPassword");
        const registerConfirm = document.getElementById("registerConfirm");

        const registerUsernameError = document.getElementById("registerUsernameError");
        const registerEmailError = document.getElementById("registerEmailError");
        const registerPasswordError = document.getElementById("registerPasswordError");
        const registerConfirmError = document.getElementById("registerConfirmError");
        const registerSuccess = document.getElementById("registerSuccess");

        function clearErrors() {
            registerUsernameError.textContent = "";
            registerEmailError.textContent = "";
            registerPasswordError.textContent = "";
            registerConfirmError.textContent = "";
            registerSuccess.textContent = "";
        }

        function validateRegister() {
            let valid = true;
            clearErrors();

            if (!usernameRe.test(registerUsername.value.trim())) {
                registerUsernameError.textContent =
                    "Username 2–14 karaktere (shkronja, numra, _).";
                valid = false;
            }

            if (!emailRe.test(registerEmail.value.trim())) {
                registerEmailError.textContent = "Email i pasaktë.";
                valid = false;
            }

            if (!passwordRe.test(registerPassword.value.trim())) {
                registerPasswordError.textContent =
                    "Password duhet të ketë minimum 6 karaktere.";
                valid = false;
            }

            if (registerPassword.value !== registerConfirm.value) {
                registerConfirmError.textContent = "Passwordi nuk përputhet.";
                valid = false;
            }

            return valid;
        }

        registerForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            if (validateRegister()) {
                try {
                    const response = await fetch('../api/register.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            username: registerUsername.value.trim(),
                            email: registerEmail.value.trim(),
                            password: registerPassword.value.trim(),
                            confirmPassword: registerConfirm.value.trim()
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        registerSuccess.textContent = "Regjistrimi u krye me sukses!";
                        setTimeout(() => {
                            window.location.href = "login.php";
                        }, 700);
                    } else {
                        if (data.message.includes('username') || data.message.includes('Username')) {
                            registerUsernameError.textContent = data.message;
                        } else if (data.message.includes('email') || data.message.includes('Email')) {
                            registerEmailError.textContent = data.message;
                        } else {
                            registerUsernameError.textContent = data.message;
                        }
                    }
                } catch (error) {
                    registerUsernameError.textContent = "Error: Could not connect to server.";
                    console.error('Error:', error);
                }
            }
        });

        registerUsername.addEventListener("input", () => {
            if (usernameRe.test(registerUsername.value.trim())) {
                registerUsernameError.textContent = "";
            }
        });

        registerEmail.addEventListener("input", () => {
            if (emailRe.test(registerEmail.value.trim())) {
                registerEmailError.textContent = "";
            }
        });

        registerPassword.addEventListener("input", () => {
            if (passwordRe.test(registerPassword.value.trim())) {
                registerPasswordError.textContent = "";
            }
        });

        registerConfirm.addEventListener("input", () => {
            if (registerPassword.value === registerConfirm.value) {
                registerConfirmError.textContent = "";
            }
        });
    </script>

</body>
</html>
