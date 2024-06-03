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
  <link rel="stylesheet" href="/skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="/skydash/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="/skydash/text/css" href="js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="/skydash/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="/skydash/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="\skydash\src\direction.php"><img src="/skydash/images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="\skydash\src\direction.php"><img src="/skydash/images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        
        
        <ul class="navbar-nav navbar-nav-right">
          <!-- Thông báo -->
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="ti-settings mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Settings</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Private message
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">New user registration</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    2 days ago
                  </p>
                </div>
              </a>
            </div>
          </li>

          <!-- Logout -->
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="/skydash/images/faces/face28.jpg" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Cài đặt
              </a>
              <a class="dropdown-item" href="\skydash\src\login.php">
                <i class="ti-power-off text-primary"></i>
                Đăng xuất
              </a>
            </div>
          </li>
      </div>
    </nav>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="\skydash\src\direction.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Trang chủ</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#thong-tin-chung" aria-expanded="false" aria-controls="thong-tin-chung">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Thông tin chung</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="thong-tin-chung">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="\skydash\src\SinhVien\infomationSV.php">Thông tin học viên</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#hoc-tap" aria-expanded="false" aria-controls="hoc-tap">
              <i class="icon-paper menu-icon"></i>
              <span class="menu-title">Học tập</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="hoc-tap">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="\skydash\src\SinhVien\learningOutcomesSV.php">Kết quả học tập</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Lịch theo tuần</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Lịch theo tiến độ</a></li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#dang-ky-hoc-phan" aria-expanded="false" aria-controls="dang-ky-hoc-phan">
              <i class="icon-grid-2 menu-icon"></i>
              <span class="menu-title">Đăng ký học phần</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="dang-ky-hoc-phan">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="#">Chương trình khung</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Đăng ký học phần</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#hoc-phi" aria-expanded="false" aria-controls="hoc-phi">
              <i class="icon-contract menu-icon"></i>
              <span class="menu-title">Học phí</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="hoc-phi">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="#">Tra cứu công nợ</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">Thanh toán trực tuyến</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
