<?php
include 'D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\ConnectSQL\connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];  // "GiaoVien" hoặc "SinhVien"

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (:username, :password, :email, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
      echo "<script>
              alert('Đăng ký thành công!');
              window.location.href='\skydash\src\login.php';
            </script>";
    } else {
      echo "<script>
              alert('Lỗi: Đăng ký không thành công.');
              window.location.href='register.php';
            </script>";
    }

    $stmt->close();
    $conn->close();
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
  <link rel="shortcut icon" href="/skydash/images/favicon.png" />
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
              <h4>Học viên mới?</h4>
              <h6 class="font-weight-light">Đăng ký dễ dàng, chỉ tốn vài bước!</h6>
              <form class="pt-3" method="post" action="">
                <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-lg" id="exampleInputUsername1" placeholder="Tên đăng nhập" required>
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Mật khẩu" required>
                </div>
                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email" required>
                </div>
                <div class="form-group">
                  <select class="form-control form-control-lg" id="exampleFormControlSelect2" name="role">
                    <option value="SinhVien">Sinh viên</option>
                    <option value="GiaoVien">Giáo viên</option>
                  </select>
                </div>
                <div class="mt-3">
                  <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" value="Đăng ký">
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Đã có tài khoản? <a href="login.html" class="text-primary">Đăng nhập</a>
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
