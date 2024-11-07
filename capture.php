<?php
session_start();
include 'db.php';
if (!isset($_SESSION['man_no']) && !isset($_SESSION['type'])) {
    header('location: login.php');
    exit();
}
$man = $_SESSION['man_no'];
$bday = $_SESSION['dob'];
$type = $_SESSION['type'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['imagesub'])) {
    // Check if the image file is uploaded
    if (isset($_FILES['image_file'])) {
        $image = $_FILES['image_file'];
        $image1 = $_FILES['image_file1'];
        // Define the upload directory
        $target_dir = "capture_images/";
        $target_file = $target_dir . uniqid() . ".png";
        $target_file1 = $target_dir . uniqid() . ".png";

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $target_file) && move_uploaded_file($image1['tmp_name'], $target_file1)) {
            echo "Image uploaded successfully. File path: " . $target_file . "<br>";
            echo "Image uploaded successfully. File path: " . $target_file1 . "<br>";
        } else {
            echo "Error: Failed to upload the image.<br>";
        }
    } else {
        echo "Error: No image file provided.<br>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Capture your picture</title>
    <script src="./jquery-3.7.1.js"></script>
    <script type="text/javascript" src="./webcam.js"></script>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <script src="./bootstrap/js/bootstrap.min.js"></script>
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    #my_camera {
        width: 100% !important;
        max-width: 350px;
        aspect-ratio: 7/5 !important;
    }

    #my_camera video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    #results, #results1 {
        width: 100%;
        max-width: 350px;
        height: 250px;
    }

    #results img, #results1 img{
        width: 100%;
        max-width: 350px;
        height: 100%;
        object-fit: cover;
    }

    .butn {
        outline: none;
        border: none;
        padding: 10px 20px;
    }
</style>

<body>
    <div id="section3" class="form-section">
        <h3 class="h4 text-center">Step 3: Capture Image</h3>
        <form id="imageForm" method="POST" class="col-12" enctype="multipart/form-data">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6" align="center">
                        <label>Photo</label>
                        <div id="my_camera" class="pre_capture_frame"></div>
                        <p id="camera_error" style="color: red; display: none;">Camera access denied. Please allow permission and try again.</p>

                    </div>
                    <div class="col-lg-6" align="center">
                        <label>Result</label>
                        <div id="results">
                            <img class="after_capture_frame" src="placeholder.png" />
                        </div>
                        <br>
                    </div>
                    <input type="button" class="btn btn-info btn-round btn-file col-6 mx-auto my-3" value="Take Snapshot" onClick="take_snapshot()">

                    <input type="file" name="image_file" id="image_file" style="display: none;" required>

                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6" align="center">
                        <label>Photo</label>
                        <div id="my_camera1" class="pre_capture_frame"></div>
                        <p id="camera_error" style="color: red; display: none;">Camera access denied. Please allow permission and try again.</p>

                    </div>
                    <div class="col-lg-6" align="center">
                        <label>Result</label>
                        <div id="results1">
                            <img class="after_capture_frame" src="placeholder.png" />
                        </div>
                        <br>
                    </div>
                    <input type="button" class="btn btn-info btn-round btn-file col-6 mx-auto my-3" value="Take Snapshot" onClick="take_snapshot1()">
                        <input type="file" name="image_file1" id="image_file1" style="display: none;" required>
                        
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100" name='imagesub'>Save Picture</button>
        </form>
    </div>
    <script language="JavaScript">
        // Configure webcam settings
        Webcam.set({
            width: 350,
            height: 250,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
        Webcam.set({
            width: 350,
            height: 250,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera1');

        // Handle errors related to camera access
        Webcam.on('error', function(err) {
            document.getElementById('camera_error').style.display = 'block';
        });

        // Capture snapshot and display it in the preview
        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                // Display the captured image in the results div
                document.getElementById('results').innerHTML = '<img class="after_capture_frame" src="' + data_uri + '"/>';

                // Convert base64 data URI to Blob and set it as a file in the hidden input
                var byteString = atob(data_uri.split(',')[1]);
                var mimeString = data_uri.split(',')[0].split(':')[1].split(';')[0];
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                var blob = new Blob([ab], {
                    type: mimeString
                });
                var file = new File([blob], 'webcam_capture.png', {
                    type: mimeString
                });

                // Set the blob as the file in the hidden file input
                var fileInput = document.getElementById('image_file');
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            });
        }
        function take_snapshot1() {
            Webcam.snap(function(data_uri) {
                // Display the captured image in the results div
                document.getElementById('results1').innerHTML = '<img class="after_capture_frame" src="' + data_uri + '"/>';

                // Convert base64 data URI to Blob and set it as a file in the hidden input
                var byteString = atob(data_uri.split(',')[1]);
                var mimeString = data_uri.split(',')[0].split(':')[1].split(';')[0];
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                var blob = new Blob([ab], {
                    type: mimeString
                });
                var file = new File([blob], 'webcam_capture1.png', {
                    type: mimeString
                });

                // Set the blob as the file in the hidden file input
                var fileInput = document.getElementById('image_file1');
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            });
        }
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>