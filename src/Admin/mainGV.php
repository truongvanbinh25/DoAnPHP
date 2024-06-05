<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Thiết lập phân trang
$limit = 5; // Số lượng giáo viên hiển thị trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Lấy danh sách giáo viên
$sql = "SELECT MaGiaoVien, TenGiaoVien, DiaChi, DienThoai, MaKhoa FROM GiaoVien LIMIT :start, :limit";
$stm = $conn->prepare($sql);
$stm->bindParam(':start', $start, PDO::PARAM_INT);
$stm->bindParam(':limit', $limit, PDO::PARAM_INT);
$stm->execute();
$teachers = $stm->fetchAll(PDO::FETCH_OBJ);

// Lấy tổng số lượng giáo viên để tính toán số trang
$sql_count = "SELECT COUNT(*) as count FROM GiaoVien";
$stm_count = $conn->prepare($sql_count);
$stm_count->execute();
$total_teachers = $stm_count->fetch(PDO::FETCH_OBJ)->count;
$total_pages = ceil($total_teachers / $limit);

// Đóng kết nối
$stm = null;
$stm_count = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Danh sách giáo viên</h1><a href="themGV.php" class="btn btn-primary mb-3">Thêm giáo viên mới</a>
    <a href="dangkiGV.php" class="btn btn-secondary mb-3">Đăng ký tài khoản giáo viên</a>
    <hr>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã giáo viên</th>
                <th>Tên giáo viên</th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>Mã khoa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?php echo htmlspecialchars($teacher->MaGiaoVien); ?></td>
                    <td><?php echo htmlspecialchars($teacher->TenGiaoVien); ?></td>
                    <td><?php echo htmlspecialchars($teacher->DiaChi); ?></td>
                    <td><?php echo htmlspecialchars($teacher->DienThoai); ?></td>
                    <td><?php echo htmlspecialchars($teacher->MaKhoa); ?></td>
                    <td>
                        <a href="editGV.php?MaGiaoVien=<?php echo htmlspecialchars($teacher->MaGiaoVien); ?>" class="btn btn-warning">Sửa</a>
                        <a href="xoaGV.php?MaGiaoVien=<?php echo htmlspecialchars($teacher->MaGiaoVien); ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa giáo viên này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="mainGV.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include __DIR__ . '/../Shared/footer.php'; ?>
