<?php
session_start();
$token = $_SESSION['token'];
if (isset($_POST['acceptRequest'])) {
    $id = $_POST['idRequest'];
    $message = $_POST['message'];
    $idUserRequest = $_POST['idUserRequest'];
    $idPost = $_POST['idPost'];
    // Dữ liệu của câu hỏi cần cập nhật
    $data = array(
        'idRequest' => $id,
        'message' => $message,
        'idUserRequest' => $idUserRequest,
        'idPost' => $idPost
    );
    // Chuyển dữ liệu sang định dạng JSON
    $json_data = json_encode($data);

    // URL của API
    $url = 'http://localhost:8000/website_openshare/controllers/users/post/acceptRequest.php';

    // Khởi tạo một session cURL
    $curl = curl_init($url);

    // Cấu hình các tùy chọn cho session cURL
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data),
        "Accept: application/json",
        "Authorization: Bearer {$token}",
    ));

    // Thực thi session cURL và lấy kết quả trả về
    $result = curl_exec($curl);

    var_dump($result);
    // Kiểm tra kết quả và hiển thị thông báo tương ứng
    if ($result === false) {
        echo 'Có lỗi xảy ra khi gửi yêu cầu PUT đến API';
    } else {
        $response = json_decode($result);

        if ($response[1] === 'accept successfully') {
            $_SESSION['requestApprove_success'] = 'Duyệt yêu cầu thành công';
            header('location: ../post/view_displayReceiveRequest.php');
            exit();
        } else {
            $_SESSION['requestApprove_error'] = "Số lượng người cho đã đủ, vui lòng xem lại!!!";
            header('location: ../post/view_displayReceiveRequest.php');
            exit();
        }
    }

    // Đóng session cURL
    curl_close($curl);
}
