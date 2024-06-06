<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenMH = $_POST['TenMonHoc'];
    $stc = $_POST['SoTinChi'];
    $loaiMH = $_POST['LoaiMonHoc'];
    $trangThai = $_POST['TrangThai'];

    // Kiểm tra trùng tên môn học
    $sql_check_mgv = "SELECT COUNT(*) as count FROM MonHoc WHERE TenMH = :tenMH";
    $stm_check_mgv = $conn->prepare($sql_check_mgv);
    $stm_check_mgv->bindParam(':tenMH', $tenMH);
    $stm_check_mgv->execute();
    $count_mgv = $stm_check_mgv->fetch(PDO::FETCH_OBJ)->count;

    if ($count_mgv > 0) {
        $_SESSION['error'] = 'Tên môn học đã tồn tại!';
    } else {
        // Thêm giáo viên mới
        $sql_insert = "INSERT INTO MonHoc (TenMH, SoTinChi, LoaiMonHoc, TrangThai) VALUES (:tenMH, :stc, :loaiMH, :trangThai)";
        $stm_insert = $conn->prepare($sql_insert);
        $stm_insert->bindParam(':tenMH', $tenMH);
        $stm_insert->bindParam(':stc', $stc);
        $stm_insert->bindParam(':loaiMH', $loaiMH);
        $stm_insert->bindParam(':trangThai', $trangThai);

        if ($stm_insert->execute()) {
            $_SESSION['success'] = 'Thêm môn học thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại!';
        }

        header("Location: dashboardMonHoc.php");
        exit();
    }
}
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Thêm môn học</h1>
    <hr>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="TenMonHoc">Tên môn học</label>
            <input type="text" name="TenMonHoc" id="TenMonHoc" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="SoTinChi">Số tín chỉ</label>
            <select name="SoTinChi" id="SoTinChi" class="form-control" required>
                <option value="0">0 tín chỉ</option>
                <option value="1">1 tín chỉ</option>
                <option value="2">2 tín chỉ</option>
                <option value="3">3 tín chỉ</option>
                <option value="15">15 tín chỉ</option>
                <option value="17">17 tín chỉ</option>
                <option value="21">21 tín chỉ</option>
            </select>
        </div>
        <div class="form-group">
            <label for="LoaiMonHoc">Loại môn học</label>
            <select name="LoaiMonHoc" id="LoaiMonHoc" class="form-control" required>
                <option value="Bắt buộc">Bắt buộc</option>
                <option value="Tự chọn">Tự chọn</option>
            </select>
        </div>
        <div class="form-group">
            <label for="TrangThai">Trạng thái</label>
            <select name="Trangthai" id="TrangThai" class="form-control" required>
                <option value="Hoạt động">Hoạt động</option>
                <option value="Khác">Khác</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm môn học</button>
    </form>
</div>
<?php include __DIR__ . '/../Shared/footer.php'; ?>
