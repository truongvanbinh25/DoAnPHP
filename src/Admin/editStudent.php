<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['ma_sv'])) {
    $ma_sv = $_GET['ma_sv'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ten_sv = $_POST['ten_sv'];
        $gioi_tinh = $_POST['gioi_tinh'];
        $ngay_sinh = $_POST['ngay_sinh'];
        $que_quan = $_POST['que_quan'];
        $trang_thai = $_POST['trang_thai'];

        $sql = "UPDATE SinhVien SET TenSV = ?, GioiTinh = ?, NgaySinh = ?, QueQuan = ?, TrangThai = ? WHERE MaSV = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$ten_sv, $gioi_tinh, $ngay_sinh, $que_quan, $trang_thai, $ma_sv])) {
            echo "Cập nhật thành công!";
        } else {
            echo "Lỗi khi cập nhật: " . $stmt->errorInfo()[2];
        }
    }

    $sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ma_sv]);
    $sv = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$sv) {
        echo "Không tìm thấy sinh viên.";
        exit();
    }
} else {
    echo "Không có mã sinh viên.";
    exit();
}

// Đóng kết nối
$stmt = null;
$conn = null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin sinh viên</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Sửa thông tin sinh viên</h1>
        <form method="POST">
            <div class="form-group">
                <label for="ma_sv">Mã sinh viên:</label>
                <input type="text" class="form-control" name="ma_sv" id="ma_sv" value="<?php echo htmlspecialchars($sv->MaSV); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="ten_sv">Tên sinh viên:</label>
                <input type="text" class="form-control" name="ten_sv" id="ten_sv" value="<?php echo htmlspecialchars($sv->TenSV); ?>" required>
            </div>
            <div class="form-group">
                <label for="gioi_tinh">Giới tính:</label>
                <input type="text" class="form-control" name="gioi_tinh" id="gioi_tinh" value="<?php echo htmlspecialchars($sv->GioiTinh); ?>" required>
            </div>
            <div class="form-group">
                <label for="ngay_sinh">Ngày sinh:</label>
                <input type="date" class="form-control" name="ngay_sinh" id="ngay_sinh" value="<?php echo htmlspecialchars($sv->NgaySinh); ?>" required>
            </div>
            <div class="form-group">
                <label for="que_quan">Quê quán:</label>
                <input type="text" class="form-control" name="que_quan" id="que_quan" value="<?php echo htmlspecialchars($sv->QueQuan); ?>" required>
            </div>
            <div class="form-group">
                <label for="trang_thai">Trạng thái:</label>
                <input type="text" class="form-control" name="trang_thai" id="trang_thai" value="<?php echo htmlspecialchars($sv->TrangThai); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
        <a href="studentList.php" class="btn btn-secondary mt-3">Quay lại danh sách sinh viên</a>
        <a href="../logout.php" class="btn btn-danger mt-3">Đăng xuất</a>
    </div>
</body>
</html>
