<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Thiết lập phân trang
$limit = 5; // Số lượng môn học hiển thị trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Lấy danh sách môn học
$sql = "SELECT * FROM MonHoc LIMIT :start, :limit";
$stm = $conn->prepare($sql);
$stm->bindParam(':start', $start, PDO::PARAM_INT);
$stm->bindParam(':limit', $limit, PDO::PARAM_INT);
$stm->execute();
$monHoc = $stm->fetchAll(PDO::FETCH_OBJ);

// Lấy tổng số lượng môn học để tính toán số trang
$sql_count = "SELECT COUNT(*) as count FROM MonHoc";
$stm_count = $conn->prepare($sql_count);
$stm_count->execute();
$total_mon = $stm_count->fetch(PDO::FETCH_OBJ)->count;
$total_pages = ceil($total_mon / $limit);

// Đóng kết nối
$stm = null;
$stm_count = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Danh sách môn học</h1><a href="themMH.php" class="btn btn-primary mb-3">Thêm môn học mới</a>
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
                <th>Mã môn học</th>
                <th>Tên môn học</th>
                <th>Số tín chỉ</th>
                <th>Loại môn học</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monHoc as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item->MaMH); ?></td>
                    <td><?php echo htmlspecialchars($item->TenMH); ?></td>
                    <td><?php echo htmlspecialchars($item->SoTinChi); ?></td>
                    <td><?php echo htmlspecialchars($item->LoaiMonHoc); ?></td>
                    <td>
                        <a href="moLop.php?MaMH=<?php echo htmlspecialchars($item->MaMH); ?>" class="btn btn-warning">Mở lớp</a>
                        <a href="editMonHoc.php?MaMH=<?php echo htmlspecialchars($item->MaMH); ?>" class="btn btn-secondary">Chỉnh sửa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="dashboardMonHoc.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include __DIR__ . '/../Shared/footer.php'; ?>
