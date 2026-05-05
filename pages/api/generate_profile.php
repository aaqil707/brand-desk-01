<?php
/**
 * API: Generate Profile Picture & Banner
 * Accepts portrait image upload + entity type, returns JSON with generated image paths.
 */
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

if (!extension_loaded('gd')) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'GD library is not enabled on the server.']);
    exit;
}

// Ensure directories exist
foreach (['uploads', 'output', 'employee_photos', 'banners'] as $dir) {
    if (!file_exists(__DIR__ . "/../$dir")) {
        mkdir(__DIR__ . "/../$dir", 0777, true);
    }
}

// ── Entity Configuration ──
$entityConfig = [
    'vdart' => [
        'bannerUrl' => 'https://github.com/Saranraj102000/VDart-images/blob/main/VDart_LinkedIn_Banner.png?raw=true',
        'bannerLocal' => 'banners/VDart_LinkedIn_Banner.png',
        'shape1' => 'https://github.com/Saranraj102000/VDart-images/blob/main/firsthalf.png?raw=true',
        'shape2' => 'https://github.com/Saranraj102000/VDart-images/blob/main/secondhalf.png?raw=true',
    ],
    'vdart-digital' => [
        'bannerUrl' => 'http://vdpl.co/dnimg/VDart_Digital_Staff_Banner.jpg',
        'bannerLocal' => 'banners/VDart_Digital_Staff_Banner.jpg',
        'shape1' => 'https://github.com/Saranraj102000/VDart-images/blob/main/firsthalf.png?raw=true',
        'shape2' => 'https://github.com/Saranraj102000/VDart-images/blob/main/secondhalf.png?raw=true',
    ],
    'trustpeople' => [
        'bannerUrl' => 'https://github.com/Saranraj102000/VDart-images/blob/main/Final_Trustpeople_Banner.jpg?raw=true',
        'bannerLocal' => 'banners/trustpeople_banner.jpg',
        'shape1' => 'https://github.com/Saranraj102000/VDart-images/blob/main/firsthalf.png?raw=true',
        'shape2' => 'https://github.com/Saranraj102000/VDart-images/blob/main/secondhalf.png?raw=true',
    ],
];

// ── Helper Functions ──

function getEmployeeImage($employeeId) {
    $dir = __DIR__ . "/../employee_photos/";
    foreach (['jpg', 'jpeg', 'png'] as $ext) {
        $path = $dir . $employeeId . '.' . $ext;
        if (file_exists($path)) return $path;
    }
    return false;
}

function validateImage($file) {
    if (empty($file['tmp_name'])) return false;
    $allowed = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];
    $detected = @exif_imagetype($file['tmp_name']);
    return in_array($detected, $allowed);
}

function cropAndResizeImage($sourcePath, $tw = 430, $th = 500) {
    list($sw, $sh, $type) = getimagesize($sourcePath);

    switch ($type) {
        case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($sourcePath); break;
        case IMAGETYPE_PNG:  $src = imagecreatefrompng($sourcePath);  break;
        case IMAGETYPE_GIF:  $src = imagecreatefromgif($sourcePath);  break;
        default: return false;
    }

    $sr = $sw / $sh;
    $tr = $tw / $th;

    if ($sr > $tr) {
        $cw = round($sh * $tr); $ch = $sh;
        $cx = round(($sw - $cw) / 2); $cy = 0;
    } else {
        $cw = $sw; $ch = round($sw / $tr);
        $cx = 0; $cy = round(($sh - $ch) / 2);
    }

    $cropped = imagecreatetruecolor($tw, $th);
    if ($type == IMAGETYPE_PNG) {
        imagealphablending($cropped, false);
        imagesavealpha($cropped, true);
        $t = imagecolorallocatealpha($cropped, 0, 0, 0, 127);
        imagefilledrectangle($cropped, 0, 0, $tw, $th, $t);
    }

    imagecopyresampled($cropped, $src, 0, 0, $cx, $cy, $tw, $th, $cw, $ch);

    $outPath = dirname($sourcePath) . '/cropped_' . basename($sourcePath, '.' . pathinfo($sourcePath, PATHINFO_EXTENSION)) . '.png';
    imagepng($cropped, $outPath);
    imagedestroy($src);
    imagedestroy($cropped);

    return $outPath;
}

function fetchRemoteImage($url) {
    $context = stream_context_create([
        'http' => ['ignore_errors' => true, 'timeout' => 5],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
    ]);
    $data = @file_get_contents($url, false, $context);
    return $data;
}

