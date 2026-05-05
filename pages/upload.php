<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/upload_errors.log');

$response = ['success' => false, 'message' => '', 'files' => [], 'debug' => []];

// Log server information
$response['debug']['server_info'] = [
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'],
    'script_owner' => get_current_user(),
    'script_path' => __FILE__
];

// Define upload directory with absolute path
$uploadDir = __DIR__ . '/employee_photos/';
$response['debug']['upload_dir'] = $uploadDir;

// Check directory status
$response['debug']['directory_status'] = [
    'exists' => file_exists($uploadDir),
    'is_dir' => is_dir($uploadDir),
    'is_writable' => is_writable($uploadDir),
    'permissions' => substr(sprintf('%o', fileperms($uploadDir)), -4),
    'owner' => function_exists('posix_getpwuid') ? 
        posix_getpwuid(fileowner($uploadDir))['name'] : 'N/A'
];

// Ensure directory exists with correct permissions
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        $error = error_get_last();
        error_log("Failed to create directory: " . print_r($error, true));
        $response['message'] = 'Failed to create upload directory';
        $response['debug']['mkdir_error'] = $error;
        echo json_encode($response);
        exit;
    }
}

// Set directory permissions
chmod($uploadDir, 0755);

// Verify directory is writable
if (!is_writable($uploadDir)) {
    error_log("Directory not writable: $uploadDir");
    $response['message'] = 'Upload directory is not writable';
    echo json_encode($response);
    exit;
}

// Check if files were uploaded
if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    $response['message'] = 'No files were uploaded';
    $response['debug']['files'] = $_FILES;
    echo json_encode($response);
    exit;
}

$uploadedFiles = [];
$errors = [];

// Process each uploaded file
foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
    $originalFileName = $_FILES['images']['name'][$key];
    $fileSize = $_FILES['images']['size'][$key];
    $fileType = $_FILES['images']['type'][$key];
    $fileError = $_FILES['images']['error'][$key];

    // Log file information
    error_log("Processing file: $originalFileName");
    $response['debug']['file_' . $key] = [
        'name' => $originalFileName,
        'size' => $fileSize,
        'type' => $fileType,
        'error' => $fileError,
        'tmp_name' => $tmpName
    ];

    if ($fileError === UPLOAD_ERR_NO_FILE) {
        error_log("No file uploaded for slot $key");
        continue;
    }

    // Generate unique filename
    $extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
    $cleanBaseName = preg_replace("/[^a-zA-Z0-9]/", "_", $baseName);
    $newFileName = $cleanBaseName . '_' . time() . '_' . uniqid() . '.' . $extension;
    $fullPath = $uploadDir . $newFileName;

    error_log("Attempting to move file to: $fullPath");

    // Try to move the file
    if (move_uploaded_file($tmpName, $fullPath)) {
        chmod($fullPath, 0644);
        
        // Verify file exists and is readable
        if (file_exists($fullPath) && is_readable($fullPath)) {
            $uploadedFiles[] = [
                'original_name' => $originalFileName,
                'saved_name' => $newFileName,
                'size' => $fileSize,
                'type' => $fileType,
                'path' => 'employee_photos/' . $newFileName,
                'full_path' => $fullPath,
                'permissions' => substr(sprintf('%o', fileperms($fullPath)), -4)
            ];
            error_log("Successfully uploaded: $fullPath");
        } else {
            $errors[] = "$originalFileName: File was moved but is not accessible";
            error_log("File not accessible after move: $fullPath");
        }
    } else {
        $moveError = error_get_last();
        $errors[] = "$originalFileName: Failed to save file. Error: " . ($moveError['message'] ?? 'Unknown error');
        error_log("Failed to move file: " . print_r($moveError, true));
    }
}

// Final directory check
$response['debug']['final_status'] = [
    'directory_exists' => file_exists($uploadDir),
    'is_directory' => is_dir($uploadDir),
    'is_writable' => is_writable($uploadDir),
    'permissions' => substr(sprintf('%o', fileperms($uploadDir)), -4),
    'uploaded_files' => count($uploadedFiles),
    'errors' => count($errors)
];

// List all files in directory
$response['debug']['directory_contents'] = scandir($uploadDir);

if (count($uploadedFiles) > 0) {
    $response['success'] = true;
    $response['files'] = $uploadedFiles;
    $response['message'] = 'Files uploaded successfully';
} else {
    $response['message'] = 'Upload failed: ' . implode(', ', $errors);
}

echo json_encode($response);
?>