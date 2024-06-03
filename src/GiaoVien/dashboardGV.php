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
$sql = "SELECT TenGiaoVien FROM GiaoVien WHERE MaGiaoVien = :username";
$stm = $conn->prepare($sql);
$stm->bindParam(':username', $username);
$stm->execute();

$data = $stm->fetch(PDO::FETCH_OBJ);

// Đóng kết nối
$stm = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Chào mừng giáo viên <?php echo $data->TenGiaoVien ?></h3>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card tale-bg">
        <div class="card-people mt-auto">
          <img src="/skydash/images/dashboard/people.svg" alt="people">
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin transparent">

      <div class="row">

        <!-- Box 1 -->
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <a class="card-body" style="text-decoration: none; color:white" href="infomationGV.php">
              <p class="mb-4">Thông tin giáo viên</p>
            </a>
          </div>
        </div>

        <!-- Box 2 -->
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <a class="card-body" style="text-decoration: none; color:white" href="enterCourseGrades.php">
              <p class="mb-4">Nhập điểm</p>
            </a>
          </div>
        </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../Shared/footer.php"; ?>

