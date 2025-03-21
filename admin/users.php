<?php
session_start();
//if not logged in redirect to login page
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Admin') {
    header("Location: admin-login.php");
}
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
    <link href="../styles/admin/users.css" rel="stylesheet"/>
    <title>Users | Admin Dashboard</title>
</head>
<body>
<div class="backdrop-modal" id="backdrop-modal">
</div>
<div class="backdrop-modal" id="admin-backdrop-modal">
</div>
<div class="reset-login-content" id="reset-login-content">
    <div class="reset-login-title">
        <h1>Do you want to reset selected login?</h1>
    </div>
    <div class="reset-login-buttons">
        <button type="button" onclick="closeResetModal()" class="reset-cancel-button">Cancel</button>
        <button type="button" onclick="resetLogin()" class="reset-confirm-button">Confirm</button>
    </div>
</div>
<div class="suspend-user-content" id="suspend-user-content">
    <div class="suspend-user-title">
        <h1 id="suspend-user-text">Do you want to suspend the selected user?</h1>
    </div>
    <div class="suspend-user-buttons">
        <button type="button" id="suspend-cancel-button" onclick="closeSuspendModal()" class="suspend-cancel-button">Cancel</button>
        <button type="button" id="suspend-confirm-button" class="suspend-confirm-button">Confirm</button>
    </div>
</div>
<div class="success-message-container" id="register-success">
    <h1><i class="fa-solid fa-check"></i></h1>
    <div class="success-message-text">
        <h1>Admin Account Created!</h1>
    </div>
</div>
<div class="failed-message-container" id="register-failed">
    <h1><i class="fa-solid fa-xmark"></i></h1>
    <div class="failed-message-text">
        <h1>Account Creation Failed</h1>
        <h3 id="error-text"></h3>
    </div>
</div>
<div class="create-admin-form" id="create-admin-form">
    <div class="create-admin-wrapper">
        <div class="create-admin-title">
            <h1>Create new <u>Admin</u></h1>
        </div>
        <form id="admin-create-form" action="" method="POST">
            <div class="admin-form-container">
                <div class="admin-form-row">
                    <div class="admin-form-column">
                        <label for="first-name">First name</label>
                        <br/>
                        <input type="text" id="first-name" name="first-name"/>
                        <span class="input-error-text" id="input-first-name-error"></span>
                    </div>
                    <div class="admin-form-column">
                        <label for="last-name">Last name</label>
                        <br/>
                        <input type="text" id="last-name" name="last-name"/>
                        <span class="input-error-text" id="input-last-name-error"></span>
                    </div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-form-column">
                        <label for="email">Email</label>
                        <br/>
                        <input type="text" id="email" name="email"/>
                        <span class="input-error-text" id="input-email-error"></span>
                    </div>
                    <div class="admin-form-column">
                        <label for="phone-number">Phone number</label>
                        <br/>
                        <input type="text" id="phone-number" name="phone-number"/>
                        <span class="input-error-text" id="input-phone-number-error"></span>
                    </div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-form-column">
                        <label for="address">Address</label>
                        <br/>
                        <input type="text" id="address" name="address"/>
                        <span class="input-error-text" id="input-address-error"></span>
                    </div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-form-column">
                        <label for="nic-number">NIC Number</label>
                        <br/>
                        <input type="text" id="nic-number" name="nic-number"/>
                        <span class="input-error-text" id="input-nic-number-error"></span>
                    </div>
                    <div class="admin-form-column">
                        <label for="phone-number">Date of birth(MM/DD/YYYY)</label>
                        <br/>
                        <input type="date" id="dob" name="dob"/>
                        <span class="input-error-text" id="input-dob-error"></span>
                    </div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-form-column">
                        <label for="initial-password">Initial password</label>
                        <br/>
                        <input type="password" id="initial-password" name="initial-password"/>
                        <span class="input-error-text" id="input-initial-password-error"></span>
                    </div>
                    <div class="admin-form-column">
                        <label for="confirm-password">Confirm password</label>
                        <br/>
                        <input type="password" id="confirm-password" name="confirm-password"/>
                        <span class="input-error-text" id="input-confirm-password-error"></span>
                    </div>
                </div>
                <div class="form-message">
                    <h5>Note that: We will prompt to change password in initial login</h5>
                </div>
                <div class="button-container">
                    <button type="button" class="cancel-button" id="admin-create-cancel-button">Cancel</button>
                    <input type="submit" class="submit-button" id="admin-create-submit-button" name="admin-create-submit-button" value="Create Admin"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include_once '../components/navbar.php' ?>
