<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Truy vấn danh sách sinh viên
$sql = "SELECT * FROM SinhVien";
if ($search) {
    $sql .= " WHERE TenSV LIKE :search OR MaSV LIKE :search";
}
$stmt = $conn->prepare($sql);
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$sinhViens = $stmt->fetchAll(PDO::FETCH_OBJ);

// Đóng kết nối
$stmt = null;
$conn = null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Danh sách sinh viên</h1>
        <form method="GET" class="form-inline mb-4">
            <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm sinh viên" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
        <a href="addStudent.php" class="btn btn-success mb-4">Thêm sinh viên mới</a>
        <a href="importStudent.php" class="btn btn-info mb-4">Nhập từ Excel</a>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Mã sinh viên</th>
                    <th>Tên sinh viên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Quê quán</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sinhViens as $sv): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sv->MaSV); ?></td>
                    <td><?php echo htmlspecialchars($sv->TenSV); ?></td>
                    <td><?php echo htmlspecialchars($sv->GioiTinh); ?></td>
                    <td><?php echo htmlspecialchars($sv->NgaySinh); ?></td>
                    <td><?php echo htmlspecialchars($sv->QueQuan); ?></td>
                    <td><?php echo htmlspecialchars($sv->TrangThai); ?></td>
                    <td>
                        <a href="editStudent.php?ma_sv=<?php echo htmlspecialchars($sv->MaSV); ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="deleteStudent.php?ma_sv=<?php echo htmlspecialchars($sv->MaSV); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboardGV.php" class="btn btn-secondary">Quay về trang chủ</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
