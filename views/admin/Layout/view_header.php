<?php include('../../../configs/url_api.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>website_openshare</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <?php include('../../../file.php') ?>

</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['token_admin'])) {
        $token = $_SESSION['token_admin'];
    } else {
        header('location: ../Staff/view_login.php');
    }
    $url = getUrlHead() . "admin/Staff/getStaff.php";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Accept: application/json",
        "Authorization: Bearer {$token}",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    if ($resp) {
        $result = json_decode($resp, true);
    }
    ?>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="../Trangchu/Trangchu.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">OPENSHARE</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <!-- <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div> -->
        <!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->
                <!-- callapi đang đợi duyệt để truyền vào header -->
                <?php
                $url = getUrlHead() . 'admin/PostManager/displayUnapprovedPost.php';

                // Khởi tạo một cURL session
                $curl = curl_init();

                // Thiết lập các tùy chọn cho cURL session
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        "Accept: application/json",
                        "Authorization: Bearer {$token}",
                    )
                ));

                // Thực hiện yêu cầu cURL và lấy kết quả trả về
                $response = curl_exec($curl);

                // Kiểm tra nếu có lỗi xảy ra
                if (curl_error($curl)) {
                    echo 'Error: ' . curl_error($curl);
                } else {
                    // Xử lý kết quả trả về
                    $data = json_decode($response, true);
                    $data1 = isset($data['data']) ? $data['data'] : null;
                }
                // Đóng cURL session
                curl_close($curl);
                ?>
                <?php

                date_default_timezone_set('Asia/Ho_Chi_Minh');
                function convert_time($datecreate)
                {

                    $thoigianhienthi = 0;
                    $thoigian = ((strtotime(date('Y-m-d H:i:s')) - strtotime($datecreate)) / 3600);
                    if ($thoigian <= 1) {
                        $thoigianhienthi = round($thoigian * 60, 0);
                    } else if ($thoigian <= 24) {
                        $thoigianhienthi = round($thoigian);
                    } else if ($thoigian > 24 && $thoigian <= 168) {
                        $thoigianhienthi = round($thoigian / 24);
                    } else {
                        $thoigianhienthi = $datecreate;
                    }
                    $text = '';
                    if ($thoigian <= 1) {
                        $text = ' phút trước';
                    } else if ($thoigian > 1 && $thoigian <= 24) {
                        $text = ' giờ trước';
                    } else if ($thoigian > 24 && $thoigian <= 168) {
                        $text = ' ngày trước';
                    } else {
                        $text = '';
                    }
                    echo $thoigianhienthi . $text;
                }

                ?>
                <!-- end -->

                <li class="nav-item dropdown">

                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number"><?php if ($data1 === null) echo 0;
                                                                    else echo count($data1) ?></span>
                    </a><!-- End Notification Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header">
                            Bạn có <?php if ($data1 === null) echo 0;
                                    else echo count($data1) ?> bài viết mới cần duyệt
                            <a href="../PostManager/view_displayUnapprovedPost.php"><span class="badge rounded-pill bg-primary p-2 ms-2">Xem tất cả</span></a>
                        </li>
                        <?php
                        if ($data1 === null) {
                        } else {
                            if (count($data1) > 3) {
                                $count = 3;
                            } else {
                                $count = count($data1);
                            }
                            for ($i = 0; $i < $count; $i++) { ?> <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="notification-item">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <a href="../PostManager/view_displayUnapprovedPost.php" style="display:inline-block">
                                        <div>
                                            <h4><?= $data1[$i]['name'] ?></h4>
                                            <p><?= $data1[$i]['title'] ?></p>
                                            <p><?php convert_time($data1[$i]['postDate']) ?></p>
                                        </div>
                                    </a>
                                </li> <?php }
                                } ?> <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-footer">
                            <a href="../PostManager/view_displayUnapprovedPost.php">Xem tất cả thông báo</a>
                        </li>

                    </ul><!-- End Notification Dropdown Items -->

                </li><!-- End Notification Nav -->


                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="<?= $result['user']['photoURL'] ?>" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?= $result['user']['userName'] ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $result['user']['userName'] ?></h6>
                            <span><?php if ($result['user']['idRole'] == 0) {
                                        echo "Nhân viên";
                                    } else {
                                        echo "Quản lý";
                                    } ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="../Staff/view_quanlytaikhoan.php">
                                <i class="bi bi-person"></i>
                                <span>Quản lý tài khoản</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="../Staff/view_logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->