<?php
session_start();
include 'db.php';

if (!isset($_SESSION['man_no']) && !isset($_SESSION['type'])) {
    header('location: login.php');
    exit();
}
$man = $_SESSION['man_no'] ?? 'N/A';
$bday = $_SESSION['dob'] ?? 'N/A';
$type = $_SESSION['type'] ?? 'N/A';
$name_employee = $_SESSION['name_employee'] ?? 'N/A';
$aadhar_no_employee = $_SESSION['aadhar_no_employee'] ?? 'N/A';
$address_employee = $_SESSION['address_employee'] ?? 'N/A';
$mobile_employee = $_SESSION['mobile_employee'] ?? 'N/A';
$email_employee = $_SESSION['email_employee'] ?? 'N/A';
$whatsapp_employee = $_SESSION['whatsapp_employee'] ?? 'N/A';
$dor_employee = $_SESSION['dor_employee'] ?? 'N/A';
$desig_employee = $_SESSION['desig_employee'] ?? 'N/A';
$cadre_employee = $_SESSION['cadre_employee'] ?? 'N/A';
$name_spouse = $_SESSION['name_spouse'] ?? 'N/A';
$aadhar_no_spouse = $_SESSION['aadhar_no_spouse'] ?? 'N/A';
$dob_spouse = $_SESSION['dob_spouse'] ?? 'N/A';
$mobile_spouse = $_SESSION['mobile_spouse'] ?? 'N/A';
$dod_employee = $_SESSION['dod_employee'] ?? 'N/A';
$death_certificate = $_SESSION['death_certificate'] ?? 'N/A';
$imagepath = $_SESSION['image_path'] ?? '';
$imagepath1 = $_SESSION['image_path1'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $sql = "INSERT INTO application (
        name_employee, aadhar_employee, man_no, dob_employee, dor, designation, category,
        name_spouse, aadhar_spouse, dob_spouse, mob_spouse, address, mobile, email, whatsapp,
        type, pic_employee, pic_spouse, dod_employee, death_certificate
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssssssssssss",
        $name_employee,
        $aadhar_no_employee,
        $man,
        $bday,
        $dor_employee,
        $desig_employee,
        $cadre_employee,
        $name_spouse,
        $aadhar_no_spouse,
        $dob_spouse,
        $mobile_spouse,
        $address_employee,
        $mobile_employee,
        $email_employee,
        $whatsapp_employee,
        $type,
        $imagepath,
        $imagepath1,
        $dod_employee,
        $death_certificate
    );

    if ($stmt->execute()) {
        
        // Redirect to preview page
        session_unset();
        session_destroy();
        echo "<script>
                alert('Thank you! Your application has been submitted successfully.');
                window.location.href = 'login.php';
              </script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
function isDisabled($value)
{
    return ($value === "N/A") ? "disabled" : "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Details</title>
    <script src="./jquery-3.7.1.js"></script>
    <script type="text/javascript" src="./webcam.js"></script>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="printJS/jspdf.umd.min.js"></script>
    <script src="printJS/html2canvas.min.js"></script>

</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Preview Live Form</h2>
        <form method="post" enctype="multipart/form-data" id="printJS-form">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="managerNumber" class="form-label">Employee Number</label>
                    <input type="text" class="form-control" id="managerNumber" value="<?= $man ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="dob" class="form-label">Employee Date of Birth</label>
                    <input type="text" class="form-control" id="dob" value="<?= $bday ?>" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Application Type</label>
                <input type="text" class="form-control" id="type" value="<?= $type ?>" readonly>
            </div>
            <div style="display: <?= ($type == 'employee' || $type == 'both') ? "block" : "none" ?>">
                <hr>
                <h4 class="mt-4">Employee Details</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" id="employeeName" value="<?= $name_employee ?>" <?= isDisabled($name_employee) ?> readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="aadhar" class="form-label">Employee Aadhar</label>
                        <input type="text" class="form-control" id="aadhar" value="<?= $aadhar_no_employee ?>" <?= isDisabled($aadhar_no_employee) ?>>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Employee Address</label>
                    <input type="text" class="form-control" id="address" value="<?= $address_employee ?>" <?= isDisabled($address_employee) ?> readonly>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="address" class="form-label">Employee Cadre</label>
                        <input type="text" class="form-control" id="address" value="<?= $cadre_employee ?>" <?= isDisabled($cadre_employee) ?> readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="mobile" class="form-label">Employee Mobile</label>
                        <input type="text" class="form-control" id="mobile" value="<?= $mobile_employee ?>" <?= isDisabled($mobile_employee) ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Employee Email</label>
                        <input type="email" class="form-control" id="email" value="<?= $email_employee ?>" <?= isDisabled($email_employee) ?>>
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp" class="form-label">Employee WhatsApp</label>
                        <input type="text" class="form-control" id="whatsapp" value="<?= $whatsapp_employee ?>" <?= isDisabled($whatsapp_employee) ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dor" class="form-label">Date of Retirement (DOR)</label>
                        <input type="text" class="form-control" id="dor" value="<?= $dor_employee ?>" <?= isDisabled($dor_employee) ?> readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" class="form-control" id="designation" value="<?= $desig_employee ?>" <?= isDisabled($desig_employee) ?> readonly>
                    </div>
                </div>

                


            </div>

            <div style="display: <?= ($type == 'spouse' || $type == 'both') ? "block" : "none" ?>">
                <hr>
                <h4 class="mt-4">Spouse Details</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="spouseName" class="form-label">Spouse Name</label>
                        <input type="text" class="form-control" id="spouseName" value="<?= $name_spouse ?>" <?= isDisabled($name_spouse) ?> readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="spouseAadhar" class="form-label">Spouse Aadhar</label>
                        <input type="text" class="form-control" id="spouseAadhar" value="<?= $aadhar_no_spouse ?>" <?= isDisabled($aadhar_no_spouse) ?>>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="spouseDob" class="form-label">Spouse DOB</label>
                        <input type="text" class="form-control" id="spouseDob" value="<?= $dob_spouse ?>" <?= isDisabled($dob_spouse) ?> readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="spouseMobile" class="form-label">Spouse Mobile</label>
                        <input type="text" class="form-control" id="spouseMobile" value="<?= $mobile_spouse ?>" <?= isDisabled($mobile_spouse) ?>>
                    </div>
                </div>
            </div>
            <div style="display: <?= ($type == 'spouse') ? "block" : "none" ?>">
                <hr>
                <h4 class="mt-4">Employee Death Details</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dod" class="form-label">Date of Death (DOD)</label>
                        <input type="text" class="form-control" id="dod" value="<?= $dod_employee ?>" <?= isDisabled($dod_employee) ?>>
                    </div>
                    <div class="col-md-6">
                        <label for="deathCert" class="form-label">Death Certificate</label>
                        <?php if (!empty($death_certificate)) { ?>
                            <a href="<?= htmlspecialchars($death_certificate) ?>" class="btn btn-primary d-flex" style="width:max-content;" download target="_blank">Download Death Certificate</a>
                        <?php } else { ?>
                            <p class="text-muted">No certificate available</p>
                        <?php } ?>
                    </div>
                </div>

            </div>

            <h4 class="mt-4">Uploaded Images</h4>
            <div class="row mb-3">
                <?php if ($imagepath && ($type=='employee' || $type=='both')): ?>
                    <div class="col-md-6  d-flex flex-column">
                        <label class="form-label">Employee Picture</label>
                        <img src="<?= $imagepath ?>" alt="Uploaded Image 1" class="img-thumbnail me-2" style="max-width: 200px; aspect-ratio:7/5; object-fit:cover;">
                    </div>
                <?php endif; ?>
                <?php if ($imagepath1 && ($type=='spouse' || $type=='both')): ?>
                    <div class="col-md-6 d-flex flex-column">
                        <label class="form-label">Spouse Picture</label>
                        <img src="<?= $imagepath1 ?>" alt="Uploaded Image 2" class="img-thumbnail me-2" style="max-width: 200px; aspect-ratio:7/5; object-fit:cover;">
                    </div>
                <?php endif; ?>
            </div>
            <div class="w-100 text-center mb-4" aria-label="Basic example">
                <a href="capture.php" class="btn btn-primary px-4 exclude-from-pdf">Go Back</a>
                <button type="button" class="btn btn-success px-4 exclude-from-pdf" id="download-pdf">Download as PDF</button>
                <button type="submit" name="submit" class="btn btn-success px-4 exclude-from-pdf">Confirm & Submit</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('download-pdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;

    // Capture the form element
    const formElement = document.getElementById('printJS-form');

    // Temporarily hide buttons and other unwanted elements
    document.querySelectorAll('.exclude-from-pdf').forEach(el => el.style.display = 'none');

    // Add a heading before generating the PDF
    const heading = document.createElement('h2');
    heading.textContent = 'Preview Live Form';
    heading.style.textAlign = 'center';
    heading.style.marginBottom = '20px';
    formElement.insertBefore(heading, formElement.firstChild);

    // Define padding and quality
    const padding = 20; // Increase padding for better readability
    const pdfQuality = 1.0; // Set quality to high (1.0)

    // Capture the form with html2canvas
    html2canvas(formElement, {
        scale: 3, // Higher scale for better quality
        useCORS: true,
        allowTaint: true,
        scrollY: -window.scrollY,
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', pdfQuality);
        const pdf = new jsPDF('p', 'mm', 'a4');

        // Calculate dimensions with margins
        const pageWidth = pdf.internal.pageSize.getWidth() - 2 * padding;
        const pageHeight = pdf.internal.pageSize.getHeight() - 2 * padding;
        const canvasWidth = canvas.width;
        const canvasHeight = canvas.height;

        // Determine optimal scale to fit content within PDF page
        const scale = Math.min(pageWidth / canvasWidth, pageHeight / canvasHeight);

        const imgWidth = canvasWidth * scale;
        const imgHeight = canvasHeight * scale;

        // Add the image with calculated scaling and padding
        pdf.addImage(imgData, 'JPEG', padding, padding, imgWidth, imgHeight);

        // Restore hidden elements and remove temporary heading
        document.querySelectorAll('.exclude-from-pdf').forEach(el => el.style.display = '');
        heading.remove();

        // Save the optimized PDF
        pdf.save('Employee_Spouse_Details.pdf');
    });
});

    </script>
</body>

</html>