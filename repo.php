<?php
session_start();
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

// استعلام المرضى المعالجين مع بيانات الجلسة
$sqlDonePatients = "SELECT a.appoid, p.pid, p.pname, p.pemail, 
                   a.appodate, a.report, a.apponum, s.title as session_title,
                   s.scheduledate, s.scheduletime, a.status
                   FROM appointment a
                   INNER JOIN schedule s ON a.scheduleid = s.scheduleid
                   INNER JOIN patient p ON a.pid = p.pid
                   WHERE s.docid = $userid AND a.status = 'done'
                   ORDER BY a.appodate DESC";
$donePatientsResult = $database->query($sqlDonePatients);
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
    <title>Treated Patients</title>
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
        .badge-done {
            background-color: #2ecc71;
            color: white;
        }
        .appointment-card {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
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
        .action-btn {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
            margin-right: 10px;
        }
        .report-preview {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            max-height: 100px;
            overflow-y: auto;
            font-size: 14px;
        }
        .filter-container {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
                    <td class="menu-btn menu-icon-dashbord">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">My Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-treated menu-active menu-icon-treated-active">
                        <a href="treated-patients.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Treated Patients</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
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
                        <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Treated Patients</p>
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
                            My Treated Patients (<?php echo $donePatientsResult->num_rows; ?>)
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
                                        if($donePatientsResult->num_rows == 0) {
                                            echo '<tr><td colspan="7">
                                                <br><br><br><br>
                                                <center>
                                                <img src="../img/notfound.svg" width="25%">
                                                <br>
                                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No treated patients found!</p>
                                                </center>
                                                <br><br><br><br>
                                            </td></tr>';
                                        } else {
                                            while($row = $donePatientsResult->fetch_assoc()) {
                                                echo '
                                                <tr>
                                                    <td>
                                                        <div class="appointment-card">
                                                            <div class="card-header">
                                                                <span class="badge badge-done">Completed</span>
                                                                <span class="appointment-id">#'.$row['apponum'].'</span>
                                                            </div>
                                                            <div class="card-body">
                                                                <h3>'.htmlspecialchars($row['pname']).'</h3>
                                                                <p><strong>Email:</strong> '.htmlspecialchars($row['pemail']).'</p>
                                                                <p><strong>Session:</strong> '.htmlspecialchars($row['session_title']).'</p>
                                                                <p><strong>Appointment Date:</strong> '.htmlspecialchars($row['appodate']).'</p>
                                                                <p><strong>Session Date:</strong> '.htmlspecialchars($row['scheduledate']).' @ '.substr($row['scheduletime'], 0, 5).'</p>';
                                                
                                                if(!empty($row['report'])) {
                                                    echo '<div class="report-preview">'.substr(htmlspecialchars($row['report']), 0, 100).'...</div>';
                                                }
                                                
                                                echo '</div>
                                                            <div class="card-footer">
                                                                <a href="add-report.php?appoid='.$row['appoid'].'" class="action-btn">'.(!empty($row['report']) ? 'Edit Report' : 'Add Report').'</a>
                                                                <a href="pv.php?id='.$row['pid'].'" class="action-btn">View Patient</a>
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
</body>
</html>