<?php
session_start();
$token = $_SESSION['token'];
if (isset($_POST['addItem'])) {
    $nameType = $_POST['Them_LoaiDoDung'];

    // Dữ liệu của câu hỏi cần cập nhật
    $data = array(
        'nameType' => $nameType,

    );

    // Chuyển dữ liệu sang định dạng JSON
    $json_data = json_encode($data);

    // URL của API
    $url = 'http://localhost:8000/website_openshare/controllers/admin/ItemType/addItem.php';

    // Khởi tạo một session cURL
    $curl = curl_init($url);

    // Cấu hình các tùy chọn cho session cURL
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        "Accept: application/json",
        "Authorization: Bearer {$token}",
    ));

    // Thực thi session cURL và lấy kết quả trả về
    $result = curl_exec($curl);

    // Kiểm tra kết quả và hiển thị thông báo tương ứng
    if ($result === false) {
        echo 'Có lỗi xảy ra khi gửi yêu cầu POST đến API';
    } else {
        $response = json_decode($result, true);
        var_dump($response);
        if ($response[1] === 'ItemType is Inserted') {
            $_SESSION['status_success'] = "Thêm mới loại đồ dùng thành công";
            header('location: ./view_displayItem.php');
            exit();
        } else {
            $_SESSION['status_error'] = "Loại đồ dùng này đã tồn tại";
            header('location: ./view_displayItem.php');
            exit();
        }
    }

    // Đóng session cURL
    curl_close($curl);
}
