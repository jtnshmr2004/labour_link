<?php
    session_start();
    // Check whether customer is logged in
    if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Customer') {
        header("Location: ../login.php");
    }
    $userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Google fonts imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@400;500;600&display=swap"
          rel="stylesheet">

    <!--Fontawesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"/>

    <!-- CSS files -->
    <link href="../styles/index-page.css" rel="stylesheet"/>
    <link href="../styles/dashboard.css" rel="stylesheet"/>
    <link href="../styles/customer/customer-bookings.css" rel="stylesheet"/>
    <title>Bookings | LabourLink</title>
</head>
<body>
<div class="backdrop-modal" id="backdrop-modal">
</div>
<div class="message-backdrop" id="message-backdrop">
</div>
<div class="error-message-container" id="error-message-container">
    <div class="error-message-heading">
        <h1>Sorry, an unexpected error has occurred. Please try again later or contact customer support for assistance</h1>
    </div>
    <div class="error-message-image">
        <img src="../assets/error-image.png" alt="error-image" />
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
<div class="delete-booking-container" id="delete-booking-container">
    <div id="delete-booking-content">
        <div class="delete-booking-title">
            <h1 id="delete-booking-text">Do you want to delete selected booking?</h1>
        </div>
        <div class="delete-booking-buttons" id="delete-booking-buttons">
            <button type="button" id="delete-cancel-button" onclick="closeDeleteModal()" class="delete-cancel-button">Cancel</button>
            <button type="button" id="delete-confirm-button" class="delete-confirm-button">Delete</button>
        </div>
    </div>
    <div class="loader-container" id="loader-container" style="height: 100%; width: 100%">
        <svg id="spinner" class="spinner" width="50%" height="100%" viewBox="0 0 50 50">
            <circle class="path" style="stroke: #FF5B19;" cx="25" cy="25" r="20" fill="#FFF" stroke-width="5"></circle>
        </svg>
    </div>
</div>
<div class="booking-details-container" id="booking-details-container">
    <div class="booking-details-scroll-wrapper">
        <div class="booking-details-title">
            <h1>Current Status of Your <u>Booking</u></h1>
        </div>
        <div class="status-container" id="booking-details-status-container"></div>
        <div class="details-container">
            <div class="details-row">
                <h4>Job type</h4>
                <h4 class="details-value" id="booking-details-job-type"></h4>
            </div>
            <div class="details-row">
                <h4>Worker name</h4>
                <h4 class="details-value" id="booking-details-worker-name"></h4>
            </div>
            <div class="details-row">
                <h4>Start date</h4>
                <h4 class="details-value" id="booking-details-start-date"></h4>
            </div>
            <div class="remaining-time-container" id="remaining-time-container">
                <h4>This booking will be closed in</h4>
                <h1 class="countdown-text" id="booking-details-countdown"></h1>
            </div>
            <div class="payment-method-container">
                <div class="payment-image-container">
                    <h4>Payment Method</h4>
                    <div class="payment-image-card">
                        <img class="payment-image" id="payment-image" src="../assets/customer/dashboard/undraw_credit_card_re_blml.svg"
                             alt="payment method"/>
                        <h4 id="payment-method-text">Online payments</h4>
                    </div>
                </div>
                <div class="payment-details-container" id="payment-details-container">
                    <h3>Amount that needs to be paid</h3>
                    <h2 id="payment-details-amount-text">Rs. 17500.00</h2>
                </div>
            </div>
            <div class="back-button-container" id="back-button-container">
                <button type="button" class="primary-outline-button" id="back-button"><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Close</button>
            </div>
        </div>
    </div>
</div>
<div class="success-message-container" id="booking-reject-success">
    <h1><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Booking rejected!</h1>
</div>
<div class="failed-message-container" id="booking-reject-fail">
    <div class="message-text">
        <h1><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Booking rejection process failed!</h1>
        <h5>Your login session outdated. Please login again.</h5>
    </div>
</div>
<div class="success-message-container" id="booking-accepted-success">
    <h1><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Booking successfully accepted!</h1>
</div>
<div class="failed-message-container" id="booking-accept-fail">
    <div class="message-text">
        <h1><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Booking acception process failed!</h1>
        <h5 id="booking-accept-fail-text">Your login session outdated. Please login again.</h5>
    </div>
</div>
<div class="success-message-container" id="booking-complete-success">
    <h1><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Booking successfully completed!</h1>
