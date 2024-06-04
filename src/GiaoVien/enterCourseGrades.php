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

//Lấy danh sách lớp
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
                            <th scope="col">Điểm tiểu thi</th>
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
                                    <input type="number" name="grades[<?php echo $student->MaSV; ?>]" value="<?php echo htmlspecialchars($student->Diem); ?>" step="0.1" min="0" max="10" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="grades[<?php echo $student->MaSV; ?>]" value="<?php echo htmlspecialchars($student->Diem); ?>" step="0.1" min="0" max="10" class="form-control">
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Lưu điểm</button>
            </form>
        <?php endif; ?>
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <?php include __DIR__ . "/../Shared/footer.php"; ?>




