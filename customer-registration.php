<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Google fonts imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!--Fontawesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"/>

    <!-- CSS files -->
    <link href="./styles/customer-registration.css" rel="stylesheet"/>
    <title>LabourLink | Customer registration</title>
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
            <img src="./assets/logo-croped.png" alt="labour-link-logo" class="labour-link-logo"/>
        </div>
        <div class="form-container">
            <h4>Welcome to Labour Link!</h4>
            <h2>Create account</h2>
            <form id="registration-form" action="customer-registration.php" method="POST">
                <div class="form-grid">
                    <div class="form-grid-column">
                        <label for="firstname">First name</label>
                        <br/>
                        <div class="input-container">
                            <input type="text" id="firstname" class="text-input" name="firstname"/>
                            <br/>
                            <span class="input-error-text" id="input-firstname-error"></span>
                        </div>
                        <label for="email">Email</label>
                        <br/>
                        <div class="input-container">
                            <input type="text" id="email" class="text-input" name="email"/>
                            <br/>
                            <span class="input-error-text" id="input-email-error"></span>
                        </div>
                    </div>
                    <div class="form-grid-column">
                        <label for="lastname">Last name</label>
                        <br/>
                        <div class="input-container">
                            <input type="text" id="lastname" class="text-input"  name="lastname"/>
                            <br/>
                            <span class="input-error-text" id="input-lastname-error"></span>
                        </div>
                        <label for="phone-number">Phone number</label>
                        <br/>
                        <div class="input-container">
                            <input type="text" id="phone-number" class="text-input"  name="phone"/>
                            <br/>
                            <span class="input-error-text" id="input-phone-error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-grid">
                    <div style="padding-left: 4px">
                        <label for="address">Address</label>
                        <br/>
                        <div class="input-container">
                            <input type="text" id="address" class="text-input" style="min-width: 440px;"  name="address"/>
                            <br/>
                            <span class="input-error-text" id="input-address-error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-grid-column">
                        <label for="password">Aadhar Card number</label>
                        <div class="input-container">
                            <input type="text" id="nic-number" class="text-input" style="height: 26px"  name="nic"/>
                            <br/>
                            <span class="input-error-text" id="input-nic-error"></span>
                        </div>
                        <label for="password">Password</label>
                        <br/>
                        <div class="input-container">
                            <input type="password" id="password" class="text-input"  name="password"/>
                            <br/>
                            <span class="input-error-text" id="input-password-error"></span>
                        </div>
                    </div>
                    <div class="form-grid-column">
                        <label for="dob">Date of birth(MM/DD/YY)</label>
                        <br/>
                        <div class="input-container">
                            <input type="date" id="dob" class="date-input" name="dob" />
                            <br/>
                            <span class="input-error-text" id="input-dob-error"></span>
                        </div>
                        <label for="confirm-password">Confirm Password</label>
                        <br/>
                        <div class="input-container">
                            <input type="password" id="confirm-password" class="text-input"/>
                            <br/>
                            <span class="input-error-text" id="input-confirm-password-error"></span>
                        </div>
                    </div>
                </div>

                <div class="remember-me-container">
                    <input type="checkbox" class="login-checkbox" id="remember-checkbox"/>
                    <span>Remember me</span>
                </div>
                <div class="term-policy-container">
                    <input type="checkbox" class="policy-checkbox" id="privacy-checkbox"/>
                    <span>I agree to all the <a href="#" class="terms-link">Terms</a> and <a href="#" class="terms-link">Privacy policy</a></span>
                    <br />
                    <span class="input-error-text" id="input-privacy-error"></span>
                </div>
                <div class="button-container">
                    <button type="button" id="back-button" class="back-button" onclick="window.location.href='index.php'">
                        Back
                    </button>
                    <input type="submit" class="register-button" id="register-button" value="Register" name="register-button" />
                </div>
            </form>
        </div>
    </section>
</main>
 <script src="./scripts/registration-input-validation.js" type="text/javascript"></script>
 <script src="./scripts/customer-registration.js" type="text/javascript"></script>
</body>
</html>

<?php
//use db
require_once 'db.php';

//insert data from form
if(isset($_POST['firstname'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $nic = $_POST['nic'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
  
    $sql1 = "INSERT INTO User (First_Name, Last_Name, Email, User_Address, Contact_No, NIC, Pswd, DOB, Type) VALUES ('$firstname', '$lastname', '$email', '$address', '$phone', '$nic', '$password', '$dob', 'Customer')";
    $result1 = mysqli_query($conn, $sql1);

    $sql2="SELECT User_ID FROM User WHERE Email='$email'";
    $result2 = mysqli_query($conn, $sql2);
    $row = mysqli_fetch_assoc($result2);
    $user_id = $row['User_ID'];
    
    $sql3 = "INSERT INTO Customer (Customer_ID) VALUES ('$user_id')";
    $result3 = mysqli_query($conn, $sql3);

    if($result1 && $result3){
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
        

    }else{
        echo '<script>showFailMessage("We are having trouble with registration process. Please try again later.");</script>';
    }
}

?>  