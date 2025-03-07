<?php
function handleImageUpload($param_id, &$error) {
    $imageFilename = "";

    if (!isset($_FILES['listing_image']) || $_FILES['listing_image']['error'] !== UPLOAD_ERR_OK) {
        $error = "File upload failed with error code: " . $_FILES['listing_image']['error'];
        return "";
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($_FILES['listing_image']['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        $error = "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
        return "";
    }

    $imageFilename = $param_id . "_" . basename($_FILES['listing_image']['name']);
    $targetDir = __DIR__ . "/uploads/";
    $targetFilePath = $targetDir . $imageFilename;

    // Ensure uploads directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['listing_image']['tmp_name'], $targetFilePath)) {
        $error = "Failed to upload image. Temp file: " . $_FILES['listing_image']['tmp_name'] . 
                 " Target: " . $targetFilePath;
        return "";
    }

    return $imageFilename;
}
