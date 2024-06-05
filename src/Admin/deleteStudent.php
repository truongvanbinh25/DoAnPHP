<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['ma_sv'])) {
    $ma_sv = $_GET['ma_sv'];

    try {
        // Bắt đầu một giao dịch để đảm bảo tính nhất quán trong cơ sở dữ liệu
        $conn->beginTransaction();

        // Xóa sinh viên từ bảng UsersSinhVien trước
        $sql = "DELETE FROM UsersSinhVien WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv]);

        // Xóa sinh viên từ bảng SinhVien_Lop
        $sql = "DELETE FROM SinhVien_Lop WHERE MaSV = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv]);

        // Xóa điểm của sinh viên từ bảng Diem
        $sql = "DELETE FROM Diem WHERE MaSV = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv]);

        // Xóa sinh viên từ bảng SinhVien
        $sql = "DELETE FROM SinhVien WHERE MaSV = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv]);

        // Hoàn tất giao dịch nếu không có lỗi xảy ra
        $conn->commit();

        // Đóng kết nối
        $stmt = null;
        $conn = null;

        // Hiển thị thông báo xác nhận và chuyển hướng người dùng đến trang studentList.php
        echo "<script>alert('Xóa sinh viên thành công!'); window.location.href = 'studentList.php';</script>";
        exit();
    } catch (PDOException $e) {
        // Nếu có lỗi, rollback giao dịch và hiển thị thông báo lỗi
        $conn->rollBack();
        echo "Lỗi: " . $e->getMessage();
    }
} else {
    // Nếu không có mã sinh viên, chuyển hướng người dùng đến trang studentList.php
    header('Location: studentList.php');
    exit();
}
?>