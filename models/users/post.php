<?php
class Post
{
    private $conn;

    //property
    public $title;
    public $description;
    public $address;
    public $photos;
    public $idUser;
    public $idType;
    public $idPost;
    public $idUserRequest;
    public $postDate;
    public $requestDate;
    public $statusPost;
    public $name;
    public $photoURL;
    public $nameType;
    public $id_Userget;
    public $message;

    // connect db
    public function __construct($db)
    {
        $this->conn = $db;
    }



    public function displayRequest()
    {
        $query = "SELECT yc.idRequest, yc.idPost, yc.idUserRequest, yc.message, yc.requestDate,yc.status,
            p.title, p.description, p.postDate, p.address, p.photos, p.idType,u.name,d.nameType,u.photoURL
            FROM yeucau yc 
            JOIN baiviet p ON yc.idPost = p.idPost
            JOIN user u ON u.idUser= p.idUser
            JOIN doanhmuc d ON d.idType = p.idType
            WHERE yc.idUserRequest =:idUser";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':idUser', $this->idUser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function displayManegerRequest()
    {
        $query = "SELECT yc.idRequest, yc.idPost, yc.idUserRequest, yc.message, yc.idUserRequest, yc.requestDate, yc.status,
        p.title, p.description, p.postDate, p.address, p.photos, p.idType,u.name,d.nameType,u.photoURL
        FROM yeucau yc
        JOIN baiviet p ON yc.idPost = p.idPost
        JOIN user u ON u.idUser= yc.idUserRequest
        JOIN doanhmuc d ON d.idType = p.idType
        WHERE p.idUser =:idUser";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':idUser', $this->idUser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function addItem()
    {
        $query = "INSERT INTO `baiviet` SET title=:title,description=:description,address=:address,photos=:photos,idType=:idType,idUser=:idUser";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
        $stmt->bindValue(':photos', $this->photos, PDO::PARAM_STR);
        $stmt->bindValue(':idType', $this->idType, PDO::PARAM_INT);
        $stmt->bindValue(':idUser', $this->idUser, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error", $stmt->error;
            return false;
        }
    }

    public function requestPost()
    {
        $check_requestPost = "SELECT idUserRequest,idPost FROM `yeucau` WHERE  `idUserRequest`=:idUserRequest and `idPost`=:idPost";
        $check_requestPost_stmt = $this->conn->prepare($check_requestPost);
        $check_requestPost_stmt->bindValue(':idUserRequest', $this->idUserRequest, PDO::PARAM_INT);
        $check_requestPost_stmt->bindValue(':idPost', $this->idPost, PDO::PARAM_INT);
        $check_requestPost_stmt->execute();

        if ($check_requestPost_stmt->rowCount()) :
            echo "Người dùng này đã yêu cầu";
        else :
            $query = "INSERT INTO `yeucau` SET idUserRequest=:idUserRequest,idPost=:idPost,message=:message";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':idPost', $this->idPost, PDO::PARAM_INT);
            $stmt->bindValue(':idUserRequest', $this->idUserRequest, PDO::PARAM_INT);
            $stmt->bindValue(':message', $this->message, PDO::PARAM_STR);
        endif;
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error", $stmt->error;
            echo $stmt->error;
            return false;
        }
    }


    public function displayItem()
    {

        $query = "SELECT * FROM `baiviet`,user,doanhmuc where isShow=1 and statusPost=0 and user.idUser = baiviet.idUser and baiviet.idType=doanhmuc.idType order by baiviet.idPost desc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function displayPostbyidUser()
    {
        $query = "SELECT * FROM `baiviet`,user,doanhmuc where user.idUser = baiviet.idUser and baiviet.idType=doanhmuc.idType and baiviet.idUser =:id_Userget  order by baiviet.idPost desc";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id_Userget', $this->id_Userget, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function displayItemWithType()
    {
        $query = "SELECT * FROM `baiviet` where idType=:idType";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':idType', $this->idType, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    public function deleteItem()
    {
        $query_2 = "DELETE FROM baiviet WHERE idPost=:idPost";
        $stmt2 = $this->conn->prepare($query_2);
        $stmt2->bindParam(':idPost', $this->idPost, PDO::PARAM_INT);

        if ($stmt2->execute()) {
            return true;
        } else {
            echo "Xóa thất bại";
            return false;
        }
    }
}
