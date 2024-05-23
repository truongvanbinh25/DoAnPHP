<?php
include 'D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\ConnectSQL\connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST['username'] ?? '';
    $password_input = $_POST['password'] ?? '';

    $sql = "SELECT user_id, password_hash, role FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username_input, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the result
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_id = $row['user_id'];
        $password = $row['password_hash'];
        $role = $row['role'];

        if ($password == $password_input) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username_input;
            $_SESSION['role'] = $role;

            header("Location: direction.php");
            exit(); // Ensure no further code is executed after redirect
        } else {
            echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng!');</script>";
        }
    } else {
        echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng!');</script>";
    }

    // Close the connection
    $stmt = null;
    $conn = null;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="/skydash/vendors/feather/feather.css">
  <link rel="stylesheet" href="/skydash/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="/skydash/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="/skydash/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="/skydash/images/favicon.png">
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="/skydash/images/logo.svg" alt="logo">
              </div>
              <h4>Chào mừng học viên!</h4>
              <h6 class="font-weight-light">Đăng nhập để tiếp tục.</h6>
              <form class="pt-3" method="post" action="login.php">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="username" id="exampleInputEmail1" placeholder="Username" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" id="exampleInputPassword1" placeholder="Password" required>
                </div>
                <div class="mt-3">
                  <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" value="Đăng nhập">
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Giữ tôi đăng nhập
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Quên mật khẩu?</a>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Không có tài khoản? <a href="register.html" class="text-primary">Đăng ký</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="/skydash/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="/skydash/js/off-canvas.js"></script>
  <script src="/skydash/js/hoverable-collapse.js"></script>
  <script src="/skydash/js/template.js"></script>
  <script src="/skydash/js/settings.js"></script>
  <script src="/skydash/js/todolist.js"></script>
  <!-- endinject -->
</body>
</html>
