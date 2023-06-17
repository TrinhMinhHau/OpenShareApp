<?php
// set headers to allow cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
require __DIR__ . '/../../../configs/database.php';
require __DIR__ . '../../../AuthMiddleWareUsers.php';
$headers = getallheaders();
$db_connection = new db();
$conn = $db_connection->connect();
$auth = new AuthUsers($conn, $headers);
$auth_info = $auth->isValid();

if ($auth_info['success']) {
    $user = $auth_info['user'];

    // define response messages
    $success_message = 'User image updated successfully.';
    $error_message = 'Failed to update user image.';
    $method_error_message = 'Method not allowed.';
    //Check api has token

    // check if request method is PUT
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

        // get request body as JSON
        $request_body = json_decode(file_get_contents('php://input'), true);

        // extract request parameters
        $id = $request_body['idUser'];
        $name = $request_body['name'];
        $email = $request_body['email'];
        $phoneNumber = $request_body['phoneNumber'];
        $image_data = $request_body['photoURL'];
        var_dump($request_body);
        // decode image data from base64
        // $image = base64_decode($image_data);

        // create image MIME type based on file extension
        // $image_mime = "data:image/" . strtolower(pathinfo($image_data, PATHINFO_EXTENSION)) . ";base64," . $image_data;

        // update user record in database
        $update_query = "UPDATE user SET name=:name,photoURL=:image,email=:email,phoneNumber=:phoneNumber WHERE idUser=:id";
        $update_stmt = $conn->prepare($update_query);

        // bind parameters to statement
        $update_stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $update_stmt->bindValue(':image', $image_data, PDO::PARAM_LOB);
        $update_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $update_stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $update_stmt->bindValue(':phoneNumber', $phoneNumber, PDO::PARAM_STR);

        // execute statement
        if ($update_stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => $success_message]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $error_message]);
        }
    } else {
        // return 405 Method Not Allowed for non-PUT requests
        http_response_code(405);
        echo json_encode(['error' => $method_error_message]);
    }
} else {
    // Return error response if the token is invalid
    echo json_encode([
        'success' => false,
        'message' => 'Token request not found',
    ]);
}
