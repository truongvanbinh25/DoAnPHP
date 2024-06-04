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

// Truy vấn thông tin sinh viên dựa trên user_id
$sql = "SELECT SinhVien.MaSV, SinhVien.TenSV, SinhVien.GioiTinh, SinhVien.NgaySinh, SinhVien.QueQuan, SinhVien.TrangThai, Lop.TenLop
        FROM SinhVien, SinhVien_Lop, Lop 
        WHERE SinhVien.MaSV = SinhVien_Lop.MaSV and SinhVien_Lop.MaLop = Lop.MaLop and SinhVien.MaSV = :username";
$stm = $conn->prepare($sql);
$stm->bindParam(':username', $username);
$stm->execute();

$data = $stm->fetch(PDO::FETCH_OBJ);

// Đóng kết nối
$stm = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerSV.php"; ?>
      <!-- partial -->
      <div class="container mt-5">
        <div class="row">
            <div class="col-md-2">
                <img src="\skydash\images\faces\face1.jpg" alt="Avatar của sinh viên" class="img-fluid rounded-circle mb-3">
            </div>
            <div class="col-md-10">
                <h1 class="display-4">Thông tin sinh viên</h1>
                <hr>
                <div class="mb-3">
                    <h2 class="h5">Mã sinh viên: <span class="font-weight-normal"><?php echo $data->MaSV ?></span></h2>
                </div>
                <div class="mb-3">
                    <h2 class="h5">Tên sinh viên: <span class="font-weight-normal"><?php echo $data->TenSV ?></span></h2>
                </div>
                <div class="mb-3">
                    <h2 class="h5">Lớp: <span class="font-weight-normal"><?php echo $data->TenLop ?></span></h2>
                </div>
                <div class="mb-3">
                    <h2 class="h5">Ngày sinh: <span class="font-weight-normal"><?php echo $data->NgaySinh ?></span></h2>
                </div>
                <div class="mb-3">
                    <h2 class="h5">Giới tính: <span class="font-weight-normal"><?php echo $data->GioiTinh ?></span></h2>
                </div>
                <div class="mb-3">
                    <h2 class="h5">Quê quán: <span class="font-weight-normal"><?php echo $data->QueQuan ?></span></h2>
                </div>
                <div class="mb-3">
                    <h2 class="h5">Trạng thái: <span class="font-weight-normal"><?php echo $data->TrangThai ?></span></h2>
                </div>
            </div>
        </div>
      </div>

      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <?php include __DIR__ . "/../Shared/footer.php"; ?>


