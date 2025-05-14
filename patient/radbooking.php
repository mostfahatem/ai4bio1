<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Radiology Session Booking</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .session-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }
        .xray-badge {
            background-color: #4CAF50;
            color: white;
        }
        .ct-badge {
            background-color: #2196F3;
            color: white;
        }
        .mri-badge {
            background-color: #9C27B0;
            color: white;
        }
        .preparation-box {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 10px;
            margin: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php
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
    
    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
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
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session ">
                        <a href="radio.php" class="non-style-link-menu"><div><p class="menu-text">Radiology Sessions</p></div></a>
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
                        <a href="http://127.0.0.1:5000/" class="non-style-link-menu"><div><p class="menu-text">Med AI</p></a></div>
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
            <!-- القائمة الجانبية تبقى كما هي -->
            <!-- ... نفس كود القائمة ... -->
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                    <td width="13%">
                        <a href="radio.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
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
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49);font-weight:400;">
                            Radiology Session Booking
                        </p>
                    </td>
                </tr>
                
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                            <tbody>
                            <?php
                            if(isset($_GET["id"])){
                                $id=$_GET["id"];
                                $sqlmain= "SELECT * FROM radiology_schedule 
                                          INNER JOIN radiology_technicians ON radiology_schedule.techid=radiology_technicians.techid 
                                          WHERE radiology_schedule.scheduleid=$id";
                                
                                $result= $database->query($sqlmain);
                                $row=$result->fetch_assoc();
                                
                                $scheduleid=$row["scheduleid"];
                                $title=$row["title"];
                                $techname=$row["techname"];
                                $techemail=$row["email"];
                                $scheduledate=$row["scheduledate"];
                                $scheduletime=$row["scheduletime"];
                                $session_type=$row["session_type"];
                                $duration=$row["duration"];
                                $preparation=$row["preparation_instructions"];
                                $fee=$row["fee"] ?? "EG.2 000.00"; // قيمة افتراضية إذا لم يكن هناك رسوم محددة
                                
                                // تحديد لون البادج حسب نوع الجلسة
                                $badge_class = '';
                                if($session_type == 'xray') $badge_class = 'xray-badge';
                                elseif($session_type == 'ct') $badge_class = 'ct-badge';
                                elseif($session_type == 'mri') $badge_class = 'mri-badge';
                                
                                $sql2="SELECT * FROM radiology_appointment WHERE scheduleid=$id";
                                $result12= $database->query($sql2);
                                $apponum=($result12->num_rows)+1;
                                
                                echo '
                                <form action="booking-complete.php" method="post">
                                    <input type="hidden" name="scheduleid" value="'.$scheduleid.'">
                                    <input type="hidden" name="apponum" value="'.$apponum.'">
                                    <input type="hidden" name="date" value="'.$today.'">
                                    <input type="hidden" name="type" value="radiology">
                                
                                    <td style="width: 50%;" rowspan="2">
                                        <div class="dashboard-items search-items">
                                            <div style="width:100%">
                                                <div class="h1-search" style="font-size:25px;">
                                                    Radiology Session Details
                                                    <span class="session-badge '.$badge_class.'">'.strtoupper($session_type).'</span>
                                                </div><br><br>
                                                <div class="h3-search" style="font-size:18px;line-height:30px">
                                                    Technician: &nbsp;&nbsp;<b>'.$techname.'</b><br>
                                                    Email: &nbsp;&nbsp;<b>'.$techemail.'</b> 
                                                </div>
                                                <div class="h3-search" style="font-size:18px;">
                                                    Session Title: '.$title.'<br>
                                                    Session Date: '.$scheduledate.'<br>
                                                    Start Time: '.substr($scheduletime,0,5).'<br>
                                                    Duration: '.$duration.' minutes<br>
                                                    Fee: <b>'.$fee.'</b>
                                                </div>
                                                <div class="preparation-box">
                                                    <b>Preparation Instructions:</b><br>
                                                    '.$preparation.'
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td style="width: 25%;">
                                        <div class="dashboard-items search-items">
                                            <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
                                                <div class="h1-search" style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">
                                                    Your Appointment Number
                                                </div>
                                                <center>
                                                    <div class="dashboard-icons" style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:var(--btnnictext);background-color: var(--btnice)">'.$apponum.'</div>
                                                </center>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="Submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;" value="Book Now" name="booknow">
                                        </td>
                                    </tr>
                                </form>';
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