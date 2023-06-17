    <div class="post">
        <i class="fa-regular fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#post"></i>

        <div class="modal fade" id="post" tabindex="-1" aria-labelledby="Label_Edit" aria-hidden="true">
            <div class="modal-dialog modal-lg ">
                <!-- modal-xl -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Label_Edit">Đăng bài cho</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../post/view_scriptpost.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Tiêu đề</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="title" type="text" class="form-control" id="fullName" placeholder="Bếp ga cũ cầm cho ...">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-md-4 col-lg-3 col-form-label">Mô tả</label>
                                <div class="col-md-8 col-lg-9">
                                    <textarea class="form-control" id="description" name="description" placeholder="Mô tả ..." rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Ảnh</label>
                                <div class="col-md-8 col-lg-9">
                                    <div id="galeria">
                                    </div>
                                    <div class="mb-3 pt-2">
                                        <label for="formFileMultiple" class="form-label"></label>
                                        <input class="form-control" type="file" id="fileToUploadmul" name="fileToUpload[]" onchange="previewMultiple(event)" multiple>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?= $result['user']['idUser'] ?> " class=" form-control">

                            <div class="row mb-3">
                                <label for="type" class="col-md-4 col-lg-3 col-form-label">Loại đồ cho</label>
                                <div class="col-md-8 col-lg-9">
                                    <select class="form-select" name="type">
                                        <?php
                                        $token = $_SESSION['token'];
                                        $url = 'http://localhost:8000/website_openshare/controllers/users/type/getType.php';

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
                                            $data3 = $data['data'];
                                        }

                                        // Đóng cURL session
                                        curl_close($curl);
                                        ?>
                                        <?php for ($i = 0; $i < count($data3); $i++) { ?>
                                            <option value="<?= $data3[$i]['idType'] ?>" selected><?= $data3[$i]['nameType'] ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                <div class="col-md-8 col-lg-9">
                                    <select class="form-select" name="address">
                                        <option value="" selected>Chọn địa chỉ</option>
                                        <?php

                                        $token = $_SESSION['token'];
                                        $idUser = $result['user']['idUser'];
                                        $data = array(
                                            'idUser' => $idUser
                                        );
                                        $json_data = json_encode($data);

                                        $url = 'http://localhost:8000/website_openshare/controllers/users/address/get.php';


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
                                            $data4 = $data ? $data['data'] : null;
                                        }

                                        // Đóng cURL session
                                        curl_close($curl);
                                        ?>
                                        <?php
                                        if ($data4 == null) {
                                        } else {
                                            for ($i = 0; $i < count($data4); $i++) { ?>

                                                <option value="<?= $data4[$i]['address'] ?>"><?= $data4[$i]['address'] ?></option>
                                        <?php }
                                        } ?>

                                    </select>
                                    <p class="mt-3">
                                        <button class="btn btn-primary p-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            Thêm địa chỉ
                                        </button>
                                    </p>
                                </div>

                                <div class="collapse" id="collapseExample">

                                    <div class="container">

                                        <div class="row">
                                            <div class="col-md-3"><select name="" id="province_post" class="form-select"></select></div>
                                            <div class="col-md-3"> <select name="" id="district_post" class=" form-select">
                                                    <option value="">chọn quận</option>
                                                </select></div>
                                            <div class="col-md-3">
                                                <select name="" id="ward_post" class="form-select">
                                                    <option value="">chọn phường</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" placeholder="Nhập địa chỉ" class="input-group p-1" id="street_post">

                                            </div>
                                            <input type="hidden" name="result_post" id="result_post" value="" />
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" name="post" class="btn btn-primary">Lưu thay đổi</button>

                            </div>
                    </form>


                </div>

            </div>
        </div>
    </div>
    <script src="../assests/handle_address_post.js"></script>