<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

if (isset($_GET['MaGiaoVien'])) {
    $maGiaoVien = $_GET['MaGiaoVien'];

    // Thực hiện truy vấn xóa giáo viên
    $sql = "DELETE FROM GiaoVien WHERE MaGiaoVien = :maGiaoVien";
    $stm = $conn->prepare($sql);
    $stm->bindParam(':maGiaoVien', $maGiaoVien);

    if ($stm->execute()) {
        $_SESSION['success'] = 'Xóa giáo viên thành công!';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại!';
    }

    // Đóng kết nối
    $stm = null;
    $conn = null;

    // Chuyển hướng về trang danh sách giáo viên
    header("Location: mainGV.php");
    exit();
} else {
    $_SESSION['error'] = 'Không tìm thấy giáo viên!';
    header("Location: mainGV.php");
    exit();
}
?>
