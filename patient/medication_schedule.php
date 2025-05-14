<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sql_database_edoc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Patient ID for schedule
session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
        header("location: ../login.php");
    }else{
        $useremail=$_SESSION["user"];
    }

}else{
    header("location: ../login.php");
}
include("../connection.php");

$userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];
$pid = $userid; // Update this as needed
$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('last Sunday', strtotime($today)));
$endOfWeek = date('Y-m-d', strtotime('next Saturday', strtotime($today)));

// Query for medication schedule
$sql = "SELECT 
            m.medname AS 'Medicine Name', 
            m.dosage AS 'Dosage', 
            m.intake_time AS 'Intake Time', 
            m.start_date AS 'Start Date', 
            m.end_date AS 'End Date', 
            m.daily_doses AS 'Daily Doses', 
            m.status AS 'Status', 
            m.notes AS 'Notes'
        FROM medication m
        WHERE m.pid = $pid
          AND DATE(m.start_date) <= '$endOfWeek' 
          AND DATE(m.end_date) >= '$startOfWeek'
          AND m.status = 'ongoing'
        ORDER BY m.intake_time";

$result = $conn->query($sql);

// Generate dates for the week
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i day", strtotime($startOfWeek)));
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <title>جدول مواعيد الأدوية الأسبوعي</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table tbody tr {
    margin-bottom: 10px; /* مسافة بين الصفوف */
    border-bottom: 1px solid #ddd; /* خط سفلي خفيف */
}
.sub-table tbody tr:last-child {
    margin-bottom: 0; /* إزالة المسافة عن آخر صف */
    border-bottom: none; /* إزالة الخط الأخير */
}

    </style>
    
</head>
<body>
    <?php

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    include("../connection.php");
    $userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];
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
                                    <p class="profile-title"><?php echo substr($username, 0, 13); ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
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
                    <td class="menu-btn menu-icon-med menu-active menu-icon-med-active">
                        <a href="medication_schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My schedule</p></a></div>
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
            <table border="0" width="100%" style="border-spacing: 0; margin:0; padding:0; margin-top:25px;">
                <tr>
                    <td>
                        <p class="heading-main12" style="margin-left: 45px; font-size:18px; color:rgb(49, 49, 49)">Medication Schedule</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="abc scroll">
                            <table width="93%" class="sub-table scrolldown" border="0">
                                <thead>
                                    <tr>
                                        <th class="table-headin">اليوم</th>
                                        <th class="table-headin">التاريخ</th>
                                        <th class="table-headin">اسم الدواء</th>
                                        <th class="table-headin">الجرعة</th>
                                        <th class="table-headin">وقت أخذ الدواء</th>
                                        <th class="table-headin">عدد الجرعات اليومية</th>
                                        <th class="table-headin">الحالة</th>
                                        <th class="table-headin">الملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            foreach ($dates as $currentDate) {
                                                if (strtotime($currentDate) >= strtotime($row['Start Date']) && strtotime($currentDate) <= strtotime($row['End Date'])) {
                                                    $dayName = date('l', strtotime($currentDate));
                                                    $dayNameInArabic = [
                                                        "Sunday" => "الأحد",
                                                        "Monday" => "الإثنين",
                                                        "Tuesday" => "الثلاثاء",
                                                        "Wednesday" => "الأربعاء",
                                                        "Thursday" => "الخميس",
                                                        "Friday" => "الجمعة",
                                                        "Saturday" => "السبت"
                                                    ][$dayName];
                                                    echo "<tr>
                                                        <td>$dayNameInArabic</td>
                                                        <td>$currentDate</td>
                                                        <td>{$row['Medicine Name']}</td>
                                                        <td>{$row['Dosage']}</td>
                                                        <td>{$row['Intake Time']}</td>
                                                        <td>{$row['Daily Doses']}</td>
                                                        <td>{$row['Status']}</td>
                                                        <td>{$row['Notes']}</td>
                                                    </tr>";
                                                }
                                            }
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'><p>No medication scheduled for this week.</p></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
