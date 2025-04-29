<?php
require_once "inc/config.php";

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['listing_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$listing_id = intval($input['listing_id']);

// Count favorites
$stmt = $db->prepare("SELECT COUNT(*) AS total FROM favorites WHERE listing_id = ?");
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['success' => true, 'count' => $row['total']]);
?>
