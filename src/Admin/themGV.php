<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy danh sách khoa để hiển thị trong combobox
$sql = "SELECT MaKhoa, TenKhoa FROM Khoa";
$stm = $conn->prepare($sql);
$stm->execute();
$khoaList = $stm->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maGiaoVien = $_POST['MaGiaoVien'];
    $tenGiaoVien = $_POST['TenGiaoVien'];
    $diaChi = $_POST['DiaChi'];
    $dienThoai = $_POST['DienThoai'];
    $maKhoa = $_POST['MaKhoa'];

    // Kiểm tra trùng mã giáo viên
    $sql_check_mgv = "SELECT COUNT(*) as count FROM GiaoVien WHERE MaGiaoVien = :maGiaoVien";
    $stm_check_mgv = $conn->prepare($sql_check_mgv);
    $stm_check_mgv->bindParam(':maGiaoVien', $maGiaoVien);
    $stm_check_mgv->execute();
    $count_mgv = $stm_check_mgv->fetch(PDO::FETCH_OBJ)->count;

    // Kiểm tra trùng tên giáo viên
    $sql_check_tgv = "SELECT COUNT(*) as count FROM GiaoVien WHERE TenGiaoVien = :tenGiaoVien";
    $stm_check_tgv = $conn->prepare($sql_check_tgv);
    $stm_check_tgv->bindParam(':tenGiaoVien', $tenGiaoVien);
    $stm_check_tgv->execute();
    $count_tgv = $stm_check_tgv->fetch(PDO::FETCH_OBJ)->count;

    if ($count_mgv > 0) {
        $_SESSION['error'] = 'Mã giáo viên đã tồn tại!';
    } elseif ($count_tgv > 0) {
        $_SESSION['error'] = 'Tên giáo viên đã tồn tại!';
    } else {
        // Thêm giáo viên mới
        $sql_insert = "INSERT INTO GiaoVien (MaGiaoVien, TenGiaoVien, DiaChi, DienThoai, MaKhoa) VALUES (:maGiaoVien, :tenGiaoVien, :diaChi, :dienThoai, :maKhoa)";
        $stm_insert = $conn->prepare($sql_insert);
        $stm_insert->bindParam(':maGiaoVien', $maGiaoVien);
        $stm_insert->bindParam(':tenGiaoVien', $tenGiaoVien);
        $stm_insert->bindParam(':diaChi', $diaChi);
        $stm_insert->bindParam(':dienThoai', $dienThoai);
        $stm_insert->bindParam(':maKhoa', $maKhoa);

        if ($stm_insert->execute()) {
            $_SESSION['success'] = 'Thêm giáo viên thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại!';
        }

        header("Location: mainGV.php");
        exit();
    }
}
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Thêm giáo viên</h1>
    <hr>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="MaGiaoVien">Mã giáo viên</label>
            <input type="text" name="MaGiaoVien" id="MaGiaoVien" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="TenGiaoVien">Tên giáo viên</label>
            <input type="text" name="TenGiaoVien" id="TenGiaoVien" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="DiaChi">Địa chỉ</label>
            <input type="text" name="DiaChi" id="DiaChi" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="DienThoai">Điện thoại</label>
            <input type="text" name="DienThoai" id="DienThoai" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="MaKhoa">Khoa</label>
            <select name="MaKhoa" id="MaKhoa" class="form-control" required>
                <?php foreach ($khoaList as $khoa): ?>
                    <option value="<?php echo $khoa->MaKhoa; ?>"><?php echo htmlspecialchars($khoa->TenKhoa); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm giáo viên</button>
    </form>
</div>
<?php include __DIR__ . '/../Shared/footer.php'; ?>
