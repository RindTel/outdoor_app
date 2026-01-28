<?php

require_once __DIR__ . '/User.php';

class AuthService
{
    public static function login(string $username, string $password): array
    {
        $user = User::findByUsername($username);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }

    
        $passwordValid = false;
        if (strpos($user->passwordHash, '$2y$') === 0) {
            
            $passwordValid = password_verify($password, $user->passwordHash);
        } else {
            
            $passwordValid = ($password === $user->passwordHash);
        }

        if (!$passwordValid) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }

    public static function register(string $username, string $email, string $password, string $confirmPassword): array
    {
        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{2,14}$/', $username)) {
            return ['success' => false, 'message' => 'Invalid username format'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }

        $conn = getDBConnection();

    
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => 'Username already exists'];
        }
        $stmt->close();

        
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => 'Email already exists'];
        }
        $stmt->close();
        $conn->close();

        
        $user = User::create($username, $email, $password, 'user');

        
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        return ['success' => true, 'message' => 'Registration successful', 'user' => $user];
    }
}

