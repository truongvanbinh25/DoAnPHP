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

// Truy vấn thông tin giáo viên dựa trên user_id
$sql = "SELECT GiaoVien.MaGiaoVien, GiaoVien.TenGiaoVien, GiaoVien.DiaChi, GiaoVien.DienThoai, Khoa.TenKhoa
        FROM GiaoVien, Khoa 
        WHERE GiaoVien.MaKhoa = Khoa.MaKhoa and GiaoVien.MaGiaoVien = :username";
$stm = $conn->prepare($sql);
$stm->bindParam(':username', $username);
$stm->execute();
$data = $stm->fetch(PDO::FETCH_OBJ);

// Truy vấn thông tin lớp học
$sql2 = "SELECT MonHoc.MaMH, MonHoc.TenMH, Lop.TenLop
        FROM GiaoVien, Lop, MonHoc 
        WHERE GiaoVien.MaGiaoVien = :username
        AND GiaoVien.MaGiaoVien = Lop.MaGiaoVien and Lop.MaMon = MonHoc.MaMH";
$stm2 = $conn->prepare($sql2);
$stm2->bindParam(':username', $username);
$stm2->execute();
$data2 = $stm2->fetchAll(PDO::FETCH_OBJ);

// Đóng kết nối
$stm = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
      <!-- partial -->
      <div class="container mt-5">
        <img src="\skydash\images\faces\face1.jpg" alt="Avatar của sinh viên" class="img-fluid rounded-circle mb-3">
        <div class="row">
            
                <div class="col-md-10">
                    <h1 class="display-4">Thông tin giáo viên</h1>
                    <hr>
                    <div class="mb-3">
                        <h2 class="h5">Mã giáo viên: <span class="font-weight-normal"><?php echo $data->MaGiaoVien ?></span></h2>
                    </div>
                    <div class="mb-3">
                        <h2 class="h5">Tên giáo viên: <span class="font-weight-normal"><?php echo $data->TenGiaoVien?></span></h2>
                    </div>
                    <div class="mb-3">
                        <h2 class="h5">Địa chỉ: <span class="font-weight-normal"><?php echo $data->DiaChi ?></span></h2>
                    </div>
                    <div class="mb-3">
                        <h2 class="h5">Diện thoại: <span class="font-weight-normal"><?php echo $data->DienThoai ?></span></h2>
                    </div>
                    <div class="mb-3">
                        <h2 class="h5">Khoa: <span class="font-weight-normal"><?php echo $data->TenKhoa ?></span></h2>
                    </div>
                </div>
                <hr>
                <div class="col-md-10">
                    <h1 class="display-4">Thông tin lớp học đang dạy</h1>
                    <hr>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Mã môn học</th>
                                <th scope="col">Tên môn học</th>
                                <th scope="col">Tên lớp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($data2 as $item2) {
                                ?>
                                <tr>
                                    <td><?php echo $item2->MaMH ?></td>
                                    <td><?php echo $item2->TenMH ?></td>
                                    <td><?php echo $item2->TenLop ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
        </div>
      </div>

      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <?php include __DIR__ . "/../Shared/footer.php"; ?>


