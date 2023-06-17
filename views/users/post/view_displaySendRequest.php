<?php include('../layout/header.php'); ?>
<script src="//code.jquery.com/jquery.min.js"></script>

<!-- CSS của Images Grid -->
<script src="https://cdn.jsdelivr.net/gh/taras-d/images-grid/src/images-grid.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/taras-d/images-grid/src/images-grid.min.css" />
<?php

$token = $_SESSION['token'];
$idUser = $result['user']['idUser'];
$data = array(
    'idUser' => $idUser
);
$json_data = json_encode($data);

$url = 'http://localhost:8000/website_openshare/controllers/users/post/getRequest.php';


// Khởi tạo một cURL session
$curl = curl_init();

// Thiết lập các tùy chọn cho cURL session
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_POSTFIELDS => $json_data,
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
    $data1 = $data ? $data['data'] : null;
}

// Đóng cURL session
curl_close($curl);
?>
<!--Profile Page-->
<div class="profile-container">
    <?php if (isset($_SESSION['send_request_success'])) {
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['send_request_success'];
            unset($_SESSION['send_request_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    } ?>
    <div class="page">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../TrangChu/index.php">Trang chủ</a></li>
            <li class="breadcrumb-item active">Yêu cầu đã gửi</li>
        </ol>

    </div>
    <div class="profile-details displayRequest">
        <div class="pd-left">
            <div class="pd-row">
                <img src="<?= $result['user']['photoURL'] ?>" alt="" class="pd-image" />
                <div>
                    <h3><?= $result['user']['name'] ?></h3>
                    <p>120 Friends - 20 Mutual</p>
                    <img src="../assests/images/member-1.png" alt="" />
                    <img src="../assests/images/member-2.png" alt="" />
                    <img src="../assests/images/member-3.png" alt="" />
                    <img src="../assests/images/member-4.png" alt="" />
                    <img src="../assests/images/member-5.png" alt="" />
                    <img src="../assests/images/member-6.png" alt="" />
                </div>
            </div>
        </div>
        <div class="pd-right">
            <button type="button">
                <img src="../assests/images/add-friends.png" alt="" /> Friends
            </button>
            <button type="button">
                <img src="../assests/images/message.png" alt="" />Message
            </button>
            <br />
            <a href=""><img src="../assests/images/more.png" alt="" /></a>
        </div>
    </div>


    <div class="post-col displayRequest">

        <?php
        if ($data1 == null) {
        ?>
            <div class="post-container">
                <p class="datanull">Không yêu cầu nào được gửi </p>
            </div>
            <?php
        } else {
            for ($i = 0; $i < count($data1); $i++) {
            ?>


                <div class="post-container">
                    <div class="post-row">
                        <div class="user-profile">
                            <a href="#"> <img src="<?= $data1[$i]['photoURL'] ?>" alt="" />
                            </a>
                            <div>
                                <a href="#">
                                    <p><?= $data1[$i]['name'] ?></p>
                                </a>
                                <span><?= $data1[$i]['postDate'] ?></span>
                            </div>
                            <div class="address">
                                <p><i class="fa-solid fa-location-dot"></i> <?= explode(",",  $data1[$i]['address'])[0] ?></p>
                            </div>
                            <div class="type">
                                <a href="#">
                                    <p><?= $data1[$i]['nameType'] ?></p>
                                </a>
                            </div>
                            <div class="status_post">
                                <p><?php if ($data1[$i]['status'] == 0) {
                                        echo "Đang đợi";
                                    } elseif ($data1[$i]['status'] == 1) {
                                        echo "Đã duyệt";
                                    } elseif ($data1[$i]['status'] == 2) {
                                        echo "Từ chối";
                                    } else if ($data1[$i]['status'] == 3) {
                                        echo "Đã nhận";
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <i class="fas fa-ellipsis-v toggle<?= $i ?>" style="cursor:pointer"></i>
                        <div class="menu-child menu<?= $i ?>">
                            <ul class="child">
                                <li>

                                    <form action="../post/view_deletePost.php" method="post" id="form_delete">
                                        <a href="#" onclick="document.getElementById('form_delete').submit()">Xoá bài cho</a>
                                        <input type="hidden" name="deletePost" value="<?= $data1[$i]['idPost'] ?>">
                                    </form>

                                </li>
                            </ul>
                        </div>
                        <script>
                            document.querySelector('.toggle<?= $i ?>').addEventListener('click', function() {
                                document.querySelector('.menu<?= $i ?>').classList.toggle('active');
                            });
                        </script>

                    </div>
                    <p class="post-text">
                        <?= $data1[$i]['description'] ?>
                    </p>
                    <div id="post-image<?php echo $i ?>">
                        <?php
                        if ($data1[$i]['photos'] == null) {
                        } else {
                            $arr_img = [];
                            for ($j = 0; $j < count(json_decode($data1[$i]['photos'])); $j++) {
                                array_push($arr_img, json_decode($data1[$i]['photos'])[$j]);
                            }
                        }
                        // var_dump($arr_img);
                        ?>
                    </div>
                    <div class="post-row">
                        <div class="activity-icons">
                            <div>
                                <img src="../assests/images/like-blue.png" alt="" />
                                120
                            </div>
                            <div>
                                <img src="../assests/images/comments.png" alt="" />
                                45
                            </div>
                            <div>
                                <img src="../assests/images/share.png" alt="" />
                                20
                            </div>
                        </div>
                        <div class="post-profile-icon">

                            <div style="cursor:pointer">
                                <div data-bs-toggle="modal" data-bs-target="#request<?php echo $data1[$i]['idPost'] ?>"><img src="../assests/images/detail_request_icon.png"> <small> Chi tiết yêu cầu </small></div>

                                <div class=" modal fade" id="request<?php echo $data1[$i]['idPost'] ?>" tabindex="-1" aria-labelledby="Label_Edit" aria-hidden="true">
                                    <div class="modal-dialog modal-lg ">
                                        <!-- modal-xl -->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="Label_Edit">Chi tiết yêu cầu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="row mb-3">
                                                    <label for="message" class="col-md-4 col-lg-3 col-form-label">Tin nhắn</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <textarea class="form-control" id="message" name="message" placeholder="Mô tả ..." rows="3" disabled><?= $data1[$i]['message'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="dateRequest" class="col-md-4 col-lg-3 col-form-label">Thời gian yêu cầu</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <textarea class="form-control" id="dateRequest" name="dateRequest" placeholder="Mô tả ..." rows="1" disabled><?= $data1[$i]['requestDate'] ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $("#post-image<?php echo $i ?>").imagesGrid({
                            images: <?= json_encode($arr_img) ?>,
                            align: false,
                            cells: 4,
                            nextOnClick: true,
                            showViewAll: "more",
                            getViewAllText: function() {},
                            onGridRendered: $.noop,
                            onGridItemRendered: $.noop,
                            onGridLoaded: $.noop,
                            onGridImageLoaded: $.noop,
                            onModalOpen: $.noop,
                            onModalClose: $.noop,
                            onModalImageClick: $.noop,
                            onModalImageUpdate: $.noop,
                        });
                    });
                </script>

            <?php } ?>
        <?php } ?>
    </div>
    <?php include('../post/view_post.php') ?>
</div>
<script>
    imgInpEl = document.getElementById('fileToUpload');
    imgEL = document.getElementById('img');

    imgInpEl.onchange = evt => {
        const [file] = imgInpEl.files
        if (file) {
            imgEL.src = URL.createObjectURL(file)
        }
    }
</script>
<?php include('../layout/footer.php'); ?>
<script src="../assests/handle_address.js"></script>