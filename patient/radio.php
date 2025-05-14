<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Radiology Sessions</title>
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
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="radio.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Radiology Sessions</p></div></a>
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
                        <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Radiology Type or Date (YYYY-MM-DD)" list="radiology" value="<?php echo isset($_POST['search']) ? $_POST['search'] : '' ?>">&nbsp;&nbsp;
                            
                            <?php
                            echo '<datalist id="radiology">';
                            $list11 = $database->query("SELECT DISTINCT session_type FROM radiology_schedule");
                            $list12 = $database->query("SELECT DISTINCT title FROM radiology_schedule");
                            
                            for ($y=0;$y<$list11->num_rows;$y++){
                                $row00=$list11->fetch_assoc();
                                $d=$row00["session_type"];
                                echo "<option value='$d'><br/>";
                            };
                            
                            for ($y=0;$y<$list12->num_rows;$y++){
                                $row00=$list12->fetch_assoc();
                                $d=$row00["title"];
                                echo "<option value='$d'><br/>";
                            };
                            echo '</datalist>';
                            ?>
                            
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
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">
                            <?php 
                            $searchtype = "All Radiology";
                            $insertkey = "";
                            $q = '';
                            
                            $sqlmain = "SELECT * FROM radiology_schedule WHERE scheduledate>='$today' ORDER BY 
                                        CASE 
                                            WHEN session_type='xray' THEN 1
                                            WHEN session_type='ct' THEN 2
                                            WHEN session_type='mri' THEN 3
                                        END,
                                        scheduledate ASC";
                            
                            if($_POST && !empty($_POST["search"])){
                                $keyword = $_POST["search"];
                                $sqlmain = "SELECT * FROM radiology_schedule WHERE scheduledate>='$today' AND 
                                          (title='$keyword' OR title LIKE '$keyword%' OR title LIKE '%$keyword' OR title LIKE '%$keyword%' OR 
                                          session_type='$keyword' OR scheduledate LIKE '$keyword%' OR scheduledate LIKE '%$keyword' OR scheduledate LIKE '%$keyword%' OR scheduledate='$keyword') 
                                          ORDER BY scheduledate ASC";
                                $insertkey = $keyword;
                                $searchtype = "Search Result : ";
                                $q = '"';
                            }
                            
                            $result = $database->query($sqlmain);
                            echo $searchtype." Sessions (".$result->num_rows.")"; 
                            ?>
                        </p>
                        <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)">
                            <?php echo $q.$insertkey.$q; ?>
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
                                        if($result->num_rows==0){
                                            echo '<tr>
                                                <td colspan="4">
                                                <br><br><br><br>
                                                <center>
                                                <img src="../img/notfound.svg" width="25%">
                                                
                                                <br>
                                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No radiology sessions found!</p>
                                                <a class="non-style-link" href="radio.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show All Sessions &nbsp;</font></button>
                                                </a>
                                                </center>
                                                <br><br><br><br>
                                                </td>
                                                </tr>';
                                        } else {
                                            for ($x=0; $x<($result->num_rows);$x++){
                                                echo "<tr>";
                                                for($q=0;$q<3;$q++){
                                                    $row=$result->fetch_assoc();
                                                    if (!isset($row)) break;
                                                    
                                                    $scheduleid=$row["scheduleid"];
                                                    $title=$row["title"];
                                                    $session_type=$row["session_type"];
                                                    $scheduledate=$row["scheduledate"];
                                                    $scheduletime=$row["scheduletime"];
                                                    $duration=$row["duration"];
                                                    $preparation=$row["preparation_instructions"];
                                                    
                                                    // تحديد لون البادج حسب نوع الجلسة
                                                    $badge_class = '';
                                                    if($session_type == 'xray') $badge_class = 'xray-badge';
                                                    elseif($session_type == 'ct') $badge_class = 'ct-badge';
                                                    elseif($session_type == 'mri') $badge_class = 'mri-badge';
                                                    
                                                    echo '
                                                    <td style="width: 25%;">
                                                        <div class="dashboard-items search-items">
                                                            <div style="width:100%">
                                                                <div class="h1-search">
                                                                    '.substr($title,0,21).'
                                                                    <span class="session-badge '.$badge_class.'">'.strtoupper($session_type).'</span>
                                                                </div><br>
                                                                <div class="h3-search">
                                                                    Duration: '.$duration.' minutes
                                                                </div>
                                                                <div class="h4-search">
                                                                    '.$scheduledate.'<br>Starts: <b>@'.substr($scheduletime,0,5).'</b>
                                                                </div>
                                                                <div class="h5-search" style="font-size:12px;color:#666">
                                                                    Preparation: '.substr($preparation,0,50).'...
                                                                </div>
                                                                <br>
                                                                <a href="radbooking.php?id='.$scheduleid.'&type=radiology"><button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Book Now</font></button></a>
                                                            </div>
                                                        </div>
                                                    </td>';
                                                }
                                                echo "</tr>";
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