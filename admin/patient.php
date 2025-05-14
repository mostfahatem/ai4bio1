<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Patients</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .patient-details-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            width: 150px;
        }
        .medication-table, .appointment-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        .medication-table th, .appointment-table th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .medication-table td, .appointment-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .status-done {
            color: green;
            font-weight: 600;
        }
        .status-pending {
            color: orange;
            font-weight: 600;
        }
        .status-cancelled {
            color: red;
            font-weight: 600;
        }
        .status-ongoing {
            color: orange;
            font-weight: 600;
        }
        .status-completed {
            color: green;
            font-weight: 600;
        }
        .status-paused {
            color: red;
            font-weight: 600;
        }
    </style>
    <style>
    .popup{
        animation: transitionIn-Y-bottom 0.5s;
        max-height: 90vh; /* تحديد ارتفاع أقصى للنافذة المنبثقة */
        overflow: hidden; /* إخفاء أي محتوى يتجاوز الارتفاع */
    }
    .popup-content {
        max-height: 80vh; /* ارتفاع محتوى النافذة المنبثقة */
        overflow-y: auto; /* تمكين التمرير العمودي */
        padding-right: 15px; /* إضافة مساحة لمنع تداخل شريط التمرير مع المحتوى */
    }
    .sub-table{
        animation: transitionIn-Y-bottom 0.5s;
    }
    .patient-details-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
    }
    .detail-row {
        display: flex;
        margin-bottom: 10px;
    }
    .detail-label {
        font-weight: 600;
        width: 150px;
    }
    .medication-table, .appointment-table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }
    .medication-table th, .appointment-table th {
        background-color: #f2f2f2;
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }
    .medication-table td, .appointment-table td {
        padding: 8px;
        border: 1px solid #ddd;
    }
    .status-done {
        color: green;
        font-weight: 600;
    }
    .status-pending {
        color: orange;
        font-weight: 600;
    }
    .status-cancelled {
        color: red;
        font-weight: 600;
    }
    .status-ongoing {
        color: orange;
        font-weight: 600;
    }
    .status-completed {
        color: green;
        font-weight: 600;
    }
    .status-paused {
        color: red;
        font-weight: 600;
    }
