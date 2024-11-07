<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize response array
$response = array();

try {
    // Check if the 'image' data is present in the request
    if (!isset($_POST['image'])) {
        throw new Exception('No image data found in the request.');
    }

    $folderPath = 'capture_images/';
    
    // Split the base64 string
    $image_parts = explode(";base64,", $_POST['image']);
    
    // Check if the base64 string is properly formatted
    if (count($image_parts) != 2) {
        throw new Exception('Invalid image data format.');
    }

    // Extract image type and decode base64 string
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);

    // Check if the image type is supported (you can add more types if needed)
    if (!in_array($image_type, ['png', 'jpeg', 'jpg'])) {
        throw new Exception('Unsupported image type.');
    }

    // Generate unique filename and save the image
    $file = $folderPath . uniqid() . '.png';
    if (!file_put_contents($file, $image_base64)) {
        throw new Exception('Failed to save the image.');
    }

    // If everything is successful
    $response['status'] = 'success';
    $response['message'] = 'Image uploaded successfully.';
    $response['file_path'] = $file;

} catch (Exception $e) {
    // Catch and return any error messages
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Return the response in JSON format
echo json_encode($response);
?>

