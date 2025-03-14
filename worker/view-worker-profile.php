<?php
require_once('../db.php');
session_start();

// Ensure Worker ID is set
$workerID = 1; // Default to 1 for testing

// Query to get worker details
$sql_get_workers_details = "SELECT User.User_ID, User.First_Name, User.Last_Name, User.City, User.Current_Rating, User.Description,
                            Worker.Worker_ID 
                            FROM User 
                            INNER JOIN Worker ON User.User_ID = Worker.Worker_ID 
                            WHERE Worker.Worker_ID = ?";
$stmt = $conn->prepare($sql_get_workers_details);
$stmt->bind_param("i", $workerID);
$stmt->execute();
$result = $stmt->get_result();

$currentRating = 0;
$description = '';
$city = '';
$fullName = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['First_Name'] . " " . $row['Last_Name'];
    $city = $row['City'];
    $currentRating = (int)$row['Current_Rating'];
    $description = $row['Description'];
}

// Generate star ratings
$ratingHtml = '';
for ($i = 0; $i < 5; $i++) {
    $ratingHtml .= ($i < $currentRating) 
        ? "<i class='fa-solid fa-star'></i>" 
        : "<i class='fa-regular fa-star'></i>";
}

// Query to get worker categories
$workerCategories = [];
$sql_statement = "SELECT Worker.Worker_ID, P.Plumber_ID, C.Carpenter_ID, E.Electrician_ID, P2.Painter_ID,
                  M.Mason_ID, J.Janitor_ID, M2.Mechanic_ID, G.Gardener_ID
                  FROM Worker
                  LEFT JOIN Plumber P ON Worker.Worker_ID = P.Plumber_ID AND P.Active = 1
                  LEFT JOIN Carpenter C ON Worker.Worker_ID = C.Carpenter_ID AND C.Active = 1
                  LEFT JOIN Electrician E ON Worker.Worker_ID = E.Electrician_ID AND E.Active = 1
                  LEFT JOIN Painter P2 ON Worker.Worker_ID = P2.Painter_ID AND P2.Active = 1
                  LEFT JOIN Mason M ON Worker.Worker_ID = M.Mason_ID AND M.Active = 1
                  LEFT JOIN Janitor J ON Worker.Worker_ID = J.Janitor_ID AND J.Active = 1
                  LEFT JOIN Mechanic M2 ON Worker.Worker_ID = M2.Mechanic_ID AND M2.Active = 1
                  LEFT JOIN Gardener G ON Worker.Worker_ID = G.Gardener_ID AND G.Active = 1
                  WHERE Worker.Worker_ID = ?";
$stmt2 = $conn->prepare($sql_statement);
$stmt2->bind_param("i", $workerID);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        if ($row2['Plumber_ID']) $workerCategories[] = "Plumber";
        if ($row2['Carpenter_ID']) $workerCategories[] = "Carpenter";
        if ($row2['Electrician_ID']) $workerCategories[] = "Electrician";
        if ($row2['Painter_ID']) $workerCategories[] = "Painter";
        if ($row2['Mason_ID']) $workerCategories[] = "Mason";
        if ($row2['Janitor_ID']) $workerCategories[] = "Janitor";
        if ($row2['Mechanic_ID']) $workerCategories[] = "Mechanic";
        if ($row2['Gardener_ID']) $workerCategories[] = "Gardener";
    }
}
?>


<!-- HTML follows... -->


<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <!-- Google fonts imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS files -->
    <link href="../styles/index-page.css" rel="stylesheet"/>
    <link href="../styles/customer/customer-bookings.css" rel="stylesheet"/>
    <link href="../styles/dashboard.css" rel="stylesheet" />
    <link href="../styles/worker/view-worker-profile.css" rel="stylesheet"/>
    <title>Worker Profile | LabourLink</title>

    <!--Fontawesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"/>

    <!-- Material Icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>
