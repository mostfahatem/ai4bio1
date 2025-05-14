<?php
session_start();

// 1. التحقق من تسجيل الدخول والصحة - تعديل الشرط للسماح للمسؤولين والأطباء
if(!isset($_SESSION["user"]) || $_SESSION["user"] == "" || ($_SESSION['usertype'] != 'a' && $_SESSION['usertype'] != 'd')) {
    header("location: ../login.php");
    exit();
}

// 2. التحقق من وجود ID
if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    $_SESSION['error'] = "معرف الموعد غير صالح";
    header("location: appointment.php");
    exit();
}

// 3. الاتصال بقاعدة البيانات
include("../connection.php");
if($database->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $database->connect_error);
}

// 4. تنفيذ عملية الحذف
$id = intval($_GET["id"]); // تأمين المدخلات
$sql = "DELETE FROM appointment WHERE appoid = ?";
$stmt = $database->prepare($sql);

if(!$stmt) {
    $_SESSION['error'] = "تحضير الاستعلام فشل: " . $database->error;
    header("location: appointment.php");
    exit();
}

$stmt->bind_param("i", $id);
if($stmt->execute()) {
} else {
}

$stmt->close();
$database->close();

// 5. إعادة التوجيه
header("location: appointment.php");
exit();
?>