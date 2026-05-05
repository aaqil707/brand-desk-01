<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "uploads/";
    
    // Handle file uploads
    $portraitPath = $uploadDir . basename($_FILES["portrait"]["name"]);
    $shape1Path = $uploadDir . basename($_FILES["shape1"]["name"]);
    $shape2Path = $uploadDir . basename($_FILES["shape2"]["name"]);
    
    // Move uploaded files
    move_uploaded_file($_FILES["portrait"]["tmp_name"], $portraitPath);
    move_uploaded_file($_FILES["shape1"]["tmp_name"], $shape1Path);
    move_uploaded_file($_FILES["shape2"]["tmp_name"], $shape2Path);
    
    // Combine images
    $outputPath = "output/combined_" . time() . ".png";
    if (combineImages($portraitPath, $shape1Path, $shape2Path, $outputPath)) {
        echo "Success! <a href='$outputPath'>View Result</a>";
    }
}
?>