<div class="backdrop-modal" id="backdrop-modal"></div>
<div class="message-backdrop" id="message-backdrop">
</div>
<div class="create-booking-container" id="create-booking-container">
    <div class="create-booking-scroll-wrapper">
        <div class="create-booking-title">
            <h1 style="width: 100%">Create new <u>Booking</u></h1>
        </div>
        <form id="booking-create-form">
            <div class="form-input-row">
                <label for="job-type">Job type</label>
                <select id="job-type" name="job-type">
                    <?php
                       /* $sql_statement = "SELECT Worker.Worker_ID, P.Plumber_ID, C.Carpenter_ID, E.Electrician_ID, P2.Painter_ID,
        M.Mason_ID, J.Janitor_ID, M2.Mechanic_ID, Gardener_ID
                    FROM Worker LEFT JOIN Plumber P on Worker.Worker_ID = P.Plumber_ID AND P.Active = 1
                    LEFT JOIN Carpenter C on Worker.Worker_ID = C.Carpenter_ID AND C.Active = 1
                    LEFT JOIN Electrician E on Worker.Worker_ID = E.Electrician_ID AND E.Active = 1
                    LEFT JOIN Painter P2 on Worker.Worker_ID = P2.Painter_ID AND P2.Active = 1
                    LEFT JOIN Mason M on Worker.Worker_ID = M.Mason_ID AND M.Active = 1
                    LEFT JOIN Janitor J on Worker.Worker_ID = J.Janitor_ID AND J.Active = 1
                    LEFT JOIN Mechanic M2 on Worker.Worker_ID = M2.Mechanic_ID AND M2.Active = 1
                    LEFT JOIN Gardener G on Worker.Worker_ID = G.Gardener_ID AND G.Active = 1 WHERE Worker_ID = $workerID";

                        $result = $conn->query($sql_statement);

                        $workerCategories = array();

                        if($result->num_rows){
                            while($row = $result->fetch_assoc()){
                                if($row['Plumber_ID'] != null){
                                    array_push($workerCategories, "Plumber");
                                }

                                if($row['Carpenter_ID'] != null){
                                    array_push($workerCategories, "Carpenter");
                                }

                                if($row['Electrician_ID'] != null){
                                    array_push($workerCategories, "Electrician");
                                }

                                if($row['Painter_ID'] != null){
                                    array_push($workerCategories, "Painter");
                                }

                                if($row['Mason_ID'] != null){
                                    array_push($workerCategories, "Mason");
                                }

                                if($row['Janitor_ID'] != null){
                                    array_push($workerCategories, "Janitor");
                                }

                                if($row['Mechanic_ID'] != null){
                                    array_push($workerCategories, "Mechanic");
                                }

                                if($row['Gardener_ID'] != null){
                                    array_push($workerCategories, "Gardener");
                                }
                            }
                        }

                        for($i = 0; $i < count($workerCategories); $i++){
                            echo "<option value='$workerCategories[$i]'>$workerCategories[$i]</option>";
                        }
                    */?>
                </select>
            </div>
            <div class="form-input-row">
                <label for="worker-id">Worker</label>
                <select id="worker-id" name="worker-name">
                    <?php echo "<option value='$userId' selected>$fullName</option>"; ?>
                </select>
            </div>
            <div class="form-input-row">
                <label for="start-date">Start date</label>
                <input type="date" id="start-date" name="start-date"/>
            </div>
            <div class="form-time-row" id="days-complete-container">
                <label>
                    Days needed to complete
                </label>
                <div class="time-row">
                    <label>
                        <input type="radio" name="time-input" value="1" class="time-card-input"/>
                        <div class="time-card">
                            <h3>1</h3>
                            <h4>Day</h4>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="time-input" value="2" class="time-card-input"/>
                        <div class="time-card">
                            <h3>2</h3>
                            <h4>Days</h4>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="time-input" value="7" class="time-card-input" checked/>
                        <div class="time-card">
                            <h3>7</h3>
                            <h4>Days</h4>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="time-input" value="14" class="time-card-input"/>
                        <div class="time-card">
                            <h3>14</h3>
                            <h4>Days</h4>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="time-input" value="30" class="time-card-input"/>
                        <div class="time-card">
                            <h3>30</h3>
                            <h4>Days</h4>
                        </div>
                    </label>
                </div>
            </div>
            <div class="form-input-row" id="end-date-container">
                <label for="end-date">End date</label>
                <input type="date" id="end-date" name="send-date"/>
            </div>
            <div class="form-button-container">
                <button type="button" class="more-button submit-button" id="change-days-complete-button">Custom date</button>
            </div>
            <div class="form-payment-row">
                <label>Payment method</label>
                <div class="payment-methods-container">
                    <label>
                        <input type="radio" name="payment-method" class="payment-method-radio" value="Manual"/>
                        <div class="payment-method-card">
                            <img src="../assets/customer/dashboard/undraw_savings_re_eq4w.svg" alt="manual-payment"
                                 class="payment-method-image"/>
                            <h5>Manual payments</h5>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="payment-method" class="payment-method-radio" value="Online" checked/>
                        <div class="payment-method-card">
                            <img src="../assets/customer/dashboard/undraw_credit_card_re_blml.svg" alt="online-payment"
                                 class="payment-method-image"/>
                            <h5>Online payments</h5>
                        </div>
                    </label>
                </div>
            </div>
            <div class="form-button-container">
                <button type="button" class="more-button" id="booking-create-cancel-button">Cancel</button>
                <button type="submit" class="more-button submit-button" id="booking-create-submit-button">Create
                    Booking
                </button>
            </div>
        </form>
    </div>
