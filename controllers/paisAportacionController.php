<?php 
require_once('config/database.php');
class paisAportacionController{
private $conn;

public function __construct() {
    $this->conn = db::conexion();
}

public function getPais(){
    $query = "SELECT * FROM pais";
    $result = $this->conn->query($query);

    $pais= [];
    while ($row = $result->fetch_assoc()) {
        $pais[] = $row;
    }

    echo json_encode($pais); // Convertir la respuesta a JSON
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
    $query = "SELECT * FROM pais WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

}

public function save($bankData) {
    $query = "INSERT INTO pais (pais) VALUES (?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $bankData['pais']); // Sin json_encode
    $stmt->execute();

    
}


public function update($userId, $bankData) {
$id=$userId;

$query = "UPDATE  pais SET pais= ? WHERE id = ?";
$stmt = $this->conn->prepare($query);
$stmt->bind_param("ssi", $bankData['pais'], $id); // Sin json_encode
$stmt->execute();
}
}
