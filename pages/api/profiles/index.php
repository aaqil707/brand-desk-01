<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $files = glob('*.json');
    $profiles = [];

    if ($files === false) {
        throw new Exception('Failed to access profiles directory');
    }

    foreach ($files as $file) {
        $content = file_get_contents($file);
        if ($content === false) continue;

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        
        if (isset($data['id']) && isset($data['name'])) {
            $profiles[] = [
                'value' => $data['id'],
                'label' => $data['name']
            ];
        }
    }

    echo json_encode($profiles);
} catch (JsonException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'JSON decoding error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
