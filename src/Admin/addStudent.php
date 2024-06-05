<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Fetch classes from the database
$sql = "SELECT MaLop, TenLop FROM Lop";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lops = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_sv = $_POST['ma_sv'];
    $ten_sv = $_POST['ten_sv'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $que_quan = $_POST['que_quan'];
    $trang_thai = $_POST['trang_thai'];
    $ma_lop = $_POST['ma_lop']; // Đảm bảo ma_lop được gửi qua form
    $password_hash = 123;// Mã hóa mật khẩu mặc định là mã sinh viên
    $email = $ma_sv . "@example.com"; // Tạo email mặc định từ mã sinh viên

    // Bắt đầu transactionS
    $conn->beginTransaction();
    
    try {
        // Thêm vào bảng SinhVien
        $sql = "INSERT INTO SinhVien (MaSV, TenSV, GioiTinh, NgaySinh, QueQuan, TrangThai) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv, $ten_sv, $gioi_tinh, $ngay_sinh, $que_quan, $trang_thai]);
        
        // Thêm vào bảng SinhVien_Lop
        $sql = "INSERT INTO SinhVien_Lop (MaSV, MaLop) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv, $ma_lop]);
        
        // Thêm vào bảng UsersSinhVien
        $sql = "INSERT INTO UsersSinhVien (username, password_hash, email, role) VALUES (?, ?, ?, 'SinhVien')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_sv, $password_hash, $email]);
        
        // Commit transaction
        $conn->commit();
        echo "Thêm sinh viên và tài khoản thành công!";
    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi
        $conn->rollBack();
        echo "Lỗi khi thêm vào cơ sở dữ liệu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sinh viên</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Thêm sinh viên</h1>
        <form method="POST">
            <div class="form-group">
                <label for="ma_sv">Mã sinh viên:</label>
                <input type="text" class="form-control" name="ma_sv" id="ma_sv" required>
            </div>
            <div class="form-group">
                <label for="ten_sv">Tên sinh viên:</label>
                <input type="text" class="form-control" name="ten_sv" id="ten_sv" required>
            </div>
            <div class="form-group">
                <label for="gioi_tinh">Giới tính:</label>
                <input type="text" class="form-control" name="gioi_tinh" id="gioi_tinh" required>
            </div>
            <div class="form-group">
                <label for="ngay_sinh">Ngày sinh:</label>
                <input type="date" class="form-control" name="ngay_sinh" id="ngay_sinh" required>
            </div>
            <div class="form-group">
                <label for="que_quan">Quê quán:</label>
                <input type="text" class="form-control" name="que_quan" id="que_quan" required>
            </div>
            <div class="form-group">
                <label for="trang_thai">Trạng thái:</label>
                <input type="text" class="form-control" name="trang_thai" id="trang_thai" required>
            </div>
            <div class="form-group">
                <label for="ma_lop">Mã lớp:</label>
                <select class="form-control" name="ma_lop" id="ma_lop" required>
                    <?php foreach ($lops as $lop): ?>
                        <option value="<?= $lop['MaLop'] ?>"><?= $lop['TenLop'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
        </form>
        <a href="studentList.php" class="btn btn-secondary mt-3">Quay lại danh sách sinh viên</a>
    </div>
</body>
</html>
