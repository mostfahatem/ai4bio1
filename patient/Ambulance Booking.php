<?php
// Include database connection and session
require '../connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("location: ../login.php");
    exit();
}
$useremail = $_SESSION["user"];

// Fetch patient data from the database
$userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Ambulance Request</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table,.anime{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session ">
                        <a href="radio.php" class="non-style-link-menu"><div><p class="menu-text">Radiology Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
    <td class="menu-btn menu-icon-session">
        <a href="lab_types.php" class="non-style-link-menu">
            <div><p class="menu-text">Medical Labs</p></div>
        </a>
    </td>
</tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="form.php" class="non-style-link-menu"><div><p class="menu-text">Booking form </p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-med">
                        <a href="medication_schedule.php" class="non-style-link-menu"><div><p class="menu-text">My schedule</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-s">
                        <a href="http://127.0.0.1:9000/" class="non-style-link-menu"><div><p class="menu-text">Med AI</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-s menu-active menu-icon-s-active">
                        <a href="Ambulance Booking.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Ambulance</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                <tr>
                    <td colspan="1" class="nav-bar" >
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Ambulance Request</p>
                    </td>
                    <td width="25%"></td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                                date_default_timezone_set('Asia/Kolkata');
                                $today = date('Y-m-d');
                                echo $today;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                            <table class="filter-container" style="border: none;width:95%" border="0">
                                <tr>
                                    <td>
                                        <h3>Request an Ambulance</h3>
                                        <form action="process_ambulance.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                                            <div class="form-group">
                                                <label for="patient_name">Full Name:</label>
                                                <input type="text" id="patient_name" name="patient_name" class="input-text" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone Number:</label>
                                                <input type="text" id="phone" name="phone" class="input-text" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address:</label>
                                                <input type="text" id="address" name="address" class="input-text" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="location">Location (Optional):</label>
                                                <input type="text" id="location" name="location" class="input-text">
                                            </div>
                                            <div class="form-group">
                                                <label for="emergency_type">Emergency Type:</label>
                                                <textarea id="emergency_type" name="emergency_type" class="input-text" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" value="Submit Request" class="login-btn btn-primary btn">
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                            <table class="filter-container" style="border: none;width:95%" border="0">
                                <tr>
                                    <td>
                                        <h3>Your Previous Requests</h3>
                                        <?php
                                        $query = "SELECT * FROM ambulance_requests WHERE user_email = '$useremail' ORDER BY request_time DESC";
                                        $result = $database->query($query);
                                        if ($result->num_rows > 0) {
                                            echo '<div class="previous-requests">';
                                            echo "<ul>";
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<li>Emergency: " . $row["emergency_type"] . " - Date: " . $row["request_time"] . "</li>";
                                            }
                                            echo "</ul>";
                                            echo '</div>';
                                        } else {
                                            echo '<p>No previous requests found.</p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>