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

// Lấy thông tin môn học
$sql = "SELECT * FROM MonHoc WHERE MaMH = :maMH";
$stm = $conn->prepare($sql);
$stm->bindParam(':maMH', $maMH);
$stm->execute();
$monHoc = $stm->fetch(PDO::FETCH_OBJ);

// Kiểm tra xem môn học có tồn tại không
if (!$monHoc) {
    $_SESSION['error'] = "Môn học không tồn tại.";
    header("Location: dashboardMonHoc.php");
    exit();
}

// Lấy danh sách khoa
$sql_khoa = "SELECT * FROM Khoa";
$stm_khoa = $conn->prepare($sql_khoa);
$stm_khoa->execute();
$khoaList = $stm_khoa->fetchAll(PDO::FETCH_OBJ);

// Lấy danh sách giáo viên
$sql_gv = "SELECT * FROM GiaoVien";
$stm_gv = $conn->prepare($sql_gv);
$stm_gv->execute();
$gvList = $stm_gv->fetchAll(PDO::FETCH_OBJ);

// Xử lý tạo lớp
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenLop = $_POST['tenLop'];
    $maKhoa = $_POST['maKhoa'];
    $maGiaoVien = $_POST['maGiaoVien'];

    $sql_create_lop = "INSERT INTO Lop (TenLop, MaKhoa, MaGiaoVien, TrangThai, MaMon) VALUES (:tenLop, :maKhoa, :maGiaoVien, 'Đang học', :maMH)";
    $stm_create_lop = $conn->prepare($sql_create_lop);
    $stm_create_lop->bindParam(':tenLop', $tenLop);
    $stm_create_lop->bindParam(':maKhoa', $maKhoa);
    $stm_create_lop->bindParam(':maGiaoVien', $maGiaoVien);
    $stm_create_lop->bindParam(':maMH', $maMH);
    $stm_create_lop->execute();

    $_SESSION['success'] = "Đã tạo lớp mới.";
    header("Location: dashboardMonHoc.php");
    exit();
}

// Đóng kết nối
$stm = null;
$stm_khoa = null;
$stm_gv = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Mở lớp cho môn học: <?php echo htmlspecialchars($monHoc->TenMH); ?></h1>
    <hr>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="tenLop">Tên lớp:</label>
            <input type="text" name="tenLop" id="tenLop" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="maKhoa">Khoa:</label>
            <select name="maKhoa" id="maKhoa" class="form-control" required>
                <option value="">Chọn khoa</option>
                <?php foreach ($khoaList as $khoa): ?>
                    <option value="<?php echo htmlspecialchars($khoa->MaKhoa); ?>"><?php echo htmlspecialchars($khoa->TenKhoa); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="maGiaoVien">Giáo viên:</label>
            <select name="maGiaoVien" id="maGiaoVien" class="form-control" required>
                <option value="">Chọn giáo viên</option>
                <?php foreach ($gvList as $gv): ?>
                    <option value="<?php echo htmlspecialchars($gv->MaGiaoVien); ?>"><?php echo htmlspecialchars($gv->TenGiaoVien); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mở lớp</button>
    </form>
</div>

<?php include __DIR__ . '/../Shared/footer.php'; ?>
