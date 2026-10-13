<?php
require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');

$functionNumber = $_GET['CKEditorFuncNum'] ?? '';
$functionNumber = is_string($functionNumber) && ctype_digit($functionNumber) ? (int) $functionNumber : 0;

function respondToCkeditorUpload(int $functionNumber, string $url = '', string $message = ''): void
{
    if (($_GET['response'] ?? '') === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        if ($url === '') {
            http_response_code(400);
        }
        echo json_encode([
            'uploaded' => $url !== '' ? 1 : 0,
            'url' => $url,
            'error' => $message !== '' ? ['message' => $message] : null,
        ], JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    if ($functionNumber < 1) {
        http_response_code(400);
        echo 'Invalid editor upload request.';
        exit;
    }

    echo '<!doctype html><html><body><script>window.parent.CKEDITOR.tools.callFunction('
        . $functionNumber . ', '
        . json_encode($url, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)
        . ', '
        . json_encode($message, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)
        . ');</script></body></html>';
    exit;
}

if (!isLoggedIn()) {
    respondToCkeditorUpload($functionNumber, '', 'Your admin session expired. Sign in and try again.');
}

$submittedToken = $_GET['token'] ?? '';
if (!is_string($submittedToken) || !hash_equals(csrfToken(), $submittedToken)) {
    respondToCkeditorUpload($functionNumber, '', 'The upload request could not be verified. Reload the editor and try again.');
}

$upload = $_FILES['upload'] ?? null;
if (!is_array($upload) || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    respondToCkeditorUpload($functionNumber, '', 'Choose an image to upload.');
}

if (($upload['size'] ?? 0) > 5 * 1024 * 1024) {
    respondToCkeditorUpload($functionNumber, '', 'Choose an image smaller than 5 MB.');
}

$imageInfo = getimagesize($upload['tmp_name']);
$mimeType = $imageInfo['mime'] ?? '';
$extensions = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
];
if (!isset($extensions[$mimeType])) {
    respondToCkeditorUpload($functionNumber, '', 'Choose a JPEG, PNG, GIF, or WebP image.');
}

$uploadDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
    respondToCkeditorUpload($functionNumber, '', 'The image upload folder could not be created.');
}

$fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
if (!move_uploaded_file($upload['tmp_name'], $uploadDirectory . DIRECTORY_SEPARATOR . $fileName)) {
    respondToCkeditorUpload($functionNumber, '', 'The image could not be saved. Please try again.');
}

respondToCkeditorUpload($functionNumber, adminUrl('../assets/uploads/' . $fileName));
