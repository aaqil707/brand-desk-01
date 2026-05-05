<?php
// File to store custom titles
$titlesFile = 'custom_titles.json';

// Create file if it doesn't exist
if (!file_exists($titlesFile)) {
    file_put_contents($titlesFile, json_encode(['titles' => []]));
}

// Handle CORS for local development
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

function getTitles() {
    global $titlesFile;
    $content = file_get_contents($titlesFile);
    $data = json_decode($content, true);
    return $data['titles'] ?? [];
}

function saveTitles($titles) {
    global $titlesFile;
    file_put_contents($titlesFile, json_encode(['titles' => $titles], JSON_PRETTY_PRINT));
}

switch ($action) {
    case 'get':
        echo json_encode([
            'success' => true,
            'titles' => getTitles()
        ]);
        break;

    case 'save':
        $title = $_POST['title'] ?? '';
        if (empty($title)) {
            echo json_encode([
                'success' => false,
                'message' => 'Title is required'
            ]);
            exit;
        }

        $titles = getTitles();
        if (in_array($title, $titles)) {
            echo json_encode([
                'success' => false,
                'message' => 'Title already exists'
            ]);
            exit;
        }

        $titles[] = $title;
        saveTitles($titles);
        echo json_encode(['success' => true]);
        break;

    case 'delete':
        $title = $_POST['title'] ?? '';
        if (empty($title)) {
            echo json_encode([
                'success' => false,
                'message' => 'Title is required'
            ]);
            exit;
        }

        $titles = getTitles();
        $index = array_search($title, $titles);
        if ($index !== false) {
            array_splice($titles, $index, 1);
            saveTitles($titles);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Title not found'
            ]);
        }
        break;

    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
}