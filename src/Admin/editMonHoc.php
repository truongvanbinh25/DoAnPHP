<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy mã môn học từ query string
$maMH = isset($_GET['MaMH']) ? $_GET['MaMH'] : null;

// Kiểm tra xem mã môn học có hợp lệ không
if (!$maMH) {
    $_SESSION['error'] = "Mã môn học không hợp lệ.";
    header("Location: dashboardMonHoc.php");
    exit();
}

// Lấy thông tin môn học hiện tại
$sql = "SELECT * FROM MonHoc WHERE MaMH = :maMH";
$stm = $conn->prepare($sql);
$stm->bindParam(':maMH', $maMH, PDO::PARAM_INT);
$stm->execute();
$monHoc = $stm->fetch(PDO::FETCH_OBJ);

// Xử lý cập nhật thông tin môn học
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenMH = $_POST['tenMH'];
    $soTinChi = $_POST['soTinChi'];
    $loaiMonHoc = $_POST['loaiMonHoc'];

    $sql_update = "UPDATE MonHoc SET TenMH = :tenMH, SoTinChi = :soTinChi, LoaiMonHoc = :loaiMonHoc WHERE MaMH = :maMH";
    $stm_update = $conn->prepare($sql_update);
    $stm_update->bindParam(':tenMH', $tenMH);
    $stm_update->bindParam(':soTinChi', $soTinChi);
    $stm_update->bindParam(':loaiMonHoc', $loaiMonHoc);
    $stm_update->bindParam(':maMH', $maMH);

    if ($stm_update->execute()) {
        $_SESSION['success'] = "Cập nhật thông tin môn học thành công.";
    } else {
        $_SESSION['error'] = "Cập nhật thông tin môn học thất bại.";
    }

    header("Location: dashboardMonHoc.php");
    exit();
}

// Đóng kết nối
$stm = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Chỉnh sửa thông tin môn học</h1>
    <hr>
    <form method="post" action="">
        <div class="form-group">
            <label for="tenMH">Tên môn học:</label>
            <input type="text" name="tenMH" id="tenMH" class="form-control" value="<?php echo htmlspecialchars($monHoc->TenMH); ?>" required>
        </div>
        <div class="form-group">
            <label for="soTinChi">Số tín chỉ:</label>
            <input type="number" name="soTinChi" id="soTinChi" class="form-control" value="<?php echo htmlspecialchars($monHoc->SoTinChi); ?>" required>
        </div>
        <div class="form-group">
            <label for="loaiMonHoc">Loại môn học:</label>
            <input type="text" name="loaiMonHoc" id="loaiMonHoc" class="form-control" value="<?php echo htmlspecialchars($monHoc->LoaiMonHoc); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php include __DIR__ . '/../Shared/footer.php'; ?>