function combineImages($portraitPath, $outputPath, $config) {
    $croppedPath = cropAndResizeImage($portraitPath);
    if (!$croppedPath) return ['error' => 'Failed to crop portrait image.'];

    $portrait = imagecreatefrompng($croppedPath);

    // Fetch and create banner
    $bannerData = fetchRemoteImage($config['bannerUrl']);
    if (!$bannerData) {
        if (file_exists(__DIR__ . '/../' . $config['bannerLocal'])) {
            $bannerData = file_get_contents(__DIR__ . '/../' . $config['bannerLocal']);
        } else {
            return ['error' => 'Failed to download banner image from remote.'];
        }
    }
    $banner = @imagecreatefromstring($bannerData);
    if (!$banner) return ['error' => 'Invalid banner image format.'];

    // Fetch and create shapes
    $shape1Data = fetchRemoteImage($config['shape1']);
    if (!$shape1Data) return ['error' => 'Failed to download shape1 image.'];
    $shape1 = @imagecreatefromstring($shape1Data);

    $shape2Data = fetchRemoteImage($config['shape2']);
    if (!$shape2Data) return ['error' => 'Failed to download shape2 image.'];
    $shape2 = @imagecreatefromstring($shape2Data);

    if (!$shape1 || !$shape2) {
        return ['error' => 'Invalid shape image format.'];
    }

    // Save banner for download
    $bannerOutPath = __DIR__ . '/../output/banner_' . time() . '.png';
    imagepng($banner, $bannerOutPath);

    $w = 1629;
    $h = 1434;

    $final = imagecreatetruecolor($w, $h);
    imagesavealpha($final, true);
    $t = imagecolorallocatealpha($final, 0, 0, 0, 127);
    imagefill($final, 0, 0, $t);

    // Banner
    imagecopyresampled($final, $banner, 0, 0, 0, 0, $w, intval($h * 0.4), imagesx($banner), imagesy($banner));
    // Top shape
    imagecopyresampled($final, $shape1, 0, 0, 0, 0, $w, intval($h * 1.1), imagesx($shape1), imagesy($shape1));

    // Portrait
    $pw = intval($w * 0.44444);
    $ph = intval($h * 0.6);
    $px = intval(($w - $pw) / 1.4);
    $py = intval($h * 0.15);

    $tmp = imagecreatetruecolor($pw, $ph);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    $t2 = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
    imagefill($tmp, 0, 0, $t2);
    imagealphablending($tmp, true);
    imagecopyresampled($tmp, $portrait, 0, 0, 0, 0, $pw, $ph, imagesx($portrait), imagesy($portrait));
    imagecopy($final, $tmp, $px, $py, 0, 0, $pw, $ph);

    // Bottom shape
    imagecopyresampled($final, $shape2, 0, intval($h * 0.555), 0, 0, $w, intval($h * 0.599), imagesx($shape2), imagesy($shape2));

    imagepng($final, $outputPath);

    imagedestroy($portrait);
    imagedestroy($banner);
    imagedestroy($shape1);
    imagedestroy($shape2);
    imagedestroy($tmp);
    imagedestroy($final);
    @unlink($croppedPath);

    return [
        'profile' => $outputPath,
        'banner'  => $bannerOutPath,
    ];
}

// ── Main Handler ──

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$entity = $_POST['entity'] ?? 'vdart';
if (!isset($entityConfig[$entity])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid entity type']);
    exit;
}

$config = $entityConfig[$entity];
$portraitPath = null;

// Determine upload method
$uploadMethod = $_POST['uploadMethod'] ?? 'manual';

if ($uploadMethod === 'employee' && !empty($_POST['employeeId'])) {
    $portraitPath = getEmployeeImage($_POST['employeeId']);
    if (!$portraitPath) {
        echo json_encode(['success' => false, 'message' => 'No photo found for Employee ID: ' . htmlspecialchars($_POST['employeeId'])]);
        exit;
    }
} else {
    if (!isset($_FILES['portrait']) || empty($_FILES['portrait']['tmp_name'])) {
        echo json_encode(['success' => false, 'message' => 'Please select a file to upload']);
        exit;
    }
    if (!validateImage($_FILES['portrait'])) {
        echo json_encode(['success' => false, 'message' => 'Please upload a valid image file (PNG, JPEG, or GIF)']);
        exit;
    }

    $ext = pathinfo($_FILES['portrait']['name'], PATHINFO_EXTENSION);
    $uploadPath = __DIR__ . '/../uploads/' . uniqid() . '_portrait.' . $ext;
    move_uploaded_file($_FILES['portrait']['tmp_name'], $uploadPath);
    $portraitPath = $uploadPath;
}

$outputPath = __DIR__ . '/../output/combined_' . time() . '.png';
$results = combineImages($portraitPath, $outputPath, $config);

if (isset($results['error'])) {
    echo json_encode(['success' => false, 'message' => $results['error']]);
} elseif ($results && isset($results['profile']) && isset($results['banner'])) {
    // Return paths relative to /Pages/ for the frontend to access
    $profileRelative = str_replace(str_replace('\\', '/', __DIR__ . '/../'), '', str_replace('\\', '/', $results['profile']));
    $bannerRelative  = str_replace(str_replace('\\', '/', __DIR__ . '/../'), '', str_replace('\\', '/', $results['banner']));

    echo json_encode([
        'success' => true,
        'profile' => '/Pages/' . ltrim($profileRelative, '/'),
        'banner'  => '/Pages/' . ltrim($bannerRelative, '/'),
        'originalName' => $_FILES['portrait']['name'] ?? 'employee_photo',
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to generate images. Please try again.']);
}
