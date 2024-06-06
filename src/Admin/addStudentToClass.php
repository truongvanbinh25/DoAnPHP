<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy mã lớp từ query string
$maLop = isset($_GET['MaLop']) ? $_GET['MaLop'] : null;
echo $maLop;
// Kiểm tra xem mã lớp có hợp lệ không
if (!$maLop) {
    $_SESSION['error'] = "Mã lớp không hợp lệ.";
    header("Location: dashboardLop.php");
    exit();
}

// Thiết lập phân trang
$limit = 5; // Số lượng sinh viên hiển thị trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Lấy danh sách sinh viên chưa có trong lớp
$sql_sv = "SELECT * FROM SinhVien 
           WHERE MaSV NOT IN (SELECT MaSV FROM SinhVien_Lop WHERE MaLop = :maLop)
           LIMIT :start, :limit";
$stm_sv = $conn->prepare($sql_sv);
$stm_sv->bindParam(':maLop', $maLop, PDO::PARAM_INT);
$stm_sv->bindParam(':start', $start, PDO::PARAM_INT);
$stm_sv->bindParam(':limit', $limit, PDO::PARAM_INT);
$stm_sv->execute();
$svList = $stm_sv->fetchAll(PDO::FETCH_OBJ);

// Lấy tổng số lượng sinh viên chưa có trong lớp để tính toán số trang
$sql_count = "SELECT COUNT(*) as count FROM SinhVien 
              WHERE MaSV NOT IN (SELECT MaSV FROM SinhVien_Lop WHERE MaLop = :maLop)";
$stm_count = $conn->prepare($sql_count);
$stm_count->bindParam(':maLop', $maLop, PDO::PARAM_INT);
$stm_count->execute();
$total_svs = $stm_count->fetch(PDO::FETCH_OBJ)->count;
$total_pages = ceil($total_svs / $limit);

// Xử lý thêm sinh viên vào lớp
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSV = $_POST['maSV'];
    
    $sql_add_sv = "INSERT INTO SinhVien_Lop (MaSV, MaLop) VALUES (:maSV, :maLop)";
    $stm_add_sv = $conn->prepare($sql_add_sv);
    $stm_add_sv->bindParam(':maSV', $maSV);
    $stm_add_sv->bindParam(':maLop', $maLop);
    $stm_add_sv->execute();

    $_SESSION['success'] = "Đã thêm sinh viên vào lớp.";
    header("Location: addStudentToClass.php?MaLop=$maLop");
    exit();
}

// Đóng kết nối
$stm_sv = null;
$stm_count = null;
$conn = null;
?>

<?php include __DIR__ . "/../Shared/headerGV.php"; ?>
<div class="container mt-5">
    <h1 class="display-4">Thêm sinh viên vào lớp</h1>
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
                <th>Mã sinh viên</th>
                <th>Tên sinh viên</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Quê quán</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($svList as $sv): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sv->MaSV); ?></td>
                    <td><?php echo htmlspecialchars($sv->TenSV); ?></td>
                    <td><?php echo htmlspecialchars($sv->GioiTinh); ?></td>
                    <td><?php echo htmlspecialchars($sv->NgaySinh); ?></td>
                    <td><?php echo htmlspecialchars($sv->QueQuan); ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="maSV" value="<?php echo htmlspecialchars($sv->MaSV); ?>">
                            <button type="submit" class="btn btn-primary">Thêm vào lớp</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="addStudentToClass.php?MaLop=<?php echo htmlspecialchars($maLop); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include __DIR__ . '/../Shared/footer.php'; ?>
