<?php
session_start();
include 'D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\ConnectSQL\connect.php';

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Lấy user_id từ session
$username = $_SESSION['username'];

// Truy vấn thông tin sinh viên dựa trên user_id
$sql = "SELECT MaSV, TenSV, GioiTinh, QueQuan FROM SinhVien WHERE MaSV = :username";
$stm = $conn->prepare($sql);
$stm->bindParam(':username', $username);
$stm->execute();

$data = $stm->fetch(PDO::FETCH_OBJ);

// Đóng kết nối
$stm = null;
$conn = null;
?>

<?php include "D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\Shared\header.php"; ?>
<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Chào mừng <?php echo $data->TenSV ?></h3>
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
            <a class="card-body" style="text-decoration: none; color:white" href="infomationSV.php">
              <p class="mb-4">Thông tin sinh viên</p>
            </a>
          </div>
        </div>

        <!-- Box 2 -->
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <a class="card-body" style="text-decoration: none; color:white" href="learningOutcomesSV.php">
              <p class="mb-4">Kết quả học tập</p>
            </a>
          </div>
        </div>

      <div class="row">
        <!-- Box 3 -->
        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4">Number of Meetings</p>
              <p class="fs-30 mb-2">34040</p>
              <p>2.00% (30 days)</p>
            </div>
          </div>
        </div>

        <!-- Box 4 -->
        <div class="col-md-6 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Number of Clients</p>
              <p class="fs-30 mb-2">47033</p>
              <p>0.22% (30 days)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\Shared\footer.php'; ?>