</div>
<div class="success-message-container" id="booking-create-success">
    <h1><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Booking created successfully</h1>
</div>
<div class="failed-message-container" id="booking-create-fail">
    <div class="message-text">
        <h1><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Booking creation failed</h1>
        <h5>Your login session outdated. Please login again.</h5>
    </div>
</div>
<div class="success-message-container" id="feedback-add-success">
    <h1><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Viewing feedbacks successfully updated!</h1>
</div>
<div class="failed-message-container" id="feedback-add-fail">
    <div class="message-text">
        <h1><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Viewing feedback updation failed!</h1>
        <h5>Your login session outdated. Please login again.</h5>
    </div>
</div>
<div class="error-message-container" id="error-message-container">
    <div class="error-message-heading">
        <h1>Sorry, an unexpected error has occurred. Please try again later or contact customer support for assistance</h1>
    </div>
    <div class="error-message-image">
        <img src="../assets/error-image.png" alt="error-image" />
    </div>
</div>
<div class="add-feedback-modal" id="add-feedback-modal">
    <div class="add-feedback-modal-header">
        <h1>Add feedbacks to your profile</h1>
    </div>
    <div class="feedback-list-container" id="feedback-list-container"></div>
    <div class="add-feedback-modal-button-container">
        <button type="button" class="primary-outline-button" onclick="hideUpdateFeedbackContainer()"><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Cancel</button>
        <button type="button" id="feedback-add-button" class="disable-button"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Save</button>
    </div>
</div>
<div class="update-description-container" id="update-description-container">
    <div class="update-description-header">
        <h1>Update your description</h1>
    </div>
    <div class="update-description-comment">
        <textarea id="update-description-textarea"></textarea>
    </div>
    <div class="update-description-button-container">
        <button type="button" class="primary-outline-button" onclick="hideUpdateDescriptionContainer()"><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Cancel</button>
        <button type="button" id="save-description-button" class="disable-button" disabled><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Save</button>
    </div>
</div>
<div class="success-message-container" id="description-update-success">
    <h1><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Worker current description successfully updated!</h1>
</div>
<div class="failed-message-container" id="description-update-fail">
    <div class="message-text">
        <h1><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Worker description updation failed!</h1>
        <h5>Your login session outdated. Please login again.</h5>
    </div>
</div>
<?php include_once '../components/navbar.php' ?>
<div class="profile-container">
    <div class="profile-picture">
        <img src="../assets/profile-image/<?=$workerID?>.jpg">
    </div>
    <div class="profile-info">
        <h1 style="font-size: 50px"><?php $fullName ?></h1>
        <div class="subheading">WORKER BIO</div>
        <hr style="color: #30CEF0; width: 100%" />
        <div class="worker-bio">
            <div class="detail-row">
                <div class="detail">
                    <h3>Active worker categories</h3>
                    <div class="worker-type-badge-row">
                        <?php
                        for($i = 0; $i < sizeof($workerCategories); $i++){
                            echo "
                    <div class='worker-type-badge'>
                        $workerCategories[$i]
                    </div>
                ";
                        }
                        ?>
                    </div>
                </div>
                <div class="detail">
                    <h3>Location</h3>
                    <div class="location-container">
                        <i class='fa-solid fa-location-dot' style='color: var(--primary-color)'></i> <?php $city ?>
                    </div>
                </div>
                <div class="detail">
                    <h3>Rating</h3>
                    <?php
                    echo
                    "<div class='star-container'>
                        $ratingHtml
                        <b>$currentRating</b> 
                    </div>"
                    ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail" style="min-width: 60vw">
                    <div class="description-heading">
                        <?php
                            if($description == null || $description == ""){
                                echo "<h3>No description provided</h3>";
                            } else {
                                echo "<h3>Description</h3>";
                            }
                            /*if($currentLoggedUserId == $workerID){
                                echo "<button type='button' class='primary-outline-button' style='border: none;' onclick='showUpdateDescriptionContainer()'><i class='fa-solid fa-pencil'></i>&nbsp;&nbsp;Edit</button>";
                            }*/
                        ?>
                    </div>
                    <p id="worker-description-text">
                        <?php echo $description ?>
                    </p>
                </div>
            </div>
            <?php
                if(isset($_SESSION['user_type'] ) && $_SESSION['user_type'] == 'Customer'){
                    echo "<button type='button' class='primary-button' style='width: 200px; margin-top: 24px;' onclick='openCreateBookingModal()'>Book now!</button>";
                } else {
                    echo "<button type='button' class='disable-button' style='width: 200px;margin-top: 24px;' disabled>Book now!</button>";
                }
            ?>
        </div>
    </div>