</div>
<div class="failed-message-container" id="booking-complete-fail">
    <div class="message-text">
        <h1><i class="fa-solid fa-xmark"></i>&nbsp;&nbsp;Booking completeion process failed!</h1>
        <h5 id="booking-accept-fail-text">Your login session outdated. Please login again.</h5>
    </div>
</div>
<div class="create-booking-container" id="create-booking-container" action="" method="POST">
    <div class="create-booking-scroll-wrapper">
        <div class="create-booking-title">
            <h1>Create new <u>Booking</u></h1>
        </div>
        <form id="booking-create-form">
            <div class="form-input-row">
                <label for="job-type">Job type</label>
                <select id="job-type" name="job-type">
                    <option value="Electrician" selected>Electrician</option>
                    <option value="Plumber">Plumber</option>
                    <option value="Painter">Painter</option>
                    <option value="Carpenter">Carpenter</option>
                    <option value="Mason">Mason</option>
                    <option value="Janitor">Janitor</option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Gardner">Gardner</option>
                </select>
            </div>
            <div class="form-input-row">
                <label for="worker-id">Worker</label>
                <select id="worker-id" name="worker-name"></select>
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
<?php include_once '../components/navbar.php' ?>
<main class="main-section">
    <section class="sidebar">
        <h1 class="sidebar-heading">Dashboard</h1>
        <div class="sidebar-items">
            <a href="./dashboard.php">
                <div class="sidebar-item">
                    <i class="fa-solid fa-server sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Overview</h4>
                </div>
            </a>
            <a href="./bookings.php">
                <div class="sidebar-item sidebar-item-selected">
                    <i class="fa-solid fa-b sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Bookings</h4>
                </div>
            </a>
            <a href="./feedbacks.php">
                <div class="sidebar-item">
                    <i class="fa-regular fa-message sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Feedbacks</h4>
                </div>
            </a>
            <a href="./housing.php">
                <div class="sidebar-item">
                    <i class="fa-solid fa-house sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Housing</h4>
                </div>
            </a>
            <a href="./payments.php">
                <div class="sidebar-item">
                    <i class="fa-regular fa-credit-card sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Payments</h4>
                </div>
            </a>
            <a href="./profile.php">
                <div class="sidebar-item">
                    <i class="fa-regular fa-circle-user sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Profile</h4>
                </div>
            </a>
        </div>
    </section>
    <section class="main-content">
        <div class="main-heading">
            <h1>All About Your <u>Bookings</u> Here!</h1>
            <h5>Logged as <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?></h5>
        </div>
        <div class="new-booking">
            <h1>Do you want to create a new booking?</h1>
            <button class="more-button" id="booking-create-button">Create Booking</button>
        </div>
        <!--Recent bookings section-->
        <div class="recent-bookings">
            <div class="recent-bookings-title">
                <h1>Recently made Bookings</h1>
            </div>
            <div class="recent-bookings-container" id="recent-booking-container">
                <?php
                    require_once('../db.php');

                    // Getting customer id from the session
                    $customer_id = $customer_id = $_SESSION['user_id'];
                    $sql_get_bookings = "SELECT Booking.*, User.First_Name, User.Last_Name 
                     FROM Booking 
                     INNER JOIN User ON Booking.Worker_ID = User.User_ID 
                     WHERE Booking.Customer_ID = $customer_id
                     ORDER BY Booking.Created_Date DESC 
                     LIMIT 5";

