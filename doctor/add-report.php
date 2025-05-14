<?php
session_start();
if(!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'd') {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

// التحقق من وجود معرف الموعد
if(!isset($_GET['appoid']) || empty($_GET['appoid'])) {
    header("location: treated-patients.php");
    exit();
}

$appoid = $_GET['appoid'];

// جلب بيانات الموعد والمريض
$sql = "SELECT a.*, p.pname, p.pemail, s.title as session_title
        FROM appointment a
        JOIN patient p ON a.pid = p.pid
        JOIN schedule s ON a.scheduleid = s.scheduleid
        WHERE a.appoid = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $appoid);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();

if(!$appointment) {
    header("location: treated-patients.php");
    exit();
}

// معالجة إرسال التقرير
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $report = $_POST['report'];
    $followup_date = $_POST['followup_date'];
    
    $updateSql = "UPDATE appointment SET 
                 diagnosis = ?, treatment = ?, report = ?, followup_date = ?
                 WHERE appoid = ?";
    $stmt = $database->prepare($updateSql);
    $stmt->bind_param("ssssi", $diagnosis, $treatment, $report, $followup_date, $appoid);
    
    if($stmt->execute()) {
        $_SESSION['report_success'] = "Report saved successfully";
        header("location: treated-patients.php");
        exit();
    } else {
        $error = "Error saving report";
    }
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
    <title>Medical Report</title>
    <style>
        .report-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            min-height: 200px;
        }
        .btn-submit {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #27ae60;
        }
        .patient-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <!-- نفس قائمة التنقل السابقة -->
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="treated-patients.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Medical Report</p>
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
                        <center>
                            <div class="report-container">
                                <h2>Medical Report for <?php echo htmlspecialchars($appointment['pname']); ?></h2>
                                
                                <?php if(isset($error)): ?>
                                    <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="patient-info">
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($appointment['pemail']); ?></p>
                                    <p><strong>Session:</strong> <?php echo htmlspecialchars($appointment['session_title']); ?></p>
                                    <p><strong>Appointment Date:</strong> <?php echo $appointment['appodate']; ?></p>
                                </div>
                                
                                <form method="post">
                                    <div class="form-group">
                                        <label for="diagnosis">Diagnosis:</label>
                                        <input type="text" id="diagnosis" name="diagnosis" 
                                               value="<?php echo htmlspecialchars($appointment['diagnosis'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="treatment">Treatment Plan:</label>
                                        <textarea id="treatment" name="treatment"><?php 
                                            echo htmlspecialchars($appointment['treatment'] ?? ''); 
                                        ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="report">Detailed Report:</label>
                                        <textarea id="report" name="report"><?php 
                                            echo htmlspecialchars($appointment['report'] ?? ''); 
                                        ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="followup_date">Follow-up Date:</label>
                                        <input type="date" id="followup_date" name="followup_date"
                                               value="<?php echo htmlspecialchars($appointment['followup_date'] ?? ''); ?>">
                                    </div>
                                    
                                    <button type="submit" class="btn-submit">Save Report</button>
                                </form>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>