<?php
session_start();

// التحقق من تسجيل الدخول
if(!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

// جلب بيانات الطبيب
$useremail = $_SESSION["user"];
$userrow = $database->query("SELECT * FROM doctor WHERE docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];
$username = $userfetch["docname"];

// استعلام المواعيد الأساسي
$list110 = $database->query("SELECT * FROM schedule 
                            INNER JOIN appointment ON schedule.scheduleid=appointment.scheduleid 
                            INNER JOIN patient ON patient.pid=appointment.pid 
                            INNER JOIN doctor ON schedule.docid=doctor.docid 
                            WHERE doctor.docid=$userid");

// معالجة تحديث حالة المواعيد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_done'])) {
    if (isset($_POST['appointment_done']) && !empty($_POST['appointment_done'])) {
        $successCount = 0;
        foreach ($_POST['appointment_done'] as $appoid) {
            $sqlUpdate = "UPDATE appointment SET status = 'done' WHERE appoid = ?";
            $stmt = $database->prepare($sqlUpdate);
            $stmt->bind_param("i", $appoid);
            if($stmt->execute()) {
                $successCount++;
            }
            $stmt->close();
        }
        header("Refresh:0");
        exit();
    } else {
        $_SESSION['error'] = "لم يتم اختيار أي موعد للتحديث";
    }
}

// استعلام المواعيد مع الفلتر
$sqlmain = "SELECT appointment.appoid, appointment.status, schedule.scheduleid, 
            schedule.title, doctor.docname, patient.pname, schedule.scheduledate, 
            schedule.scheduletime, appointment.apponum, appointment.appodate 
            FROM schedule 
            INNER JOIN appointment ON schedule.scheduleid=appointment.scheduleid 
            INNER JOIN patient ON patient.pid=appointment.pid 
            INNER JOIN doctor ON schedule.docid=doctor.docid 
            WHERE doctor.docid=$userid";

if(isset($_POST['sheduledate']) && !empty($_POST['sheduledate'])) {
    $sheduledate = $_POST["sheduledate"];
    $sqlmain .= " AND schedule.scheduledate='$sheduledate'";
}
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
    <title>Appointments</title>
    <style>
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
        .alert {
            padding: 15px;
            margin: 10px 0;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- عرض رسائل الخطأ والنجاح -->
        <?php
        if(isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
            unset($_SESSION['success']);
        }
        if(isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="menu">
            <!-- قائمة التنقل -->
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
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">My Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="repo.php" class="non-style-link-menu"><div><p class="menu-text">Treated Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-s">
                        <a href="http://127.0.0.1:8000/" class="non-style-link-menu"><div><p class="menu-text">Pneumonia</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
    <td class="menu-btn menu-icon-s">
        <a href="http://127.0.0.1:1000/" class="non-style-link-menu">
            <div><p class="menu-text">Brain tumor</p></div>
        </a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-s">
        <a href="http://127.0.0.1:2000/" class="non-style-link-menu">
            <div><p class="menu-text">blood diseases</p></div>
        </a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-s">
        <a href="http://127.0.0.1:3000/" class="non-style-link-menu">
            <div><p class="menu-text">leukemia</p></div>
        </a>
    </td>
</tr>
<tr class="menu-row">
    <td class="menu-btn menu-icon-s">
        <a href="http://127.0.0.1:4000/" class="non-style-link-menu">
            <div><p class="menu-text">liver tumor</p></div>
        </a>
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
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Appointment Manager</p>
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
                            My Appointments (<?php echo $list110->num_rows; ?>)
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
                                        <input type="submit" name="filter" value="Filter" class="btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin :0;width:100%">
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
                                <form action="" method="post">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">Patient name</th>
                                            <th class="table-headin">Appointment number</th>
                                            <th class="table-headin">Session Title</th>
                                            <th class="table-headin">Session Date & Time</th>
                                            <th class="table-headin">Appointment Date</th>
                                            <th class="table-headin">Done</th>
                                            <th class="table-headin">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $database->query($sqlmain);
                                        if ($result->num_rows == 0) {
                                            echo '<tr>
                                                    <td colspan="7">
                                                    <br><br><br><br>
                                                    <center>
                                                    <img src="../img/notfound.svg" width="25%">
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                                    <a class="non-style-link" href="appointment.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</button></a>
                                                    </center>
                                                    <br><br><br><br>
                                                    </td>
                                                  </tr>';
                                        } else {
                                            while($row = $result->fetch_assoc()) {
                                                $isDone = $row["status"] === 'done';
                                                echo '<tr>
                                                        <td style="font-weight:600;">&nbsp;' . substr($row["pname"], 0, 25) . '</td>
                                                        <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);">' . $row["apponum"] . '</td>
                                                        <td>' . substr($row["title"], 0, 15) . '</td>
                                                        <td style="text-align:center;">' . substr($row["scheduledate"], 0, 10) . ' @' . substr($row["scheduletime"], 0, 5) . '</td>
                                                        <td style="text-align:center;">' . $row["appodate"] . '</td>
                                                        <td style="text-align:center;">
                                                            <input type="checkbox" name="appointment_done[]" value="' . $row["appoid"] . '"' . ($isDone ? ' checked' : '') . '>
                                                        </td>
                                                        <td>

                                                            <div style="display:flex;justify-content: center;">
                                                                <a href="delete-appointment.php?action=drop&id=' . $row["appoid"] . '&name=' . urlencode($row["pname"]) . '&session=' . urlencode($row["title"]) . '&apponum=' . $row["apponum"] . '" class="non-style-link" ">
                                                   Cancel
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <hr>
                                <tr>
                                    <td colspan="7" style="text-align: center;">
                                        <input type="submit" name="submit_done" value="Update Status" class="btn-primary btn">
                                    </td>
                                </tr>
                                <hr>
                                </form>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php
    if(isset($_GET['action']) && isset($_GET['id'])) {
        $id = intval($_GET["id"]);
        $action = $_GET["action"];
        
        if($action == 'drop') {
            $nameget = htmlspecialchars($_GET["name"]);
            $session = htmlspecialchars($_GET["session"]);
            $apponum = htmlspecialchars($_GET["apponum"]);
            ?>
            <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <h2>Are you sure?</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        You want to cancel this appointment<br><br>
                        Patient Name: &nbsp;<b><?php echo substr($nameget,0,40); ?></b><br>
                        Appointment number: &nbsp;<b><?php echo substr($apponum,0,40); ?></b><br><br>
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id=<?php echo $id; ?>" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                <font class="tn-in-text">Yes</font>
                            </button>
                        </a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                <font class="tn-in-text">No</font>
                            </button>
                        </a>
                    </div>
                </center>
                </div>
            </div>
            <?php
        }
    }
    ?>
</body>
</html>