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
    // Initialize variables for image paths
    $target_file = null;
    $target_file1 = null;

    // Define the upload directory
    $target_dir = "capture_images/";

    // Check if the type is 'employee' or 'both' and handle the first image
    if (in_array($type, ['employee', 'both']) && isset($_FILES['image_file'])) {
        $image = $_FILES['image_file'];
        $target_file = $target_dir . uniqid() . ".png";
        move_uploaded_file($image['tmp_name'], $target_file);
        $_SESSION['image_path'] = $target_file;
    }

    // Check if the type is 'spouse' or 'both' and handle the second image
    if (in_array($type, ['spouse', 'both']) && isset($_FILES['image_file1'])) {
        $image1 = $_FILES['image_file1'];
        $target_file1 = $target_dir . uniqid() . ".png";
        move_uploaded_file($image1['tmp_name'], $target_file1);
        $_SESSION['image_path1'] = $target_file1;
    }

    // Redirect to preview.php
    header('location: preview.php');
    exit();
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

    #results,
    #results1 {
        width: 100%;
        max-width: 350px;
        height: 250px;
    }

    #results img,
    #results1 img {
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

                <!-- Conditional display for Employee -->
                <?php if ($type === 'employee' || $type === 'both') { ?>
                    <div class="row">
                        <div class="col-lg-6" align="center">
                            <label>Employee Photo</label>
                            <div id="my_camera" class="pre_capture_frame"></div>
                            <p id="camera_error" style="color: red; display: none;">Camera access denied. Please allow permission and try again.</p>
                        </div>
                        <div class="col-lg-6" align="center">
                            <label>Employee Result</label>
                            <div id="results">
                                <img class="after_capture_frame" src="placeholder.png" />
                            </div>
                        </div>
                        <input type="button" class="btn btn-info btn-round col-6 mx-auto my-3" value="Take Snapshot" onClick="take_snapshot()">
                        <input type="file" name="image_file" id="image_file" style="display: none;" <?= $type === 'employee' ? 'required' : '' ?>>
                    </div>
                <?php } ?>

                <!-- Conditional display for Spouse -->
                <?php if ($type === 'spouse' || $type === 'both') { ?>
                    <div class="row">
                        <div class="col-lg-6" align="center">
                            <label>Spouse Photo</label>
                            <div id="my_camera1" class="pre_capture_frame"></div>
                            <p id="camera_error" style="color: red; display: none;">Camera access denied. Please allow permission and try again.</p>
                        </div>
                        <div class="col-lg-6" align="center">
                            <label>Spouse Result</label>
                            <div id="results1">
                                <img class="after_capture_frame" src="placeholder.png" />
                            </div>
                        </div>
                        <br>
                    </div>
                    <br>
            </div>
            <input type="button" class="btn btn-info btn-round col-6 mx-auto my-3" style="display: block;" value="Take Snapshot" onClick="take_snapshot1()">
            <input type="file" name="image_file1" id="image_file1" style="display: none;" <?= $type === 'spouse' ? 'required' : '' ?>>
    </div>
<?php } ?>

<p class="text-danger container">Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque praesentium accusantium, nihil hic fuga blanditiis rerum est facere quod repudiandae voluptas accusamus neque nam quos quidem nobis molestias eius eum consequuntur. Corporis, architecto ullam quos iure voluptates odio officiis nobis rem. Perferendis nostrum officia veniam unde explicabo illo distinctio aliquam.</p>

<div class="w-100 text-center mb-5" aria-label="Basic example">
    <a href="index.php" class="btn btn-primary px-4">Go Back</a>
    <button type="submit" class="btn btn-success px-4" name='imagesub'>Save and Proceed</button>
</div>
</div>
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

    // Attach webcam based on type
    <?php if ($type === 'employee' || $type === 'both') { ?>
        Webcam.attach('#my_camera');
    <?php } ?>

    <?php if ($type === 'spouse' || $type === 'both') { ?>
        Webcam.attach('#my_camera1');
    <?php } ?>

    // Handle errors related to camera access
    Webcam.on('error', function(err) {
        document.getElementById('camera_error').style.display = 'block';
    });

    // Capture snapshot for employee
    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            document.getElementById('results').innerHTML = '<img class="after_capture_frame" src="' + data_uri + '"/>';
            var fileInput = document.getElementById('image_file');
            setFileInput(data_uri, fileInput);
        });
    }

    // Capture snapshot for spouse
    function take_snapshot1() {
        Webcam.snap(function(data_uri) {
            document.getElementById('results1').innerHTML = '<img class="after_capture_frame" src="' + data_uri + '"/>';
            var fileInput = document.getElementById('image_file1');
            setFileInput(data_uri, fileInput);
        });
    }

    // Function to set file input from data URI
    function setFileInput(data_uri, fileInput) {
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

        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
    }
</script>
</body>

</html>