</style>
</head>
<body>
    <?php
    session_start();
    if(!isset($_SESSION["user"]) || ($_SESSION["user"])=="" || $_SESSION['usertype']!='a') {
        header("location: ../login.php");
        exit();
    }

    include("../connection.php");
    $useremail = $_SESSION["user"];
    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    
    if($_POST) {
        $keyword = $_POST["search"];
        $sqlmain = "SELECT * FROM patient WHERE pemail='$keyword' OR pname='$keyword' OR pname LIKE '$keyword%' OR pname LIKE '%$keyword' OR pname LIKE '%$keyword%'";
    } else {
        $sqlmain = "SELECT * FROM patient ORDER BY pid DESC";
    }
    ?>
    
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
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@edoc.com</p>
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
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient  menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="patient.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            <datalist id="patient">
                                <?php
                                $list11 = $database->query("SELECT pname, pemail FROM patient");
                                for ($y=0;$y<$list11->num_rows;$y++) {
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                }
                                ?>
                            </datalist>
                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php echo $today; ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Patients (<?php echo $database->query($sqlmain)->num_rows; ?>)</p>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" style="border-spacing:0;">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">Name</th>
                                            <th class="table-headin">NIC</th>
                                            <th class="table-headin">Telephone</th>
                                            <th class="table-headin">Email</th>
                                            <th class="table-headin">Date of Birth</th>
                                            <th class="table-headin">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $database->query($sqlmain);
                                        if($result->num_rows == 0) {
                                            echo '<tr>
                                                <td colspan="6">
                                                <br><br><br><br>
                                                <center>
                                                <img src="../img/notfound.svg" width="25%">
                                                
                                                <br>
                                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords !</p>
                                                <a class="non-style-link" href="patient.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Patients &nbsp;</font></button>
                                                </a>
                                                </center>
                                                <br><br><br><br>
                                                </td>
                                                </tr>';
                                        } else {
                                            for ($x=0; $x<$result->num_rows;$x++) {
                                                $row = $result->fetch_assoc();
                                                $pid = $row["pid"];
                                                $name = $row["pname"];
                                                $email = $row["pemail"];
                                                $nic = $row["pnic"];
                                                $dob = $row["pdob"];
                                                $tel = $row["ptel"];
                                                
                                                echo '<tr>
                                                    <td> &nbsp;'.substr($name,0,35).'</td>
                                                    <td>'.substr($nic,0,12).'</td>
                                                    <td>'.substr($tel,0,10).'</td>
                                                    <td>'.substr($email,0,20).'</td>
                                                    <td>'.substr($dob,0,10).'</td>
                                                    <td>
                                                        <div style="display:flex;justify-content: center;">
                                                            <a href="?action=view&id='.$pid.'" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
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
    if($_GET && isset($_GET["id"])) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        $sqlmain = "SELECT * FROM patient WHERE pid='$id'";
        $result = $database->query($sqlmain);
        $row = $result->fetch_assoc();
        $name = $row["pname"];
        $email = $row["pemail"];
        $nic = $row["pnic"];
        $dob = $row["pdob"];
        $tele = $row["ptel"];
        $address = $row["paddress"];
        $gender = $row["gender"];
        $blood_type = $row["blood_type"];
        $height = $row["height"];
        $weight = $row["weight"];
        $allergies = $row["allergies"];
        $chronic_conditions = $row["chronic_conditions"];
        
        // Get appointments
        $appointments_query = "SELECT a.*, s.title, s.scheduledate, s.scheduletime, d.docname 
                             FROM appointment a
                             JOIN schedule s ON a.scheduleid = s.scheduleid
                             JOIN doctor d ON s.docid = d.docid
                             WHERE a.pid = $id
                             ORDER BY a.appodate DESC";
        $appointments_result = $database->query($appointments_query);
        
        // Get medications
        $medications_query = "SELECT * FROM medication WHERE pid = $id ORDER BY start_date DESC";
        $medications_result = $database->query($medications_query);
        
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <a class="close" href="patient.php">&times;</a>
            <div class="popup-content" style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Patient Details</p>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Patient ID: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    P-'.$id.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$name.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$email.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$nic.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$tele.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Address: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$address.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Date of Birth: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$dob.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Gender: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.ucfirst($gender).'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Blood Type: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$blood_type.'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Height/Weight: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.($height ? $height.' cm' : 'N/A').' / '.($weight ? $weight.' kg' : 'N/A').'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Allergies: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.($allergies ? $allergies : 'None').'<br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Chronic Conditions: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.($chronic_conditions ? $chronic_conditions : 'None').'<br><br>
                                </td>
                            </tr>
                            
                            <!-- Appointments Section -->
                            <tr>
                                <td colspan="2">
                                    <p style="font-size: 20px;font-weight: 500;margin: 20px 0 10px 0;">Appointments</p>';
                                    
                                    if($appointments_result->num_rows > 0) {
                                        echo '
                                        <table class="appointment-table">
                                            <tr>
                                                <th>Appt. No</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Session</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                            </tr>';
                                            
                                            while($appt = $appointments_result->fetch_assoc()) {
                                                $status_class = '';
                                                if($appt["status"] == 'done') $status_class = 'status-done';
                                                if($appt["status"] == 'pending') $status_class = 'status-pending';
                                                if($appt["status"] == 'cancelled') $status_class = 'status-cancelled';
                                                
                                                echo '
                                                <tr>
                                                    <td>'.$appt["apponum"].'</td>
                                                    <td>'.$appt["appodate"].'</td>
                                                    <td>'.substr($appt["scheduletime"], 0, 5).'</td>
                                                    <td>'.$appt["title"].'</td>
                                                    <td>Dr. '.$appt["docname"].'</td>
                                                    <td class="'.$status_class.'">'.ucfirst($appt["status"]).'</td>
                                                </tr>';
                                            }
                                            
                                            echo '
                                        </table>';
                                    } else {
                                        echo '<p>No appointments found</p>';
                                    }
                                    
                                    echo '
                                </td>
                            </tr>
                            
                            <!-- Medications Section -->
                            <tr>
                                <td colspan="2">
                                    <p style="font-size: 20px;font-weight: 500;margin: 20px 0 10px 0;">Medications</p>';
                                    
                                    if($medications_result->num_rows > 0) {
                                        echo '
                                        <table class="medication-table">
                                            <tr>
                                                <th>Medication</th>
                                                <th>Dosage</th>
                                                <th>Schedule</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                            </tr>';
                                            
                                            while($med = $medications_result->fetch_assoc()) {
                                                $status_class = '';
                                                if($med["status"] == 'ongoing') $status_class = 'status-ongoing';
                                                if($med["status"] == 'completed') $status_class = 'status-completed';
                                                if($med["status"] == 'paused') $status_class = 'status-paused';
                                                
                                                echo '
                                                <tr>
                                                    <td>'.$med["medname"].'</td>
                                                    <td>'.$med["dosage"].'</td>
                                                    <td>'.$med["daily_doses"].'x daily at '.substr($med["intake_time"], 0, 5).'</td>
                                                    <td>'.$med["start_date"].'</td>
                                                    <td>'.$med["end_date"].'</td>
                                                    <td class="'.$status_class.'">'.ucfirst($med["status"]).'</td>
                                                </tr>';
                                            }
                                            
                                            echo '
                                        </table>';
                                    } else {
                                        echo '<p>No medications prescribed</p>';
                                    }
                                    
                                    echo '
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2">
                                    <a href="patient.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" style="width:30%"></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </center>
                <br><br>
            </div>
        </div>';
    }
    ?>
</body>
</html>