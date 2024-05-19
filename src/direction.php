<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
echo $_SESSION['role'];
if ($_SESSION['role'] == 'teacher') {
    header("Location: dashboardGV.php");
    exit();
} else if ($_SESSION['role'] == 'student') {
    header("Location: dashboardSV.php");
    exit();
} else if ($_SESSION['role'] == 'admin') {
    header("Location: dashboardAdmin.php");
    exit();
}
 else {
    echo "Invalid role!";
    exit();
}
?>