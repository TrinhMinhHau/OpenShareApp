<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require __DIR__ . '/../../../configs/database.php';

require __DIR__ . '../../../AuthMiddleWareUsers.php';
include('../../../models/users/address.php');

$db = new db();
$connect = $db->connect();
$headers = getallheaders();
$auth = new AuthUsers($connect, $headers);

// Validate the token
$auth_info = $auth->isValid();

// If the token is valid
    //code...
    if ($auth_info['success']) {

        $itemPost = new Address($connect);
    
        $data = json_decode(file_get_contents("php://input"));
        $itemPost->address = $data->address;
        $itemPost->idUser = $data->idUser;
    
        if ($itemPost->addItem()) {
            echo json_encode(array('message', 'ItemType is Inserted'));
        } else {
            echo json_encode(array('message', 'ItemType is not Inserted'));
        }
    } else {
        // Return error response if the token is invalid
        echo json_encode([
            'success' => false,
            'message' => 'Token request not found',
        ]);
    }

