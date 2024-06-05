<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";
require __DIR__ . '/../phpspreadsheet_autoload.php';  // Including the PhpSpreadsheet autoload file

use PhpOffice\PhpSpreadsheet\IOFactory;

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Lấy user_id từ session
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $file = $_FILES['file']['tmp_name'];
    $selectedClass = $_POST['class'];

    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();

    $PhanTramDiemTieuLuan = 3;
    $PhanTramDiemThi = 7;

    try {
        $conn->beginTransaction();

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Assuming the first column is MaSV, the second column is DiemTieuLuan, and the third column is DiemThi
            $MaSV = $rowData[0];
            $DiemTieuLuan = $rowData[1];
            $DiemThi = $rowData[2];

            // Calculate DiemTB
            $DiemTB = ($DiemTieuLuan * $PhanTramDiemTieuLuan / 10) + ($DiemThi * $PhanTramDiemThi / 10);

            // Insert or update the grade in the database
            $sqlCheck = "SELECT * FROM Diem WHERE MaSV = :MaSV AND MaMH = (SELECT MaMon FROM Lop WHERE MaLop = :selectedClass)";
            $stmCheck = $conn->prepare($sqlCheck);
            $stmCheck->bindParam(':MaSV', $MaSV);
            $stmCheck->bindParam(':selectedClass', $selectedClass);
            $stmCheck->execute();
            $existingGrade = $stmCheck->fetch(PDO::FETCH_OBJ);

            if ($existingGrade) {
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

        $conn->commit();
        $_SESSION['grades_saved'] = true;
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['grades_saved'] = false;
        die("Error: " . $e->getMessage());
    }

    header("Location: enterCourseGrades.php");
    exit();
} else {
    $_SESSION['grades_saved'] = false;
    header("Location: enterCourseGrades.php");
    exit();
}
?>
