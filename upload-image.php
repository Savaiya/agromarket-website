
<?php

header('Content-Type: application/json');

$uploadDir = __DIR__ . 'assests/uploads/';

// Create the folder if it does not exist.
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!isset($_FILES['upload']) ||
    $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        'uploaded' => 0,
        'error' => ['message' => 'Image upload failed.']
    ]);
    exit;
}

$tmpFile = $_FILES['upload']['tmp_name'];

// Validate the actual image content.
$imageInfo = getimagesize($tmpFile);

if ($imageInfo === false) {
    http_response_code(400);
    echo json_encode([
        'uploaded' => 0,
        'error' => ['message' => 'Please upload a valid image.']
    ]);
    exit;
}

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp'
];

$mimeType = $imageInfo['mime'];

if (!isset($allowedTypes[$mimeType])) {
    http_response_code(400);
    echo json_encode([
        'uploaded' => 0,
        'error' => ['message' => 'Only JPG, PNG, GIF and WebP images are allowed.']
    ]);
    exit;
}

// Generate a unique filename.
$fileName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];

$destination = $uploadDir . $fileName;

if (!move_uploaded_file($tmpFile, $destination)) {
    http_response_code(500);
    echo json_encode([
        'uploaded' => 0,
        'error' => ['message' => 'Unable to save the image.']
    ]);
    exit;
}

// Return the public URL of the uploaded image.
$imageUrl = '/assets/uploads/' . $fileName;

echo json_encode([
    'uploaded' => 1,
    'fileName' => $fileName,
    'url' => $imageUrl
]);
