<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = 'GiaoVien'; // Đây là vai trò mặc định cho giáo viên

    // Mật khẩu mặc định
    $default_password = '123';

    try {
        // Thêm vào cơ sở dữ liệu
        $sql_insert = "INSERT INTO UsersGiaoVien (username, password_hash, email, role) VALUES (:username, :password_hash, :email, :role)";
        $stm_insert = $conn->prepare($sql_insert);
        $stm_insert->bindParam(':username', $username);
        $stm_insert->bindParam(':password_hash', $default_password); // Sử dụng mật khẩu không được mã hóa
        $stm_insert->bindParam(':email', $email);
        $stm_insert->bindParam(':role', $role);

        if ($stm_insert->execute()) {
            $_SESSION['success'] = 'Thêm tài khoản giáo viên thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại!';
        }
    } catch (PDOException $e) {
        // Xử lý lỗi kết nối hoặc truy vấn
        // ...
    }
    header("Location: mainGV.php");
}
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Đăng ký tài khoản giáo viên</h1>
    <hr>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng ký</button>
    </form>
</div>
<?php include __DIR__ . '/../Shared/footer.php'; ?>

<!-- Add JavaScript for Popup Notification -->
<?php if (isset($_SESSION['success'])): ?>
    <script>
    // Kiểm tra nếu có thông báo đăng ký thành công
    var registerSuccess = <?php echo json_encode($register_success); ?>;

    // Nếu có thông báo, hiển thị popup
    if (registerSuccess) {
        alert("Đăng ký tài khoản thành công!");
    }
</script>
<?php endif; ?>
