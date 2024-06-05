<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Lấy dữ liệu từ POST
$grades = isset($_POST['grades']) ? $_POST['grades'] : [];
$selectedClass = isset($_POST['class']) ? $_POST['class'] : null;

if ($selectedClass && $grades) {
    try {
        // Bắt đầu một transaction
        $conn->beginTransaction();

        // Đặt tỷ lệ phần trăm điểm
        $PhanTramDiemTieuLuan = 3;
        $PhanTramDiemThi = 7;

        // Xử lý và lưu điểm vào cơ sở dữ liệu
        foreach ($grades as $MaSV => $grade) {
            $DiemTieuLuan = isset($grade['DiemTieuLuan']) ? $grade['DiemTieuLuan'] : null;
            $DiemThi = isset($grade['DiemThi']) ? $grade['DiemThi'] : null;

            // Tính điểm trung bình
            $DiemTB = ($DiemTieuLuan * $PhanTramDiemTieuLuan / 10) + ($DiemThi * $PhanTramDiemThi / 10);

            // Debugging output
            echo "Processing MaSV: $MaSV, DiemTieuLuan: $DiemTieuLuan, DiemThi: $DiemThi, DiemTB: $DiemTB<br>";

            // Kiểm tra xem điểm đã tồn tại hay chưa
            $sqlCheck = "SELECT * FROM Diem WHERE MaSV = :MaSV AND MaMH = (SELECT MaMon FROM Lop WHERE MaLop = :selectedClass)";
            $stmCheck = $conn->prepare($sqlCheck);
            $stmCheck->bindParam(':MaSV', $MaSV);
            $stmCheck->bindParam(':selectedClass', $selectedClass);
            $stmCheck->execute();
            $existingGrade = $stmCheck->fetch(PDO::FETCH_OBJ);

            if ($existingGrade) {
                // Cập nhật điểm nếu đã tồn tại
                $sqlUpdate = "UPDATE Diem 
                              SET DiemTieuLuan = :DiemTieuLuan, DiemThi = :DiemThi, DiemTB = :DiemTB,
                                  PhanTramDiemTieuLuan = :PhanTramDiemTieuLuan, PhanTramDiemThi = :PhanTramDiemThi 
                              WHERE MaSV = :MaSV AND MaMH = (SELECT MaMon FROM Lop WHERE MaLop = :selectedClass)";
                $stmUpdate = $conn->prepare($sqlUpdate);
                $stmUpdate->bindParam(':DiemTieuLuan', $DiemTieuLuan);
                $stmUpdate->bindParam(':DiemThi', $DiemThi);
                $stmUpdate->bindParam(':DiemTB', $DiemTB);
                $stmUpdate->bindParam(':PhanTramDiemTieuLuan', $PhanTramDiemTieuLuan);
                $stmUpdate->bindParam(':PhanTramDiemThi', $PhanTramDiemThi);
                $stmUpdate->bindParam(':MaSV', $MaSV);
                $stmUpdate->bindParam(':selectedClass', $selectedClass);
                $stmUpdate->execute();
            } else {
                // Thêm điểm mới nếu chưa tồn tại
                $sqlInsert = "INSERT INTO Diem (MaSV, MaMH, HocKy, DiemTieuLuan, DiemThi, DiemTB, PhanTramDiemTieuLuan, PhanTramDiemThi) 
                              VALUES (:MaSV, (SELECT MaMon FROM Lop WHERE MaLop = :selectedClass), 1, :DiemTieuLuan, :DiemThi, :DiemTB, :PhanTramDiemTieuLuan, :PhanTramDiemThi)";
                $stmInsert = $conn->prepare($sqlInsert);
                $stmInsert->bindParam(':MaSV', $MaSV);
                $stmInsert->bindParam(':selectedClass', $selectedClass);
                $stmInsert->bindParam(':DiemTieuLuan', $DiemTieuLuan);
                $stmInsert->bindParam(':DiemThi', $DiemThi);
                $stmInsert->bindParam(':DiemTB', $DiemTB);
                $stmInsert->bindParam(':PhanTramDiemTieuLuan', $PhanTramDiemTieuLuan);
                $stmInsert->bindParam(':PhanTramDiemThi', $PhanTramDiemThi);
                $stmInsert->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        $_SESSION['grades_saved'] = true;
    } catch (PDOException $e) {
        // Rollback transaction if something goes wrong
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Đóng kết nối
$conn = null;

// Chuyển hướng trở lại trang nhập điểm
header("Location: enterCourseGrades.php?class=" . $selectedClass);
exit();
?>
