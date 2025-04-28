<?php
require_once "inc/config.php";
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['listing_id']) || !isset($input['favorite_action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$listing_id = intval($input['listing_id']);
$action = $input['favorite_action'];
$username = $_SESSION['username'];

if ($action === 'favorite') {
    $stmt = $db->prepare("INSERT IGNORE INTO favorites (username, listing_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $username, $listing_id);
    $stmt->execute();
} elseif ($action === 'unfavorite') {
    $stmt = $db->prepare("DELETE FROM favorites WHERE username = ? AND listing_id = ?");
    $stmt->bind_param("si", $username, $listing_id);
    $stmt->execute();
} elseif ($action === 'check') {
    $stmt = $db->prepare("SELECT 1 FROM favorites WHERE username = ? AND listing_id = ?");
    $stmt->bind_param("si", $username, $listing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $favorited = $result->num_rows > 0;
    echo json_encode(['favorited' => $favorited]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

echo json_encode(['success' => true]);
?>