$stmt = $conn->prepare($sql_get_bookings);
//$stmt->bind_param("i", $customer_id); // Bind $customer_id as an integer
$stmt->execute();
$result = $stmt->get_result();

                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $bookingId = $row['Booking_ID'];
                            $worker_type = $row['Worker_Type'];
                            $worker_name = $row['First_Name'] . " " . $row['Last_Name'];
                            $start_date = date("d M Y", strtotime($row['Start_Date']));
                            $status = $row['Status'];

                            $button = '<button class="pending-button">Pending</button>';

                            if($status === 'Pending'){
                                $button = '<button class="pending-button">Pending</button>';
                            } else if($status === 'Accepted-by-worker'){
                                $button = '<button class="in-pogress-button">Accepted by worker</button>';
                            }else if($status === 'Accepted-by-customer'){
                                $button = '<button class="in-pogress-button">Accepted by customer</button>';
                            }else if($status === 'Completed'){
                                $button = '<button class="completed-button">Completed</button>';
                            } else if($status === 'Rejected-by-worker') {
                                $button = '<button class="rejected-button">Rejected by worker</button>';
                            } else if($status === 'Rejected-by-customer'){
                                $button = '<button class="rejected-button">Rejected by customer</button>';
                            }


                            echo "
                                <div class='booking-card' onclick='openBookingDetailsModal($bookingId)'>
                                    <div class='card-text'>
                                        <h3>$worker_type</h3>
                                        <p>Work by</p>
                                        <h4>$worker_name</h4>
                                    </div>
                                    <div class='booking-card-button-row'>
                                        <div class='badge-container'>
                                            <div class='blue-badge'>$start_date</div>
                                        </div>
                                        $button
                                    </div>
                                </div>";
                        }
                    }
                ?>
                <?php
    require_once('../db.php');
   

    $userId = $_SESSION['user_id'];
    $message = "";

    // Handle booking creation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['job-type'], $_REQUEST['worker-id'], $_REQUEST['start-date'], $_REQUEST['time-input'], $_REQUEST['payment-method'])) {
        // Getting Data From the Form
        $customer_id = $_SESSION['user_id'];
        $worker_type = $_REQUEST['job-type'];
        $worker_id = $_REQUEST['worker-id'];
        $starting_date = $_REQUEST['start-date'];
        $days_to_complete = $_REQUEST['time-input'];

        // Calculate ending date
        $ending_date = date('Y-m-d', strtotime($starting_date . ' +' . $days_to_complete . ' days'));

        $payment_method = $_REQUEST['payment-method'];

        // SQL Query for Inserting Booking
        $sql_create_booking = "INSERT INTO `booking` (`Customer_ID`, `Worker_ID`, `Worker_Type`, `Start_Date`, `End_Date`, `Payment_Method`) 
                               VALUES ($customer_id,$worker_id,$worker_type, $starting_date, $ending_date, $payment_method)";

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql_create_booking);
        $stmt->bind_param("iissss", $customer_id, $worker_id, $worker_type, $starting_date, $ending_date, $payment_method);

        if ($stmt->execute()) {
            $message = "Booking created successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }?>
            </div>
        </div>
        <!--Booking search container-->
        <div class="booking-search">
            <div class="booking-search-title">
                <h1>Search for bookings</h1>
                <form action="" method="POST">
                    <div class="booking-search-input-container">
                        <label for="booking-search">Search (Worker name etc)</label>
                        <div class="booking-search-input-field">
                            <input type="text" id="booking-search" class="booking-search-input" name="users-search"/>
                            <button type="button" class="search-icon-small" id="booking-search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="recent-payments-container">
            <table class="main-table">
                <thead>
                <tr class="main-tr">
                    <th class="main-th">
                        <div class="table-heading-container">Worker name&nbsp;<button class="sort-button" id="worker-name-sort"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                        </div>
                    </th>
                    <th class="main-th">
                        <div class="table-heading-container">Start date&nbsp;<button class="sort-button" id="start-date-sort"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                    </th>
                    <th class="main-th">
                        <div class="table-heading-container">End date&nbsp;<button class="sort-button" id="end-date-sort"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                    </th>
                    <th class="main-th">More actions</th>
                </tr>
                </thead>
                <tbody id="bookings-table-body">
                </tbody>
            </table>
            <div class="pagination-container">
                <button class="pagination-button" id="previous-page" onclick="previousPage()"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="pagination-button" id="previous-page-number" disabled><i class="fa-solid fa-1"></i></button>
                <button class="pagination-button-current" id="current-page-number"><i class="fa-solid fa-1"></i></button>
                <button class="pagination-button" id="next-page-number" disabled><i class="fa-solid fa-1"></i></button>
                <button class="pagination-button" id="next-page" onclick="nextPage()"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </section>
</main>
<footer class="footer">
    <div class="footer-row" style="border-top: 1px solid #FFF; padding-top: 16px;">
        <p>© 2025 Labour Link | All Rights Reserved</p>
    </div>
</footer>
<script src="../scripts/index.js" type="text/javascript"></script>
<?php
    echo "<script>
        let userId = $userId;
    </script>"
?>
<script src="../scripts/modals.js" type="text/javascript"></script>
<script src="../scripts/customer/bookings.js" type="text/javascript"></script>
<script src="../scripts/customer/create-booking-fetch-workers.js" type="text/javascript"></script>
<script src="../scripts/customer/create-booking.js" type="text/javascript"></script>
</body>
