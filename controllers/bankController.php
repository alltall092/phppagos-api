<?php
// BankController.php

require_once('config/database.php'); // Incluye el archivo del servicio

class BankController {

    public static function getBank() {
        $conn = db::conexion(); // Obtener la conexión estática desde la clase db
        $query = "SELECT * FROM bank";
        $result = $conn->query($query);

        $bank = [];
        while ($row = $result->fetch_assoc()) {
            $bank[] = $row;
        }

        echo json_encode($bank); // Convertir la respuesta a JSON
    }

    public function createOrUpdate($userId, $bankData) {
        $existingBank = $this->findOne($userId);
    
        if ($existingBank) {
            // Si el banco existe, actualiza los datos
            $updatedBank = array_merge((array)$existingBank, (array)$bankData);
            $this->update($userId, $updatedBank);
            return ['message' => 'Bank updated successfully', 'bank' => $updatedBank];
        } else {
            // Si el banco no existe, crea uno nuevo
            $this->create($bankData);
            return ['message' => 'Bank created successfully', 'bank' => $bankData];
        }
    }
    
    private function create($bankData) {
        $conn = db::conexion(); 
        $query = "INSERT INTO bank (nombre, imagen) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $bankData['nombre'], $bankData['imagen']); // Sin json_encode
        $stmt->execute();
    }
    
    private function update($userId, $bankData) {
        $id=$userId;
        $conn = db::conexion();
        $query = "UPDATE bank SET nombre = ?, imagen = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $bankData['nombre'], $bankData['imagen'], $id); // Sin json_encode
        $stmt->execute();
    }
    

    private function findOne($userId) {
        $id=$userId;
        $conn = db::conexion(); 
        $query = "SELECT * FROM bank WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;

       
    }

   
}

