<?php
    include_once('./db.php');

    session_start();

    $validWorkers = array('plumber', 'carpenter', 'electrician', 'painter', 'mason', 'janitor', 'mechanical', 'gardner');
   if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['workertype']) || !in_array($_GET['workertype'], $validWorkers)) {
            header("Location: ../index.php");
        }
    }
   

    $workerType = $_GET['workertype'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!--Fontawesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="styles/worker-registration.css">
    <title>LabourLink | Labour registration</title>
</head>
<body>
<div class="backdrop-modal" id="backdrop-modal">
</div>
<div class="success-message-container" id="register-success">
    <h1><i class="fa-solid fa-check"></i></h1>
    <div class="success-message-text">
        <h1>Registration Successful</h1>
        <h3>Thank you for registering. Please check your email for a confirmation. You will be redirect to the <u>home page</u> shortly</h3>
    </div>
</div>
<div class="failed-message-container" id="register-failed">
    <h1><i class="fa-solid fa-xmark"></i></h1>
    <div class="failed-message-text">
        <h1>Registration Failed</h1>
        <h3 id="error-text"></h3>
    </div>
</div>
<main class="main-container">
    <section class="left-panel"></section>
    <section class="right-panel">
        <div class="logo-container">
            <img src="./assets/logo-croped1.png" alt="labour-link-logo" class="labour-link-logo"/>
        </div>
        <div class="form-container">
            <h4>Welcome to Labour Link!</h4>
            <h2>Create account</h2>
            <form id="registration-form" action="" method="POST">
                <div class="form-grid">
                    <div class="form-grid-column">
                        <label for="firstname">First name</label><br>
                        <div class="input-container">
                            <input type="text" id="firstname" class="text-input" name="firstname"><br>
                            <span class="input-error-text" id="input-firstname-error"></span>
                        </div>
                        <label for="email">Email</label><br>
                        <div class="input-container">
                            <input type="text" id="email" class="text-input"  name="email"><br>
                            <span class="input-error-text" id="input-email-error"></span>
                        </div>
                    </div>
                    <div class="form-grid-column">
                        <label for="lastname">Last name</label><br>
                        <div class="input-container">
                            <input type="text" id="lastname" class="text-input"  name="lastname"><br>
                            <span class="input-error-text" id="input-lastname-error"></span>
                        </div>
                        <label for="phone number">Phone number</label><br>
                        <div class="input-container">
                            <input type="text" id="phone-number" class="text-input"  name="phone"><br>
                            <span class="input-error-text" id="input-phone-error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-grid">
                    <div style="padding-left: 4px">
                        <label for="address">Address</label><br>
                        <div class="input-container">
                            <input type="text" id="address" class="text-input" style="min-width: 440px;"  name="address"><br>
                            <span class="input-error-text" id="input-address-error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-grid-column">
                        <label for="nic-number">Aadhar Card number</label>
                        <div class="input-container">
                            <input type="text" id="nic-number" class="text-input" style="height: 26px"  name="nic">
                            <span class="input-error-text" id="input-nic-error"></span>
                        </div>
                        <label for="password">Password</label><br>
                        <div class="input-container">
                            <input type="password" id="password" class="text-input"  name="password"><br>
                            <span class="input-error-text" id="input-password-error"></span>
                        </div>
                        <label for="validate-identity">Upload front image of Aadhar Card</label><br>
                        <div class="input-container">
                            <input type="file" class="upload-box" id="validate-identity-front"  name="nic-front">
                        </div>
                        <label for="validate-identity">Letter from Garama sewaka</label><br>
                        <div class="input-container">
                            <input type="file" class="upload-box" id="validate-letter"  name="letter-gs">
                        </div>
                    </div>
                    <div class="form-grid-column">
                        <label for="dob">Date of birth(MM/DD/YY)</label><br>
                        <div class="input-container">
                            <input type="date" id="dob" class="date-input"  name="dob">
                            <span class="input-error-text" id="input-dob-error"></span>
                        </div>
                        <label for="confirm-password">Confirm Password</label><br>
                        <div class="input-container">
                            <input type="password" id="confirm-password" class="text-input"><br>
                            <span class="input-error-text" id="input-cofirm-password-error"></span>
                        </div>

                        <label for="validate-identity">Upload back image of Aadhar Card</label><br>
                        <div class="input-container">
                            <input type="file" class="upload-box" id="validate-identity-back"  name="nic-back">
                        </div>
                        <label for="city">City</label><br>
                        <div class="input-container">
                            <input type="text" id="city" class="text-input"  name="city"><br>
                            <span class="input-error-text" id="input-city-error"></span>
                        </div>
                    </div>
                </div>

                <div class="remember-me-container">
                    <input type="checkbox" class="login-checkbox">
                    <span>Remember me</span>
                </div>
                <div class="term-policy-container">
                    <input type="checkbox" class="policy-checkbox">
                    <span>I agree to all the <a href="#" class="terms-link">Terms</a> and <a href="#"
                                                                                             class="terms-link">Privacy Policy</a></span>
                </div>

                <div class="button-container">
                    <button type="button" class="back-button" id="back-button" onclick="window.location.href='index.php'">
                        Back
                    </button>
                    <input type="submit" class="reg-button" id="register-button" value="Register" name="register-button" />
                </div>
            </form>
        </div>
    </section>
</main>
<script src="./scripts/worker-registration.js" type="text/javascript"></script>
<script src="./scripts/index.js" type="text/javascript"></script>
</body>
</html>

<?php
//db connection
require_once 'db.php';

//get data from form
if(isset($_POST['register-button'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $nic = $_POST['nic'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $city = $_POST['city'];
    $nic_front = $_POST['nic-front'];
    $nic_back = $_POST['nic-back'];
    $letter_gs = $_POST['letter-gs'];

    //insert data to db
    $sql1 = "INSERT INTO User (First_Name, Last_Name, Email, User_Address, Contact_No, NIC, Pswd, DOB, Type) VALUES ('$firstname', '$lastname', '$email', '$address', '$phone', '$nic', '$password', '$dob', 'Worker')";
    $result1 = mysqli_query($conn, $sql1);
    $sql2 = "SELECT User_ID FROM User WHERE Email = '$email'";
    $result2 = mysqli_query($conn, $sql2);
    $row = mysqli_fetch_assoc($result2);
    $user_id = $row['User_ID'];
    $sql3 = "INSERT INTO Worker (Worker_ID, City) VALUES ('$user_id', '$city')";
    $result3 = mysqli_query($conn, $sql3);
    $sql4 = null;
    $result4 = null;

    if($workerType === 'plumber'){
        $sql4 = "INSERT INTO Plumber (Plumber_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    } else if($workerType === 'carpenter'){
        $sql4 = "INSERT INTO Carpenter (Carpenter_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    } else if($workerType === 'electrician'){
        $sql4 = "INSERT INTO Electrician (Electrician_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    } else if($workerType === 'mason'){
        $sql4 = "INSERT INTO Mason (Mason_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    }else if($workerType === 'painter'){
        $sql4 = "INSERT INTO Painter (Painter_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    } else if($workerType === 'gardener'){
        $sql4 = "INSERT INTO Gardener (Gardener_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    } else if($workerType === 'mechanical'){
        $sql4 = "INSERT INTO Mechanic (Mechanic_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    } else if($workerType === 'janitor'){
        $sql4 = "INSERT INTO Janitor (Janitor_ID) VALUES ('$user_id')";
        $result4 = mysqli_query($conn, $sql4);
    }
    if($result1 && $result3 && $result4){
        echo("Successfully Registered");
        //send email
        require_once 'mailconfiguration.php';

        $mail->addAddress($email, $firstname);
        $mail->isHTML(true);
        $mail->Subject = 'Registration Confirmation';
        $mail->Body = "Dear $firstname $lastname,<br><br>Thank you for registering with us.<br><br>Regards,<br>Team Labour Link";
        if ($mail->send()) {
            echo '<script>showSuccessMessage();</script>';
        } else {
            echo '<script>showFailMessage("We are having trouble with sending email. Please check you\'r email address.");</script>';
        }
        //header("Location: ./login.php");
    }else{
        echo '<script>showFailMessage("We are having trouble with registration process. Please try again later.");</script>';
    }
}
?>