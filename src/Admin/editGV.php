<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$maGiaoVien = isset($_GET['MaGiaoVien']) ? $_GET['MaGiaoVien'] : null;

if (!$maGiaoVien) {
    $_SESSION['error'] = 'Không tìm thấy giáo viên!';
    header("Location: mainGV.php");
    exit();
}

// Lấy thông tin giáo viên từ database
$sql = "SELECT MaGiaoVien, TenGiaoVien, DiaChi, DienThoai, MaKhoa FROM GiaoVien WHERE MaGiaoVien = :maGiaoVien";
$stm = $conn->prepare($sql);
$stm->bindParam(':maGiaoVien', $maGiaoVien);
$stm->execute();
$teacher = $stm->fetch(PDO::FETCH_OBJ);

if (!$teacher) {
    $_SESSION['error'] = 'Không tìm thấy giáo viên!';
    header("Location: mainGV.php");
    exit();
}

// Lấy danh sách khoa
$sql2 = "SELECT MaKhoa, TenKhoa FROM Khoa";
$stm2 = $conn->prepare($sql2);
$stm2->execute();
$khoaList = $stm2->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenGiaoVien = $_POST['TenGiaoVien'];
    $diaChi = $_POST['DiaChi'];
    $dienThoai = $_POST['DienThoai'];
    $maKhoa = $_POST['MaKhoa'];

    // Cập nhật thông tin giáo viên
    $sql_update = "UPDATE GiaoVien SET TenGiaoVien = :tenGiaoVien, DiaChi = :diaChi, DienThoai = :dienThoai, MaKhoa = :maKhoa WHERE MaGiaoVien = :maGiaoVien";
    $stm_update = $conn->prepare($sql_update);
    $stm_update->bindParam(':tenGiaoVien', $tenGiaoVien);
    $stm_update->bindParam(':diaChi', $diaChi);
    $stm_update->bindParam(':dienThoai', $dienThoai);
    $stm_update->bindParam(':maKhoa', $maKhoa);
    $stm_update->bindParam(':maGiaoVien', $maGiaoVien);

    if ($stm_update->execute()) {
        $_SESSION['success'] = 'Cập nhật giáo viên thành công!';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại!';
    }

    header("Location: mainGV.php");
    exit();
}
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Sửa thông tin giáo viên</h1><a href="mainGV.php" class="btn btn-primary mb-3">Trở lại</a>
    <hr>
    <form method="post" action="">
        <div class="form-group">
            <label for="TenGiaoVien">Tên giáo viên</label>
            <input type="text" name="TenGiaoVien" id="TenGiaoVien" class="form-control" value="<?php echo htmlspecialchars($teacher->TenGiaoVien); ?>" required>
        </div>
        <div class="form-group">
            <label for="DiaChi">Địa chỉ</label>
            <input type="text" name="DiaChi" id="DiaChi" class="form-control" value="<?php echo htmlspecialchars($teacher->DiaChi); ?>" required>
        </div>
        <div class="form-group">
            <label for="DienThoai">Điện thoại</label>
            <input type="text" name="DienThoai" id="DienThoai" class="form-control" value="<?php echo htmlspecialchars($teacher->DienThoai); ?>" required>
        </div>
        <div class="form-group">
            <label for="MaKhoa">Khoa</label>
            <select name="MaKhoa" id="MaKhoa" class="form-control" required>
                <?php foreach ($khoaList as $khoa): ?>
                    <option value="<?php echo $khoa->MaKhoa; ?>" <?php echo ($khoa->MaKhoa == $teacher->MaKhoa) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($khoa->TenKhoa); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
<?php include __DIR__ . '/../Shared/footer.php'; ?>
