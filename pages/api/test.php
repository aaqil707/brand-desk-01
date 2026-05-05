<?php
echo "allow_url_fopen: " . ini_get('allow_url_fopen') . "\n";
$res = file_get_contents('https://github.com/Saranraj102000/VDart-images/blob/main/VDart_LinkedIn_Banner.png?raw=true');
echo "file_get_contents: " . ($res !== false ? "success" : "failed") . "\n";
