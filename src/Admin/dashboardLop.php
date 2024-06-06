<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Thiết lập phân trang
$limit = 5; // Số lượng lớp hiển thị trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Lấy danh sách lớp
$sql = "SELECT Lop.*, Khoa.TenKhoa, GiaoVien.TenGiaoVien, MonHoc.TenMH 
        FROM Lop 
        JOIN Khoa ON Lop.MaKhoa = Khoa.MaKhoa
        JOIN GiaoVien ON Lop.MaGiaoVien = GiaoVien.MaGiaoVien
        JOIN MonHoc ON Lop.MaMon = MonHoc.MaMH
        LIMIT :start, :limit";
$stm = $conn->prepare($sql);
$stm->bindParam(':start', $start, PDO::PARAM_INT);
$stm->bindParam(':limit', $limit, PDO::PARAM_INT);
$stm->execute();
$lops = $stm->fetchAll(PDO::FETCH_OBJ);

// Lấy tổng số lượng lớp để tính toán số trang
$sql_count = "SELECT COUNT(*) as count FROM Lop";
$stm_count = $conn->prepare($sql_count);
$stm_count->execute();
$total_lops = $stm_count->fetch(PDO::FETCH_OBJ)->count;
$total_pages = ceil($total_lops / $limit);

// Đóng kết nối
$stm = null;
$stm_count = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Danh sách lớp học</h1>
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
                <th>Mã lớp</th>
                <th>Tên lớp</th>
                <th>Khoa</th>
                <th>Giáo viên</th>
                <th>Môn học</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lops as $lop): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lop->MaLop); ?></td>
                    <td><?php echo htmlspecialchars($lop->TenLop); ?></td>
                    <td><?php echo htmlspecialchars($lop->TenKhoa); ?></td>
                    <td><?php echo htmlspecialchars($lop->TenGiaoVien); ?></td>
                    <td><?php echo htmlspecialchars($lop->TenMH); ?></td>
                    <td><?php echo htmlspecialchars($lop->TrangThai); ?></td>
                    <td>
                        <a href="addStudentToClass.php?MaLop=<?php echo htmlspecialchars($lop->MaLop); ?>" class="btn btn-primary">Thêm sinh viên</a>
                        <a href="viewStudentsInClass.php?MaLop=<?php echo htmlspecialchars($lop->MaLop); ?>" class="btn btn-secondary">Xem sinh viên</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="dashboardLop.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include __DIR__ . '/../Shared/footer.php'; ?>
