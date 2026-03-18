<?php

$photo = $_FILES['photo'];

$photo_name = basename($photo['name']);

// Sanitize filename: remove spaces and special characters
$clean_name = preg_replace("/[^a-zA-Z0-9.\-_]/", "_", $photo_name);
// Prepend timestamp to make it unique
$unique_photo_name = time() . '_' . $clean_name;

$photo_path = 'member_photos/' . $unique_photo_name;

$allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic'];

$ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));

if (in_array($ext, $allowed_ext) && $photo['error'] === 0 && $photo['size'] < 15000000) {

    move_uploaded_file($photo['tmp_name'], $photo_path);

    echo json_encode(['success' => true, 'photo_path' => $photo_path]);
} else {
    http_response_code(400);
    if ($photo['error'] === 1) {
        echo "File exceeds PHP upload limits (upload_max_filesize).";
    } else {
        echo "Invalid file format or size too large (max 15MB).";
    }
}




