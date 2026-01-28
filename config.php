<?php



define('DB_HOST', 'localhost');
define('DB_USER', 'root');      
define('DB_PASS', '');          
define('DB_NAME', 'outdoor_planner');


function getDBConnection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}


if (!function_exists('db_connect')) {
    function db_connect(): mysqli {
        return getDBConnection();
    }
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getCurrentUserId(): ?int {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

function getCurrentUsername(): ?string {
    return isset($_SESSION['username']) ? (string)$_SESSION['username'] : null;
}

function logout(): void {
    session_unset();
    session_destroy();
}
