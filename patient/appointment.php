<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>My Appointments</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }
        .badge-regular {
            background-color: #6c757d;
            color: white;
        }
        .badge-xray {
            background-color: #4CAF50;
            color: white;
        }
        .badge-ct {
            background-color: #2196F3;
            color: white;
        }
        .badge-mri {
            background-color: #9C27B0;
            color: white;
        }
        .badge-lab {
            background-color: #f39c12;
            color: white;
        }
        .appointment-card {
            transition: all 0.3s ease;
        }
        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .appointment-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .badge-doctor { background-color: #3498db; }
        .xray-badge { background-color: #2ecc71; }
        .ct-badge { background-color: #e67e22; }
        .mri-badge { background-color: #9b59b6; }
        .badge-regular { background-color: #7f8c8d; }
        .badge-lab { background-color: #f39c12; }
        .appointment-id {
            color: #7f8c8d;
            font-size: 12px;
        }
        .card-body h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .card-body p {
            margin: 5px 0;
        }
        .cancel-btn {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php
session_start();
if(!isset($_SESSION["user"]) || empty($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
    header("location: ../login.php");
    exit();
}

$useremail = $_SESSION["user"];
include("../connection.php");

// جلب بيانات المريض
$stmt = $database->prepare("SELECT pid, pname FROM patient WHERE pemail = ?");
$stmt->bind_param("s", $useremail);
$stmt->execute();
$userfetch = $stmt->get_result()->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];
$stmt->close();

// استعلام شامل لجميع أنواع المواعيد
$base_sql = "
(SELECT 
    a.appoid as id,
    a.apponum as number,
    a.appodate as booking_date,
    a.status,
    'doctor' as type,
    d.docname as provider,
    s.scheduledate as session_date,
    s.scheduletime as session_time,
    s.title as description,
    NULL as session_type,
    NULL as preparation_instructions
FROM appointment a
LEFT JOIN schedule s ON a.scheduleid = s.scheduleid
LEFT JOIN doctor d ON s.docid = d.docid
WHERE a.pid = ? AND a.type = 'doctor')

UNION ALL

(SELECT 
    ra.appoid as id,
    ra.apponum as number,
    ra.appodate as booking_date,
    ra.status,
    'radiology' as type,
    rt.techname as provider,
    rs.scheduledate as session_date,
    rs.scheduletime as session_time,
    rs.title as description,
    rs.session_type,
    rs.preparation_instructions
FROM radiology_appointment ra
JOIN radiology_schedule rs ON ra.scheduleid = rs.scheduleid
JOIN radiology_technicians rt ON rs.techid = rt.techid
WHERE ra.pid = ?)

UNION ALL

(SELECT 
    la.appoid as id,
    la.apponum as number,
    la.appodate as booking_date,
    la.status,
    'lab' as type,
    lt.name as provider,
    ls.available_date as session_date,
    ls.start_time as session_time,
    lt.name as description,
    NULL as session_type,
    lt.preparation_instructions as preparation_instructions
FROM lab_appointments la
JOIN lab_schedule ls ON la.schedule_id = ls.schedule_id
JOIN lab_types lt ON ls.lab_type_id = lt.lab_type_id
WHERE la.pid = ?)
";

// تطبيق الفلتر إذا وجد
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST["sheduledate"])) {
    $sheduledate = $_POST["sheduledate"];
    $sql = "
    SELECT * FROM ($base_sql) AS all_appointments 
    WHERE session_date = ?
    ORDER BY session_date DESC, session_time DESC
    ";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("iiis", $userid, $userid, $userid, $sheduledate);
} else {
    $sql = "
    SELECT * FROM ($base_sql) AS all_appointments 
    ORDER BY session_date DESC, session_time DESC
    ";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("iii", $userid, $userid, $userid);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
    
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13) ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
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
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Bookings</p></a></div>
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
                    <td class="menu-btn menu-icon-s">
                        <a href="Ambulance Booking.php" class="non-style-link-menu"><div><p class="menu-text">Ambulance</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="appointment.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Bookings History</p>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php echo date('Y-m-d'); ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                            My Bookings (<?php echo $result->num_rows; ?>)
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;">
                        <center>
                            <table class="filter-container" border="0">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="5%" style="text-align: center;">Date:</td>
                                    <td width="30%">
                                        <form action="" method="post">
                                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">
                                    </td>
                                    <td width="12%">
                                        <input type="submit" name="filter" value="Filter" class="btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin:0;width:100%">
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
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0" style="border:none">
                                    <tbody>
                                        <?php
                                        if($result->num_rows == 0) {
                                            echo '<tr><td colspan="7">No appointments found</td></tr>';
                                        } else {
                                            while($row = $result->fetch_assoc()) {
                                                $appointment_type = $row['type'] ?? 'doctor';
                                                
                                                if($appointment_type == 'radiology') {
                                                    $title = $row['description'] ?? 'Radiology Session';
                                                    $provider = $row['provider'] ?? 'Technician';
                                                    $date = $row['session_date'] ?? 'N/A';
                                                    $time = $row['session_time'] ?? 'N/A';
                                                    $session_type = $row['session_type'] ?? 'general';
                                                    
                                                    switch(strtolower($session_type)) {
                                                        case 'xray': $badge_class = 'xray-badge'; break;
                                                        case 'ct': $badge_class = 'ct-badge'; break;
                                                        case 'mri': $badge_class = 'mri-badge'; break;
                                                        default: $badge_class = 'badge-regular';
                                                    }
                                                } 
                                                elseif($appointment_type == 'lab') {
                                                    $badge_class = 'badge-lab';
                                                    $title = $row['description'] ?? 'Lab Test';
                                                    $provider = $row['provider'] ?? 'Lab Technician';
                                                    $date = $row['session_date'] ?? 'N/A';
                                                    $time = $row['session_time'] ?? 'N/A';
                                                }
                                                else {
                                                    $badge_class = 'badge-doctor';
                                                    $title = $row['description'] ?? 'Doctor Appointment';
                                                    $provider = $row['provider'] ?? 'Doctor';
                                                    $date = $row['session_date'] ?? 'N/A';
                                                    $time = $row['session_time'] ?? 'N/A';
                                                }
                                                
                                                echo '
                                                <tr>
                                                    <td>
                                                        <div class="appointment-card">
                                                            <div class="card-header">
                                                                <span class="badge '.$badge_class.'">'.ucfirst($appointment_type).'</span>
                                                                <span class="appointment-id">#'.$row['number'].'</span>
                                                            </div>
                                                            <div class="card-body">
                                                                <h3>'.htmlspecialchars($title).'</h3>
                                                                <p><strong>With:</strong> '.htmlspecialchars($provider).'</p>
                                                                <p><strong>Date:</strong> '.htmlspecialchars($date).'</p>
                                                                <p><strong>Time:</strong> '.htmlspecialchars(substr($time, 0, 5)).'</p>
                                                                <p><strong>Status:</strong> '.htmlspecialchars($row['status'] ?? 'Pending').'</p>
                                                            </div>
                                                            <div class="card-footer">
                                                                <a href="?action=drop&id='.$row['id'].'&type='.$appointment_type.'" class="cancel-btn">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        $id = $_GET['id'] ?? '';
        $type = $_GET['type'] ?? '';
        
        if($action == 'booking-added') {
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <h2>Booking Successful</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            Your Appointment number is '.$id.'.<br><br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                            <a href="appointment.php" class="non-style-link">
                                <button class="btn-primary btn" style="margin:10px;padding:10px;">OK</button>
                            </a>
                        </div>
                    </center>
                </div>
            </div>';
        } elseif($action == 'drop') {
            $title = $_GET['title'] ?? '';
            $doc = $_GET['doc'] ?? '';
            
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <h2>Confirm Cancellation</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            Are you sure you want to cancel this '.$type.' appointment?<br><br>
                            Session: <b>'.htmlspecialchars(substr($title,0,40)).'</b><br>
                            Provider: <b>'.htmlspecialchars(substr($doc,0,40)).'</b><br><br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                            <a href="delete-appointment.php?id='.$id.'&type='.$type.'" class="non-style-link">
                                <button class="btn-primary btn" style="margin:10px;padding:10px;">Yes</button>
                            </a>
                            <a href="appointment.php" class="non-style-link">
                                <button class="btn-primary btn" style="margin:10px;padding:10px;">No</button>
                            </a>
                        </div>
                    </center>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>