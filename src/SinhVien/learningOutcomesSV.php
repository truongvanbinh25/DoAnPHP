<?php
session_start();
include __DIR__ . "/../ConnectSQL/connect.php";

// Kiểm tra xem user_id có trong session không
if (!isset($_SESSION['username'])) {
    // Nếu không có, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Lấy user_id từ session
$username = $_SESSION['username'];

//Lấy số học kỳ của sinh viên
$sql2 = "SELECT DISTINCT HocKy
        FROM Diem
        WHERE MaSV = :username;";
$stm2 = $conn->prepare($sql2);
$stm2->bindParam(':username', $username);
$stm2->execute();
$data2 = $stm2->fetchAll(PDO::FETCH_OBJ);

$stm2 = null;
?>
<?php include 'D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\Shared\header.php'; ?>
      <!-- partial -->
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Bảng Điểm</h4>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Mã môn học</th>
                  <th>Tên môn học</th>
                  <th>Lớp dự kiến</th>
                  <th>Số tín chỉ</th>
                  <th>Loại môn học</th>
                  <th>Điểm tiểu luận</th>
                  <th>Điểm thi</th>
                  <th>Điểm trung bình</th>
                  <th>Đạt</th>
                </tr>
              </thead>
              <?php
                  foreach($data2 as $item2)
                  {
                    ?>
                    <td>
                      <h5>HK<?php echo $item2->HocKy ?></h5>
                    </td>
                    <tbody>
                    <?php
                    //Truy vấn thông tin sinh viên dựa trên user_id
                    $sql = "SELECT MonHoc.MaMH, MonHoc.TenMH, MonHoc.SoTinChi, MonHoc.LoaiMonHoc, Diem.DiemTieuLuan, Diem.DiemThi, Diem.DiemTB, Lop.TenLop 
                    FROM MonHoc
                    JOIN Diem ON Diem.MaMH = MonHoc.MaMH
                    JOIN Lop ON Lop.MaMon = MonHoc.MaMH
                    WHERE Diem.MaSV = :username
                    AND Diem.HocKy = :hocky;";
                    $stm = $conn->prepare($sql);
                    $stm->bindParam(':username', $username);
                    $stm->bindParam(':hocky', $item2->HocKy);
                    $stm->execute();
                    $data = $stm->fetchAll(PDO::FETCH_OBJ);

                    $stm = null;
                    foreach($data as $item)
                    {
                      ?>
                        <tr>
                          <td><?php echo $item->MaMH ?> </td>
                          <td><?php echo $item->TenMH ?></td>
                          <td><?php echo $item->TenLop ?></td>
                          <td><?php echo $item->SoTinChi ?></td>
                          <td><?php echo $item->LoaiMonHoc ?></td>
                          <td><?php echo $item->DiemTieuLuan ?></td>
                          <td><?php echo $item->DiemThi ?></td>
                          <td><?php echo $item->DiemTB * 10 ?></td>
                          <td>
                            <?php
                            if($item->DiemTB * 10 >= 5)
                            {
                              ?>
                              <label class="badge badge-success">Đạt</label>
                              <?php
                            }
                            else
                            {
                              ?>
                              <label class="badge badge-danger">Không Đạt</label>
                              <?php
                            }
                            ?>
                            
                          </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <?php
                  }
                ?>
            </table>
          </div>
        </div>
      </div>

      <!-- main-panel ends -->
    </div>  
    <!-- page-body-wrapper ends -->
  </div>
  <?php include 'D:\CNTT\Thuc_hanh\LT_MaNguonMo\DoAn\skydash\src\Shared\footer.php'; ?>


