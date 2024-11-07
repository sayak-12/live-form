<?php
// Function to get the client IP address
echo 'User IP Address - ' . $_SERVER['REMOTE_ADDR'];
$ip = $_SERVER['REMOTE_ADDR'];
// Handle form submission to process the file upload and display all form data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['imagesub'])) {
   // Check if the image file is uploaded
   if (isset($_FILES['image_file'])) {
      $image = $_FILES['image_file'];

      // Define the upload directory
      $target_dir = "capture_images/";
      $target_file = $target_dir . uniqid() . ".png";

      // Move the uploaded file to the target directory
      if (move_uploaded_file($image['tmp_name'], $target_file)) {
         echo "Image uploaded successfully. File path: " . $target_file . "<br>";
      } else {
         echo "Error: Failed to upload the image.<br>";
      }
   } else {
      echo "Error: No image file provided.<br>";
   }

   // Echo all form fields received from the POST request
   $fields = ['man_no', 'birth_date', 'name', 'aadhar_no', 'pan_no', 'mobile', 'ip'];
   foreach ($fields as $field) {
      if (isset($_POST[$field])) {
         echo ucfirst(str_replace("_", " ", $field)) . ": " . htmlspecialchars($_POST[$field]) . "<br>";
      } else {
         echo ucfirst(str_replace("_", " ", $field)) . ": Not provided<br>";
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <title>Live Photo Capture Using Webcam</title>
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

   #results {
      width: 100%;
      max-width: 350px;
      height: 250px;
   }

   #results img {
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

<style>
   /* Styles for each form section */
   .form-section {
      display: none;
   }

   .form-section.active {
      display: flex;
      flex-direction: column;
   }

   .checkflex label {
      margin: 0;
   }

   .fieldcheck {
      padding: 10px;
      box-shadow: 0px 0px 10px #0000002e;
   }
</style>

<body>
   <div class="container">
      <!-- First Section: MAN Number and Birth Date -->
      <div id="section1" class="form-section active">
         <h3 class="h4 text-center">Step 1: Enter MAN No. and Birth Date</h3>
         <form id="section1Form">
            <label>MAN No.</label>
            <input type="text" class="form-control" name="man_no" required>
            <label>Birth Date <span style="color:red;">(Format: 01-01-2000)</span></label>
            <input type="text" class="form-control" name="birth_date" required>
            <button type="button" class="btn btn-primary col-12 my-3" onclick="goToNextSection(2)">Confirm & Proceed</button>
         </form>
      </div>

      <!-- Second Section: Name, Aadhar No, PAN No, and Mobile -->
      <div id="section2" class="form-section">
   <h3 class="h4 text-center">Step 2: Enter Additional Details</h3>
   <form id="section2Form">
      <label>Life Certificate to be filled for:</label>
      <div class="checkflex d-flex mb-3" style="flex-wrap:wrap; gap:20px;">
         <div class="fieldcheck col-lg-4 col-12">
            <input type="radio" name="check" id="check1" class="mr-2" onclick="toggleSections()" value="both">
            <label for="check1">Both Employee and Spouse</label>
         </div>
         <div class="fieldcheck col-lg-3 col-12">
            <input type="radio" name="check" id="check2" class="mr-2" onclick="toggleSections()" value="employee">
            <label for="check2">Only Employee</label>
         </div>
         <div class="fieldcheck col-lg-3 col-12">
            <input type="radio" name="check" id="check3" class="mr-2" onclick="toggleSections()" value="spouse">
            <label for="check3">Only Spouse</label>
         </div>
      </div>
      
      <!-- Employee Section -->
      <div class="employee mb-3" style="display: none;">
         <label>Beneficiary Name</label>
         <input type="text" class="form-control" name="name_employee" value="Sayak Raha" readonly required>
         <label>Aadhar No.</label>
         <input type="text" class="form-control" name="aadhar_no_employee" required>
         <label>PAN No.</label>
         <input type="text" class="form-control" name="pan_no_employee" required>
         <label>Mobile</label>
         <input type="text" class="form-control" name="mobile_employee" value="9007382357" required>
      </div>

      
      <!-- Spouse Section -->
      <div class="spouse mb-3" style="display: none;">
         <label>Beneficiary Name (Spouse)</label>
         <input type="text" class="form-control" name="name_spouse" value="Sayak Raha" readonly required>
         <label>Aadhar No. (Spouse)</label>
         <input type="text" class="form-control" name="aadhar_no_spouse" required>
         <label>PAN No. (Spouse)</label>
         <input type="text" class="form-control" name="pan_no_spouse" required>
         <label>Mobile (Spouse)</label>
         <input type="text" class="form-control" name="mobile_spouse" value="9007382357" required>
      </div>


      <!-- Only Spouse Section -->
      <div class="onlyspouse mb-3" style="display: none;">
         <label for="death" style="color:red;">Please attach the death certificate of Registered Employee:</label><br>
         <input type="file" name="deathcert" id="death" required>
      </div>
      
      <button type="button" class="btn btn-primary w-100" onclick="goToNextSection(3)">Confirm and Proceed</button>
   </form>
</div>

      <!-- Third Section: Image Capture -->
      <div id="section3" class="form-section">
         <h3>Step 3: Capture Image</h3>
         <div class="container">
            <div class="row">
               <div class="col-lg-6" align="center">
                  <label>Capture live photo</label>
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
               <form id="imageForm" method="POST" class="col-12" enctype="multipart/form-data">
                  <!-- Hidden file input -->
                  <input type="hidden" class="hidman" name="man_no">
                  <input type="hidden" class="hidbdate" name="birth_date">
                  <input type="hidden" class="hidname" name="name">
                  <input type="hidden" class="hidadd" name="aadhar_no">
                  <input type="hidden" class="hidpan" name="pan_no">
                  <input type="hidden" class="hidmob" name="mobile">
                  <input type="hidden" class="hidip" name="ip" value=<?= $ip ?>>
                  <input type="file" name="image_file" id="image_file" style="display: none;" required>
                  <button type="submit" class="btn btn-success w-100" name='imagesub'>Save Picture</button>
               </form>
            </div><!--  end row -->
         </div>
      </div>

      <script>
         // Function to switch sections
         function goToNextSection(sectionNumber) {
            $('.form-section').removeClass('active');
            $('#section' + sectionNumber).addClass('active');
            updateHiddenFields();
            if (sectionNumber == 2) {

            }
         }

         // Transfer values from form fields to hidden inputs in the final form
         function updateHiddenFields() {
            document.querySelector('.hidman').value = document.querySelector('#section1Form [name="man_no"]').value;
            console.log(document.querySelector('#section1Form [name="man_no"]').value);
            document.querySelector('.hidbdate').value = document.querySelector('#section1Form [name="birth_date"]').value;
            document.querySelector('.hidname').value = document.querySelector('#section2Form [name="name"]').value;
            document.querySelector('.hidadd').value = document.querySelector('#section2Form [name="aadhar_no"]').value;
            document.querySelector('.hidpan').value = document.querySelector('#section2Form [name="pan_no"]').value;
            document.querySelector('.hidmob').value = document.querySelector('#section2Form [name="mobile"]').value;
         }
      </script>
      <script language="JavaScript">
         // Configure webcam settings
         Webcam.set({
            width: 350,
            height: 250,
            image_format: 'jpeg',
            jpeg_quality: 90
         });
         Webcam.attach('#my_camera');

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
      </script>
      <script>
         if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
         }
      </script>
      <script>
   function toggleSections() {
      // Get selected value
      const selectedOption = document.querySelector('input[name="check"]:checked').value;

      // Get sections
      const employeeSection = document.querySelector('.employee');
      const spouseSection = document.querySelector('.spouse');
      const onlySpouseSection = document.querySelector('.onlyspouse');

      // Hide all sections initially
      employeeSection.style.display = 'none';
      spouseSection.style.display = 'none';
      onlySpouseSection.style.display = 'none';

      // Show sections based on selected option
      if (selectedOption === 'both') {
         employeeSection.style.display = 'block';
         spouseSection.style.display = 'block';
      } else if (selectedOption === 'employee') {
         employeeSection.style.display = 'block';
      } else if (selectedOption === 'spouse') {
         spouseSection.style.display = 'block';
         onlySpouseSection.style.display = 'block';
      }
   }
</script>
</body>

</html>