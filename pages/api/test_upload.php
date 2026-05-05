<?php
// Create a dummy png
$im = imagecreatetruecolor(100, 100);
imagepng($im, 'dummy.png');
imagedestroy($im);

// Upload via curl
$ch = curl_init('http://localhost:8001/pages/api/generate_profile.php');
$cfile = new CURLFile('dummy.png', 'image/png', 'dummy.png');
$data = [
    'uploadMethod' => 'manual',
    'entity' => 'vdart',
    'portrait' => $cfile
];

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo "Response: $response\n";
curl_close($ch);
