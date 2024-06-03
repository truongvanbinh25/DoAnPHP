<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class = $_POST['class'];
    $grades = $_POST['grades'];

    // Lưu điểm vào cơ sở dữ liệu
    foreach ($grades as $studentId => $grade) {
        $sql = "INSERT INTO Diem (MaSV, MaLop, Diem) VALUES (:studentId, :class, :grade)
                ON DUPLICATE KEY UPDATE Diem = :grade";
        $stm = $conn->prepare($sql);
        $stm->bindParam(':studentId', $studentId);
        $stm->bindParam(':class', $class);
        $stm->bindParam(':grade', $grade);
        $stm->execute();
    }

    // Đóng kết nối
    $stm = null;
    $conn = null;

    // Chuyển hướng về trang nhập điểm
    header("Location: enterCourseGrades.php");
    exit();
} else {
    // Chuyển hướng về trang nhập điểm nếu không phải POST request
    header("Location: enterCourseGrades.php");
    exit();
}
?>
