<?php
session_start();
if(!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

// جلب بيانات الطبيب
$useremail = $_SESSION["user"];
$userrow = $database->query("select * from doctor where docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];
$username = $userfetch["docname"];

// جلب بيانات المريض إذا كان هناك معرف
$patient = null;
if(isset($_GET['id'])) {
    $pid = $_GET['id'];
    $stmt = $database->prepare("SELECT * FROM patient WHERE pid=?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $patient = $stmt->get_result()->fetch_assoc();
    
    // جلب تاريخ المواعيد مع الطبيب الحالي
    $appointments = [];
    $stmt = $database->prepare("SELECT a.appoid, a.appodate, s.title, a.status, a.report, a.diagnosis, a.treatment, a.followup_date 
                               FROM appointment a 
                               JOIN schedule s ON a.scheduleid = s.scheduleid 
                               WHERE a.pid=? AND s.docid=?");
    $stmt->bind_param("ii", $pid, $userid);
    $stmt->execute();
    $appointments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // فصل المواعيد المكتملة (التي تحتوي على تقارير)
    $completed_appointments = array_filter($appointments, function($app) {
        return $app['status'] == 'done' && ($app['report'] || $app['diagnosis'] || $app['treatment']);
    });
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
    <title>Patient Details</title>
    <style>
        .patient-details-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 900px;
        }
        .patient-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .patient-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-right: 20px;
        }
        .patient-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .patient-id {
            color: #7f8c8d;
            font-size: 14px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .detail-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .detail-title {
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .appointments-table th {
            background: #3498db;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .appointments-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .badge-pending {
            background-color: #f39c12;
        }
        .badge-done {
            background-color: #2ecc71;
        }
        .section-title {
            font-size: 20px;
            color: #2c3e50;
            margin: 30px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        .report-card {
            background: #f0f8ff;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .report-date {
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }
        .report-field {
            margin-bottom: 8px;
        }
        .report-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .no-reports {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
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
                    <td class="menu-btn menu-icon-patient menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Patients</p></a></div>
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
                        <a href="patient.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Patient Details</p>
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
                    <td colspan="4">
                        <?php if($patient): ?>
                        <div class="patient-details-container">
                            <div class="patient-header">
                                <div class="patient-avatar">
                                    <?php echo strtoupper(substr($patient['pname'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="patient-name"><?php echo htmlspecialchars($patient['pname']); ?></div>
                                    <div class="patient-id">Patient ID: P-<?php echo $patient['pid']; ?></div>
                                </div>
                            </div>
                            
                            <h3 class="section-title">Personal Information</h3>
                            <div class="details-grid">
                                <div class="detail-card">
                                    <div class="detail-title">Email</div>
                                    <div><?php echo htmlspecialchars($patient['pemail']); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Phone</div>
                                    <div><?php echo htmlspecialchars($patient['ptel']); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Date of Birth</div>
                                    <div><?php echo htmlspecialchars($patient['pdob']); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">NIC</div>
                                    <div><?php echo htmlspecialchars($patient['pnic']); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Gender</div>
                                    <div><?php echo htmlspecialchars($patient['gender'] ?? 'Not specified'); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Address</div>
                                    <div><?php echo htmlspecialchars($patient['paddress']); ?></div>
                                </div>
                            </div>
                            
                            <h3 class="section-title">Medical Information</h3>
                            <div class="details-grid">
                                <div class="detail-card">
                                    <div class="detail-title">Blood Type</div>
                                    <div><?php echo htmlspecialchars($patient['blood_type'] ?? 'Not specified'); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Allergies</div>
                                    <div><?php echo htmlspecialchars($patient['allergies'] ?? 'None'); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Chronic Conditions</div>
                                    <div><?php echo htmlspecialchars($patient['chronic_conditions'] ?? 'None'); ?></div>
                                </div>
                                <div class="detail-card">
                                    <div class="detail-title">Medications</div>
                                    <div><?php echo htmlspecialchars($patient['medications'] ?? 'None'); ?></div>
                                </div>
                            </div>
                            
                            <h3 class="section-title">Appointments History</h3>
                            <?php if(count($appointments) > 0): ?>
                            <table class="appointments-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Session</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['appodate']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['title']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $appointment['status'] == 'done' ? 'badge-done' : 'badge-pending'; ?>">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <p>No appointment history found.</p>
                            <?php endif; ?>
                            
                            <h3 class="section-title">Medical Reports</h3>
                            <?php if(count($completed_appointments) > 0): ?>
                                <?php foreach($completed_appointments as $appointment): ?>
                                <div class="report-card">
                                    <div class="report-date">
                                        <?php echo htmlspecialchars($appointment['appodate']); ?> - <?php echo htmlspecialchars($appointment['title']); ?>
                                    </div>
                                    
                                    <?php if($appointment['diagnosis']): ?>
                                    <div class="report-field">
                                        <span class="report-label">Diagnosis:</span>
                                        <?php echo htmlspecialchars($appointment['diagnosis']); ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($appointment['treatment']): ?>
                                    <div class="report-field">
                                        <span class="report-label">Treatment:</span>
                                        <?php echo nl2br(htmlspecialchars($appointment['treatment'])); ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($appointment['report']): ?>
                                    <div class="report-field">
                                        <span class="report-label">Report:</span>
                                        <?php echo nl2br(htmlspecialchars($appointment['report'])); ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($appointment['followup_date']): ?>
                                    <div class="report-field">
                                        <span class="report-label">Follow-up Date:</span>
                                        <?php echo htmlspecialchars($appointment['followup_date']); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-reports">
                                    <p>No medical reports available for this patient.</p>
                                </div>
                            <?php endif; ?>
                            
                            <div style="margin-top: 30px; text-align: center;">
                                <a href="patient.php"><button class="login-btn btn-primary-soft btn">Back to Patients List</button></a>
                            </div>
                        </div>
                        <?php else: ?>
                        <div style="text-align: center; padding: 50px;">
                            <img src="../img/notfound.svg" width="25%">
                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Patient not found!</p>
                            <a href="patient.php"><button class="login-btn btn-primary-soft btn">Back to Patients List</button></a>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>