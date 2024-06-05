<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Lấy user_id từ session
$username = $_SESSION['username'];

// Lấy danh sách lớp
$sql = "SELECT Lop.MaLop, Lop.TenLop
        FROM GiaoVien, Lop
        WHERE GiaoVien.MaGiaoVien = :username
        AND GiaoVien.MaGiaoVien = Lop.MaGiaoVien";
$stm = $conn->prepare($sql);
$stm->bindParam(':username', $username);
$stm->execute();
$data = $stm->fetchAll(PDO::FETCH_OBJ);

$selectedClass = isset($_POST['class']) ? $_POST['class'] : null;
$students = [];

if ($selectedClass) {
    // Truy vấn danh sách sinh viên của lớp được chọn
    $sql2 = "SELECT SinhVien.MaSV, SinhVien.TenSV
             FROM SinhVien, SinhVien_Lop, Lop
             WHERE Lop.MaLop = :selectedClass
             AND SinhVien_Lop.MaLop = Lop.MaLop
             AND SinhVien.MaSV = SinhVien_Lop.MaSV";
    $stm2 = $conn->prepare($sql2);
    $stm2->bindParam(':selectedClass', $selectedClass);
    $stm2->execute();
    $students = $stm2->fetchAll(PDO::FETCH_OBJ);
}

// Kiểm tra nếu có thông báo thành công trong session
$grades_saved = isset($_SESSION['grades_saved']) ? $_SESSION['grades_saved'] : false;
if ($grades_saved) {
    // Xóa thông báo thành công để không hiển thị lại khi refresh trang
    unset($_SESSION['grades_saved']);
}

// Đóng kết nối
$stm = null;
$stm2 = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
      <!-- partial -->
      <div class="container mt-5">
        <h1 class="display-4">Nhập điểm sinh viên</h1>
        <hr>
        <form method="post" action="">
            <div class="form-group">
                <label for="class">Chọn lớp:</label>
                <select name="class" id="class" class="form-control" onchange="this.form.submit()">
                    <option value="">Chọn lớp</option>
                    <?php
                    foreach($data as $item) {
                        $selected = ($item->MaLop == $selectedClass) ? 'selected' : '';
                        echo "<option value='{$item->MaLop}' {$selected}>{$item->TenLop}</option>";
                    }
                    ?>
                </select>
            </div>
        </form>

        <?php if ($selectedClass && $students): ?>
            <form method="post" action="saveGrades.php">
                <input type="hidden" name="class" value="<?php echo htmlspecialchars($selectedClass); ?>">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Mã sinh viên</th>
                            <th scope="col">Tên sinh viên</th>
                            <th scope="col">Điểm tiểu luận</th>
                            <th scope="col">Điểm thi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($students as $student) {
                            ?>
                            <tr>
                                <td><?php echo $student->MaSV; ?></td>
                                <td><?php echo $student->TenSV; ?></td>
                                <td>
                                    <input type="number" name="grades[<?php echo $student->MaSV; ?>][DiemTieuLuan]" step="0.1" min="0" max="10" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="grades[<?php echo $student->MaSV; ?>][DiemThi]" step="0.1" min="0" max="10" class="form-control">
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Lưu điểm</button>
            </form>
            <div>
                <form action="uploadGrades.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file">Chọn Excel file:</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xls,.xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </form>
            </div>
            <!-- Form for uploading Excel file -->
            
        <?php endif; ?>
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

<<<<<<< HEAD
<?php include __DIR__ . '/../Shared/footer.php'; ?>
=======
  <?php include __DIR__ . "/../Shared/footer.php"; ?>



>>>>>>> 2be9912e86d9bb1195d23ebae33ea0371c1241cb

<!-- Add JavaScript for Popup Notification -->
<?php if ($grades_saved): ?>
<script>
    alert("Điểm đã được lưu thành công!");
</script>
<?php endif; ?>