</div>
<div class="feedbacks-container">
    <div class="subheading">CUSTOMER FEEDBACK</div>
    <hr style="color: #30CEF0;" />
    <div class="detail-row" style="justify-content: center">
        <?php
            $sql_get_choosen_feedbacks = "SELECT Feedback.Written_Feedback AS Written_Feedback FROM Worker INNER JOIN Profile_Feedback ON Worker.Worker_ID = Profile_Feedback.Worker_ID INNER JOIN Feedback ON Profile_Feedback.Feedback_Token = Feedback.Feedback_Token WHERE Worker.Worker_ID = $workerID";

            $result = $conn->query($sql_get_choosen_feedbacks);

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "
                        <div class='feedback'>
                            <div class='feedback-body'>
                                <p>$row[Written_Feedback]</p>
                            </div>           
                        </div>
                    ";
                }
            } else {
                echo "
                    <div class='feedback'>
                        <div class='feedback-body'>
                            <p>No feedbacks yet!</p>
                        </div>           
                    </div>
                ";
            }

            $sql_get_written_feedback_count = "SELECT  COUNT(Feedback_Token) AS Feedback_Count from Feedback inner join Booking on Feedback.Booking_ID = Booking.Booking_ID WHERE Booking.Worker_ID = $workerID AND Feedback.Written_Feedback != ''";

            $feedbackCount = 0;

            $result = $conn->query($sql_get_written_feedback_count);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $feedbackCount = $row['Feedback_Count'];
                }
            }

            //if($currentLoggedUserId == $workerID){
                if($feedbackCount > 0) {
                    echo "
                    <button type='button' class='add-feedback-button' onclick='displayFeedbacks()'>
                        <div class='add-feedback-icon'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </div>
                        <h1>Edit feedback</h1>
                    </button>
                ";
                } else {
                    echo "
                    <button type='button' class='add-feedback-disable-button'>
                        <div class='add-feedback-icon'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </div>
                        <h1>Edit feedback</h1>
                        <p>Currently, there are no feedbacks available to you</p>
                    </button>
                ";
                }
            
        ?>
    </div>
</div>
<footer class="footer">
    <div class="footer-row">
        <div class="footer-column">
            <img src="../assets/logo-croped.png" alt="labourlink logo" class="labour-link-logo"/>
        </div>
        <div class="footer-column">
            <h4>Learn More</h4>
            <ul>
                <li><a href="#" class="footer-more-link">About Labour Link</a></li>
                <li><a href="#" class="footer-more-link">Press Releases</a></li>
                <li><a href="#" class="footer-more-link">Environment</a></li>
                <li><a href="#" class="footer-more-link">Jobs</a></li>
                <li><a href="#" class="footer-more-link">Privacy Policy</a></li>
                <li><a href="#" class="footer-more-link">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h4>Contact Us</h4>
            <table>
                <tr>
                    <td>Mohamed Izzath:</td>
                    <td>+94 76 450 4261</td>
                </tr>
                <tr>
                    <td>Ravindu Wegiriya:</td>
                    <td>+94 71 999 9455</td>
                </tr>
                <tr>
                    <td>Dhananga Deepanjana:</td>
                    <td>+94 70 530 4401</td>
                </tr>
            </table>
        </div>
        <div class="footer-column">
            <h4>Social Media</h4>
            <p>@labourlink</p>
            <div class="social-container">
                <a href="#" class="social-link">
                    <img src="../assets/svg/socials/facebook-f.svg" alt="facebook" class="social-icon" />
                </a>
                <a href="#" class="social-link">
                    <img src="../assets/svg/socials/instagram.svg" alt="instagram" class="social-icon" />
                </a>
                <a href="#" class="social-link">
                    <img src="../assets/svg/socials/twitter.svg" alt="twitter" class="social-icon" />
                </a>
                <a href="#" class="social-link">
                    <img src="../assets/svg/socials/youtube.svg" alt="youtube" class="social-icon" />
                </a>
            </div>
        </div>
    </div>
    <div class="footer-row" style="border-top: 1px solid #FFF; padding-top: 16px;">
        <p>© 2022 Labour Link | All Rights Reserved</p>
    </div>
</footer>
<?php
echo "<script>
        let workerID = '$workerID';
    </script>";
?>
<script src="../scripts/modals.js" type="text/javascript"></script>
<script src="../scripts/index.js" type="text/javascript"></script>
<script src="../scripts/customer/create-booking.js" type="text/javascript"></script>
<script src="../scripts/worker/view-worker-profile.js" type="text/javascript"></script>
</body>
</html>