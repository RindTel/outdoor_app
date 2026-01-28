<?php
require_once '../config.php';
require_once __DIR__ . '/../classes/AuthService.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = isset($data['username']) ? trim($data['username']) : '';
$password = isset($data['password']) ? $data['password'] : '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
    exit;
}

$result = AuthService::login($username, $password);

echo json_encode([
    'success' => $result['success'],
    'message' => $result['message'],
    'role'    => $result['success'] && isset($result['user']) ? $result['user']->role : null
]);
