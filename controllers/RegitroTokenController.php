<?php
require_once("config/database.php");
class RegistroTokenController{
    private $conn;

    public function __construct() {
        $this->conn = db::conexion();
    }

    public function getToken(){
        $query = "SELECT * FROM registro_token";
        $result = $this->conn->query($query);

        $token= [];
        while ($row = $result->fetch_assoc()) {
            $token[] = $row;
        }

        echo json_encode($token); // Convertir la respuesta a JSON
    }



    public function createOrUpdate($userId, $bankData) {
        $existingBank = $this->findOne($userId);
    
        if ($existingBank) {
            // Si el banco existe, actualiza los datos
            $updatedBank = array_merge((array)$existingBank, (array)$bankData);
            $this->update($userId, $updatedBank);
            return ['message' => 'Bank updated successfully',  $updatedBank];
        } else {
            // Si el banco no existe, crea uno nuevo
            $this->save($bankData);
            return ['message' => 'Bank created successfully',  $bankData];
        }
    }
    public function findOne($id) {
        $query = "SELECT * FROM registro_token WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

    }

    public function save($bankData) {
        $query = "INSERT INTO registro_token (token) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $bankData['token']); // Sin json_encode
        $stmt->execute();
    
        
    }


public function update($userId, $bankData) {
    $id=$userId;

    $query = "UPDATE  registro_token SET token= ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssi", $bankData['token'], $id); // Sin json_encode
    $stmt->execute();
}
}