<span style="display:none" id="reset-user-id"></span>
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
                <div class="sidebar-item">
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
            <a href="./users.php">
                <div class="sidebar-item  sidebar-item-selected">
                    <i class="fa-solid fa-user-check sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Users</h4>
                </div>
            </a>
            <a href="./reports.php">
                <div class="sidebar-item">
                    <i class="fa-regular fa-newspaper sidebar-item-icon"></i>
                    <h4 class="sidebar-icon-heading">Reports</h4>
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
        <!--<div class="loader-container" id="loader-container">
            <svg id="spinner" class="spinner" width="50%" height="50%" viewBox="0 0 50 50">
                <circle class="path" cx="25" cy="25" r="20" fill="#ABC3EF" stroke-width="5"></circle>
            </svg>
        </div>-->
        <div class="main-content-container" id="main-content-container">
        <div class="main-heading">
            <h1>Control panel for managing <u>Users</u></h1>
            <h5>Logged as <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?></h5>
        </div>
        <div class="recent-logins">
            <div class="recent-logins-title">
                <h1>Recent login attemps</h1>
                <div class="login-search-container">
                    <label for="login-search" class="login-search-text">Search(Using username, date etc)</label>
                    <br/>
                    <form action="" method="POST">
                        <div class="search-input-container">
                            <input type="text" id="login-search" class="login-search" name="search"/>
                            <button class="search-icon-small"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="recent-logins-container">
            <table class="main-table">
                <thead>
                <tr class="main-tr">
                    <th class="main-th">
                        <div class="table-heading-date">Username/Status&nbsp;<button class="sort-button"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                        </div>
                    </th>
                    <th class="main-th">
                        <div class="table-heading-date">Date&nbsp;<button class="sort-button"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                    </th>
                    <th class="main-th">
                        <div class="table-heading-date">Time&nbsp;<button class="sort-button"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                    </th>
                    <th class="main-th">More actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $activationFlag=1;
                $Activation_Flag=1;
                $curr_user_id = $_SESSION['user_id'];
                require_once '../db.php';
                if (!isset($_POST['search'])) {
                    $search = "";
                } else {
                    $search = $_POST['search'];
                }
                if ($search == "") {
                    $sql = "SELECT User.User_ID, First_Name, Last_Name, date(Current_Timestamp), time(Current_Timestamp), Success_Flag,Activation_Flag FROM Login_Attempt INNER JOIN User ON Login_Attempt.User_ID=User.User_ID ORDER BY Current_Timestamp DESC LIMIT 5";
                } else {
                    $sql = "SELECT User.User_ID, First_Name, Last_Name, date(Current_Timestamp), time(Current_Timestamp), Success_Flag,Activation_Flag  FROM Login_Attempt INNER JOIN User ON Login_Attempt.User_ID=User.User_ID WHERE User.User_ID !=1 AND (First_Name LIKE 'ravi' OR Last_Name LIKE 'ravi') ORDER BY Current_Timestamp DESC LIMIT 5";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['User_ID'];
                        if($user_id == $curr_user_id){
                            echo('
                        <tr class="main-tr">
                            <td class="main-td" style="text-align: left;">' . $row['First_Name'] . ' ' . $row['Last_Name'] . ' (You)'.'
                                
                                <br/>' .
                            //if login success
                            ($row['Success_Flag'] == 1 ? '<span class="success-badge">Success</span>' : '<span class="failed-badge">Failed</span>')

                            . '</td>
                            <td class="main-td">' . date("d M Y", strtotime($row['date(Current_Timestamp)'])) . '</td>
                            <td class="main-td">' . $row['time(Current_Timestamp)'] . '</td>
                            <td class="main-td">
                                <div class="more-button-container">
                                    <button class="view-button"><i class="fa-solid fa-up-right-from-square"></i>&nbsp;&nbsp;View
                                    </button>&nbsp;' .

                            ($row['Activation_Flag'] == 1 ? 
                            //if the user is the current user, disable the suspend button
                                    
                                    '<button class="disable-button" onclick="openSuspendModal(' . $user_id.','. $curr_user_id . ', true)" disabled><i class="fa-solid fa-user-xmark"></i>&nbsp;&nbsp;Suspend</button>' :
                                    '<button class="disable-button" onclick="openSuspendModal(' . $user_id . ', false)"><i class="fa-solid fa-user-check" disabled></i>&nbsp;&nbsp;Activate</button>') .
                            '</div>
                            </td>
                        </tr>');
                        }else{
                            echo('
                        <tr class="main-tr">
                            <td class="main-td" style="text-align: left;">' . $row['First_Name'] . ' ' . $row['Last_Name'] . '
                                
                                <br/>' .
                            //if login success
                            ($row['Success_Flag'] == 1 ? '<span class="success-badge">Success</span>' : '<span class="failed-badge">Failed</span>')

                            . '</td>
                            <td class="main-td">' . date("d M Y", strtotime($row['date(Current_Timestamp)'])) . '</td>
                            <td class="main-td">' . $row['time(Current_Timestamp)'] . '</td>
                            <td class="main-td">
                                <div class="more-button-container">
                                    <button class="view-button"><i class="fa-solid fa-up-right-from-square"></i>&nbsp;&nbsp;View
                                    </button>&nbsp;' .

                            ($row['Activation_Flag'] == 1 ? 
                            //if the user is the current user, disable the suspend button
                                    
                                    '<button class="suspend-button" onclick="openSuspendModal(' . $user_id.','. $curr_user_id . ', true)"><i class="fa-solid fa-user-xmark"></i>&nbsp;&nbsp;Suspend</button>' : 
                                    '<button class="activate-button" onclick="openSuspendModal(' . $user_id . ', false)"><i class="fa-solid fa-user-check"></i>&nbsp;&nbsp;Activate</button>') .
                            '</div>
                            </td>
                        </tr>');
                        }
                        
                    }
                }
                
                ?>

                </tbody>
            </table>
            <div class="pagination-container">
                <button class="pagination-button"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="pagination-button"><i class="fa-solid fa-1"></i></button>
                <button class="pagination-button-current"><i class="fa-solid fa-2"></i></button>
                <button class="pagination-button"><i class="fa-solid fa-3"></i></button>
                <button class="pagination-button"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
        <div class="search-users">
            <div class="search-users-title">
                <h1>Search for users of the system</h1>
                <div class="search-users-container">
                    <label for="users-search" class="users-search-text">Search(Using username, role etc)</label>
                    <br/>
                    <form action="" method="POST">
                        <div class="search-input-container">
                            <input type="text" id="search-users-input" class="users-search" name="users-search"/>
                            <button class="search-icon-small"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="recent-payments-container">
            <table class="main-table">
                <thead>
                <tr class="main-tr">
                    <th class="main-th">
                        <div class="table-heading-date">Username/Status&nbsp;<button class="sort-button"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                        </div>
                    </th>
                    <th class="main-th">
                        <div class="table-heading-date">Recent login&nbsp;<button class="sort-button"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                    </th>
                    <th class="main-th">
                        <div class="table-heading-date">Role&nbsp;<button class="sort-button"><i
                                        class="fa-solid fa-arrow-up"></i></button>
                    </th>
                    <th class="main-th">More actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                require_once '../db.php';
                if (!isset($_POST['users-search'])) {
                    $search = "";
                } else {
                    $search = $_POST['users-search'];
                }
                if ($search == "") {
                    $sql = "SELECT * FROM User";
                } else {
                    $sql = "SELECT * FROM User WHERE First_Name LIKE '%$search%'";
                }

                $result = $conn->query($sql);
                $rowCount = 0;

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $sql2 = "SELECT date(Current_Timestamp) FROM Login_Attempt WHERE User_ID=" . $row['User_ID'] . " ORDER BY Current_Timestamp DESC LIMIT 1;";
                        $result2 = $conn->query($sql2);
                        $row2 = $result2->fetch_assoc();
                        $last_login = "";
                        if(isset($row2['date(Current_Timestamp)'])) {
                            $last_login = $row2['date(Current_Timestamp)'];
                        }
                        if ($last_login == "") {
                            $last_login = "Never";
                        } else {
                            //format date with month in words
                            $last_login = date("d M Y", strtotime($last_login));
                        }

                        $user_id = $row['User_ID'];
                        $rowCount = $rowCount + 1;
                        $username = $row['First_Name'] . ' ' . $row['Last_Name'];
                        $badgeHTML = '';

                        if($row['Activation_Flag'] == 1) {
                            $badgeHTML = '<span class="success-badge">Activated account</span>';
                        } else {
                            $badgeHTML = '<span class="suspend-badge">Suspended account</span>';
                        }
                
                        echo('<tr class="main-tr" id="users-table-row-'. $rowCount . '">
                                <td class="main-td" style="text-align: left;">'
                                . "<span id='users-table-username-$rowCount'>$username</span>"
                                . "<br />"
                                . $badgeHTML
                                ."</td>
                                <td class='main-td' id='users-table-login-$rowCount'>$last_login</td>
                                <td class='main-td' id='users-table-login-$rowCount'>" . $row['Type'] . '</td>
                                <td class="main-td">
                                    <div class="more-button-container">
                                        <button class="view-button"><i class="fa-solid fa-up-right-from-square"></i>&nbsp;&nbsp;View
                                        </button>
                                        <button id="suspend-user-button" class="reset-login-button" onclick="openResetModal(' . $user_id . ')"><i class="fa-solid fa-gear"></i>&nbsp;&nbsp;Reset login
                                        </button>
                                    </div>
                                </td>
                            </tr>');
                    }
                   // echo '<script src="../scripts/admin/loader.js" type="text/javascript"></script>';
                   /* echo "<script>
                            let rowCount = $rowCount;
                            let totalPages = Math.ceil(rowCount / 5);
                            closeLoader();
                        </script>";
                }
*/
                ?>


                </tbody>
            </table>
            <div class="pagination-container">
                <button class="pagination-button" id="previous-users-page" onclick="goToPreviousUsersPage()"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="pagination-button" id="previous-users-page-number"><i class="fa-solid fa-1"></i></button>
                <button class="pagination-button-current" id="current-users-page-number"><i class="fa-solid fa-2"></i></button>
                <button class="pagination-button" id="next-users-page-number"><i class="fa-solid fa-3"></i></button>
                <button class="pagination-button" id="next-users-page" onclick="goToNextUsersPage()"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
        <div class="create-admin">
            <h1>Do you want to add new <u>Admin</u></h1>
            <button type="button" class="more-button" id="create-admin-button">Create Admin</button>
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
<script src="../scripts/modals.js" type="text/javascript"></script>
<script src="../scripts/admin/admin-create-validation.js" type="text/javascript"></script>
<script src="../scripts/admin/users.js" type="text/javascript"></script>
</body>


</html>


<?php

    include_once '../db.php';
    if(isset($_POST['first-name'])){
        $first_name = $_POST['first-name'];
        $last_name = $_POST['last-name'];
        $email = $_POST['email'];
        $password = $_POST['initial-password'];
        $phone_number= $_POST['phone-number'];
        $address = $_POST['address'];
        $nic= $_POST['nic-number'];
        $dob= $_POST['dob'];
       

        $sql1 = "INSERT INTO User (First_Name, Last_Name, Email, User_Address, Contact_No, NIC, Pswd, DOB, Type) VALUES ('$first_name', '$last_name', '$email', '$address', '$phone_number', '$nic', '$password', '$dob', 'Admin')";
        $result1 = mysqli_query($conn, $sql1);
    
        $sql2="SELECT User_ID FROM User WHERE Email='$email'";
        $result2 = mysqli_query($conn, $sql2);
        $row = mysqli_fetch_assoc($result2);
        $user_id = $row['User_ID'];
        
        $sql3 = "INSERT INTO System_Admin (Admin_ID) VALUES ('$user_id')";
        $result3 = mysqli_query($conn, $sql3);
    
        if($result1 && $result3){
            echo "<script>showSuccessMessage()</script>";
        }else{
            echo "<script>showFailMessage('We are having trouble with account. Please try again later.')</script>";
            
        }
    }
}

 ?>