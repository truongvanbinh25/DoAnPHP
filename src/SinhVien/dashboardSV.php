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
$sql = "SELECT MaSV, TenSV, GioiTinh, QueQuan, TrangThai FROM SinhVien WHERE MaSV = :username";
$stm = $conn->prepare($sql);
$stm->bindParam(':username', $username);
$stm->execute();

$data = $stm->fetch(PDO::FETCH_OBJ);

if($data->TrangThai != "Đang học")
{
  $status = addslashes($data->TrangThai);  // addslashes giúp thoát các ký tự đặc biệt
  echo <<<EOT
  <script>
      alert('Sinh viên đã $status !');
      window.location.href = '../login.php';  // Đường dẫn tương đối chính xác
  </script>
  EOT;
}

// Đóng kết nối
$stm = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerSV.php"; ?>
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

        <!-- Box 3 -->
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <a class="card-body" style="text-decoration: none; color:white" href="#">
              <p class="mb-4"></p>
            </a>
          </div>
        </div>

        <!-- Box 4 -->
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <a class="card-body" style="text-decoration: none; color:white" href="#">
              <p class="mb-4"></p>
            </a>
          </div>
        </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../Shared/footer.php"; ?>

