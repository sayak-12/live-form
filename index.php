<?php
session_start();
include 'db.php';
if (!isset($_SESSION['man_no'])) {
   header('location: login.php');
   exit();
}
$man = $_SESSION['man_no'];
$bday = $_SESSION['dob'];
$stmt = $conn->prepare("select * from emp where `MA NO`= ? and `DOB`= ? and RELATION = 'EMP'");
$stmt->bind_param("ss", $man, $bday);
$stmt->execute();
$resultemp = $stmt->get_result()->fetch_assoc();
$stmt1 = $conn->prepare("select * from emp where `MA NO`= ? and RELATION = 'SPOUSE'");
$stmt1->bind_param("s", $man);
$stmt1->execute();
$resultspo = $stmt1->get_result()->fetch_assoc();
// Function to get the client IP address
echo 'User IP Address - ' . $_SERVER['REMOTE_ADDR'];
$ip = $_SERVER['REMOTE_ADDR'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proceed'])) {
   $_SESSION['type'] = $_POST['check'];
   $_SESSION['name_employee'] = $_POST['name_employee'] ?? null;
   $_SESSION['aadhar_no_employee'] = $_POST['aadhar_no_employee'] ?? null;
   $_SESSION['address_employee'] = $_POST['address_employee'] ?? null;
   $_SESSION['mobile_employee'] = $_POST['mobile_employee'] ?? null;
   $_SESSION['email_employee'] = $_POST['email_employee'] ?? null;
   $_SESSION['whatsapp_employee'] = $_POST['whatsapp_employee'] ?? null;
   $_SESSION['dor_employee'] = $_POST['dor_employee'] ?? null;
   $_SESSION['desig_employee'] = $_POST['desig_employee'] ?? null;
   $_SESSION['cadre_employee'] = $_POST['cadre_employee'] ?? null;
   $_SESSION['name_spouse'] = $_POST['name_spouse'] ?? null;
   $_SESSION['aadhar_no_spouse'] = $_POST['aadhar_no_spouse'] ?? null;
   $_SESSION['dob_spouse'] = $_POST['dob_spouse'] ?? null;
   $_SESSION['mobile_spouse'] = $_POST['mobile_spouse'] ?? null;
   $_SESSION['dod_employee'] = $_POST['dod_employee'] ?? null;
   $_SESSION['dod_spouse'] = $_POST['dod_spouse'] ?? null;
   $_SESSION['single_employee'] = isset($_POST['single_employee']) ? 'yes' : 'no';
   if (isset($_FILES['deathcert'])) {
      // Directory to upload the file
      $uploadDir = 'death_certificate/';

      // Ensure the directory exists
      if (!is_dir($uploadDir)) {
         mkdir($uploadDir, 0777, true);
      }

      // Generate a unique filename to prevent overwriting
      $fileName = basename($_FILES['deathcert']['name']);
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
      $uniqueFileName = $uploadDir . uniqid('death_cert_', true) . '.' . $fileExtension;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES['deathcert']['tmp_name'], $uniqueFileName)) {
         // Store the file path in the session
         $_SESSION['death_certificate'] = $uniqueFileName;
      } else {
         // Handle error if file upload fails
         echo "Failed to upload the death certificate.";
      }
   } else {
      // If no file is uploaded, set session to null or handle accordingly
      $_SESSION['death_certificate'] = null;
   }
   if (isset($_FILES['deathcert_spouse'])) {
      // Directory to upload the file
      $uploadDir = 'death_certificate/';

      // Ensure the directory exists
      if (!is_dir($uploadDir)) {
         mkdir($uploadDir, 0777, true);
      }

      // Generate a unique filename to prevent overwriting
      $fileName = basename($_FILES['deathcert_spouse']['name']);
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
      $uniqueFileName = $uploadDir . uniqid('death_cert_spouse_', true) . '.' . $fileExtension;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES['deathcert_spouse']['tmp_name'], $uniqueFileName)) {
         // Store the file path in the session
         $_SESSION['death_certificate_spouse'] = $uniqueFileName;
      } else {
         // Handle error if file upload fails
         echo "Failed to upload the death certificate.";
      }
   } else {
      // If no file is uploaded, set session to null or handle accordingly
      $_SESSION['death_certificate_spouse'] = null;
   }
   header('Location: capture.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <title>Fill Additional Details</title>
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
      <!-- Second Section: Name, Aadhar No, PAN No, and Mobile -->
      <div id="section2" class="form-section">
         <h3 class="h4 text-center">Step 2: Enter Additional Details</h3>
         <form id="section2Form" name="myform" method="post" enctype="multipart/form-data">
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
               <input type="text" class="form-control" name="name_employee" value="<?= $resultemp['BENEFICIARY NAME'] ?>" readonly required>
               <label>Aadhar No.</label>
               <input type="text" class="form-control" name="aadhar_no_employee" required>
               <label>MAN No.</label>
               <input type="text" class="form-control" name="man_no_employee" value="<?= $man ?>" required readonly>
               <label>Date of Birth</label>
               <input type="text" class="form-control" name="dob_employee" value="<?= $bday ?>" required readonly>
               <label>Date of Retirement</label>
               <input type="text" class="form-control" name="dor_employee" value="<?= $resultemp['DOR'] ?>" required readonly>
               <label>Designation</label>
               <input type="text" class="form-control" name="desig_employee" value="<?= $resultemp['Designation'] ?>" required readonly>
               <label>Category</label>
               <input type="text" class="form-control" name="cadre_employee" value="<?= $resultemp['CADRE'] ?>" required readonly>
            </div>


            <!-- Spouse Section -->
            <div class="spouse mb-3" style="display: none;">
               <hr>
               <label>Beneficiary Name (Spouse)</label>
               <input type="text" class="form-control" name="name_spouse" value="<?= $resultspo['BENEFICIARY NAME'] ?? "" ?>" readonly=<?= $resultspo['BENEFICIARY NAME'] ? true : false ?> required>
               <label>Aadhar No. (Spouse)</label>
               <input type="text" class="form-control" name="aadhar_no_spouse" required>
               <label>Date of Birth (Spouse)</label>
               <input type="text" class="form-control" name="dob_spouse" value="<?= $resultspo['DOB'] ?? "" ?>" readonly=<?= $resultspo['DOB'] ? true : false ?> required>
               <label>Mobile (Spouse)</label>
               <input type="text" class="form-control" name="mobile_spouse" value="<?= $resultspo['MOBILE NO'] ?? "" ?>" required>
            </div>


            <!-- Only Spouse Section -->
            <div class="onlyspouse mb-3" style="display: none;">
               <hr>
               <h4 class="h5 text-center">Details of Death of Employee</h4>
               <label>Name of Deceased Employee: </label>
               <input type="text" class="form-control" name="name1_employee" value="<?= $resultemp['BENEFICIARY NAME'] ?>" readonly required>
               <label>Ex MAN No.</label>
               <input type="text" class="form-control" name="man_no1_employee" value="<?= $man ?>" required readonly>
               <label>Date of Death</label>
               <input type="date" class="form-control" name="dod_employee" required>
               <label for="death" style="color:red;">Please attach the death certificate of Registered Employee:</label><br>
               <input type="file" accept=".pdf, image/*" name="deathcert" id="death" required>
            </div>

            <!-- Only employee Section -->
            <div class="onlyemployee mb-3" style="display: none;">
               <hr>
               <label>Check this if single/unmarried: </label>
               <input type="checkbox" name="single_employee" id="checker"><br>
               <div class="spdt">
                  <label>Date of Spouse Death</label>
                  <input type="date" class="form-control" name="dod_spouse" required>
                  <label for="death" style="color:red;">Please attach the death certificate of Spouse:</label><br>
                  <input type="file" accept=".pdf, image/*" name="deathcert_spouse" id="deathsp" required>
               </div>

            </div>

            <!-- Address Section -->
            <div class="mb-3 address" style="display:none;">
               <hr>
               <label>Present Address:</label><br>
               <input type="text" class="form-control" name="address_employee" value="<?= $resultemp['ADDRESS'] ?>" required readonly>
               <label>Mobile Number:</label><br>
               <input type="text" class="form-control" name="mobile_employee" value="<?= $resultemp['MOBILE NO'] ?>" required>
               <label>Email Address:</label><br>
               <input type="text" class="form-control" name="email_employee" value="<?= $resultemp['EMAIL ADDRESS'] ? $resultemp['EMAIL ADDRESS'] : "" ?>">
               <label>whatsapp Number:</label><br>
               <input type="text" class="form-control" name="whatsapp_employee">
            </div>
            <div class="w-100 text-center mb-5" aria-label="Basic example">
               <a href="login.php" class="btn btn-primary px-4">Go Back</a>
               <button type="submit" class="btn btn-success px-4" name="proceed">Confirm and Proceed</button>
            </div>
         </form>
      </div>

      <!-- <script>
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
      </script> -->
      <!-- <script language="JavaScript">
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
      </script> -->
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
            const onlyEmployeeSection = document.querySelector('.onlyemployee');
            const addressSection = document.querySelector('.address');

            // Hide all sections initially
            employeeSection.style.display = 'none';
            spouseSection.style.display = 'none';
            onlySpouseSection.style.display = 'none';
            onlyEmployeeSection.style.display = 'none';
            addressSection.style.display = 'none';

            // Show and set required attributes based on the selected option
            if (selectedOption === 'both') {
               showSection(employeeSection, true);
               showSection(spouseSection, true);
               showSection(addressSection, true);
               showSection(onlySpouseSection, false);
               showSection(onlyEmployeeSection, false);
            } else if (selectedOption === 'employee') {
               showSection(employeeSection, true);
               showSection(spouseSection, false);
               showSection(onlySpouseSection, false);
               showSection(addressSection, true);
               showSection(onlyEmployeeSection, true);
            } else if (selectedOption === 'spouse') {
               showSection(spouseSection, true);
               showSection(onlySpouseSection, true);
               showSection(onlyEmployeeSection, false);
               showSection(employeeSection, false);
               showSection(addressSection, true);
            }
         }

         function showSection(section, isRequired) {
            section.style.display = isRequired ? 'block' : 'none';
            const inputs = section.querySelectorAll('input, select');
            inputs.forEach(input => {
               // Exclude specific inputs from being required
               if (isRequired && !['email_employee', 'whatsapp_employee', 'single_employee'].includes(input.name)) {
                  input.setAttribute('required', 'required');
               } else {
                  input.removeAttribute('required');
               }
            });
         }
         document.getElementById('checker').addEventListener('change', function() {
            const spdtDiv = document.querySelector('.spdt');
            const inputs = spdtDiv.querySelectorAll('input');

            if (this.checked) {
               spdtDiv.style.display = 'none';
               inputs.forEach(input => input.removeAttribute('required'));
            } else {
               spdtDiv.style.display = 'block';
               inputs.forEach(input => input.setAttribute('required', 'required'));
            }
         });
      </script>
</body>

</html>