<?php
class Address
{
    private $conn;

    //property
    public $address;
    public $idUser;
    public $idAdress;


    // connect db
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addItem()
    {
        $query = "INSERT INTO `diachi` SET address=:address,idUser=:idUser";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
        $stmt->bindValue(':idUser', $this->idUser, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error", $stmt->error;
            return false;
        }
    }

    public function displayItem()
    {
        $query = "SELECT * FROM `diachi` WHERE idUser=:idUser";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':idUser', $this->idUser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function deleteItem()
    {
        $query_2 = "DELETE FROM diachi WHERE idAdress=:idAdress";
        $stmt2 = $this->conn->prepare($query_2);
        $stmt2->bindParam(':idAdress', $this->idAdress, PDO::PARAM_INT);

        if ($stmt2->execute()) {
            return true;
        } else {
            echo "Xóa thất bại";
            return false;
        }
    }
}
