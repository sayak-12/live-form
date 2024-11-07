<?php
session_start();
include 'db.php';
if(isset($_POST['confirm']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
    $man = $_POST['man_no'];
    $bday = $_POST['birth_date'];
    $stmt = $conn->prepare("select * from emp where `MA NO`= ? and `DOB`= ? and RELATION = 'EMP'");
    $stmt->bind_param("ss", $man, $bday);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        // Store man number and 'BENIFICIERY NAME' in the session
        $_SESSION['man_no'] = $result['MA NO'];
        $_SESSION['name'] = $result['BENEFICIARY NAME'];
        $_SESSION['dob'] = $result['DOB'];
        header("Location: index.php");
        exit();
    } else {
        // Handle the case when no matching record is found
        echo "No matching employee found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./jquery-3.7.1.js"></script>
   <script type="text/javascript" src="./webcam.js"></script>
   <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
   <script src="./bootstrap/js/bootstrap.min.js"></script>
    <title>MAN No. Verification</title>
    <style>
        * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
   }
   
    </style>
</head>
<body>
    <div class="container">
        <div id="section1" class="form-section active">
         <h3 class="h4 text-center">Step 1: Enter MAN No. and Birth Date</h3>
         <form id="section1Form" name="verification" method="post">
            <label>MAN No.</label>
            <input type="text" class="form-control" name="man_no" required value=<?= $_SESSION['man_no'] ? $_SESSION['man_no']:""?>>
            <label>Birth Date <span style="color:red;">(Format: 01-01-2000)</span></label>
            <input type="text" class="form-control" name="birth_date" required>
            <button type="submit" class="btn btn-primary col-12 my-3" name="confirm">Confirm & Proceed</button>
         </form>
      </div>
    </div>
    <!-- First Section: MAN Number and Birth Date -->
</body>
</html>