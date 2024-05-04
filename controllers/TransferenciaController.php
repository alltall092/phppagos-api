<?php
class TransferenciaController{

    private $conn;

    public function __construct() {
        $this->conn = db::conexion();
    }
    
    public function getTransfer(){
        $query = "SELECT * FROM transferencia";
        $result = $this->conn->query($query);
    
        $transfer= [];
        while ($row = $result->fetch_assoc()) {
            $transfer[] = $row;
        }
    
        echo json_encode($transfer); // Convertir la respuesta a JSON
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
        $query = "SELECT * FROM transferencia WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
    }
    public function save($bankData) {
        $query = "INSERT INTO transferencia (tipoDeTransferencia, parentesco, tiempo, motivo) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $bankData['tipoDeTransferencia'], $bankData['parentesco'], $bankData['tiempo'], $bankData['motivo']);
        $stmt->execute();
    }
    
    
    
    public function update($userId, $bankData) {
        $id = $userId;
    
        $query = "UPDATE transferencia SET tipoDeTransferencia = ?, parentesco = ?, tiempo = ?, motivo = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $bankData['tipoDeTransferencia'], $bankData['parentesco'], $bankData['tiempo'], $bankData['motivo'], $id); // Sin json_encode
        $stmt->execute();
    }
    


}