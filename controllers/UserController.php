<?php
require_once 'models/User.php';
require_once('config/database.php');
class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
       
    }

    public function getAllUsers() {
        $users = $this->userModel::getAllUsers();
    
        echo json_encode($users);
    }
/*
    public function getUserById($id) {
        $user = $this->userModel->getUserById($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }
*/

public static function createUser($userId,$data) {
    // Conectar a la base de datos
    $conn = db::conexion();
$id=$userId;
    // Verificar si el usuario ya existe utilizando el ID
    $existingUserQuery = "SELECT id FROM usuario WHERE id = ?";
    $stmtCheckUser = $conn->prepare($existingUserQuery);
    $stmtCheckUser->bind_param("i", $id);
    $stmtCheckUser->execute();
    $stmtCheckUser->store_result();

    // Si el usuario ya existe, realizar la actualización
    if ($stmtCheckUser->num_rows > 0) {
        $updateQuery = "UPDATE usuario SET usuario = ?, contra = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("ssi", $data['usuario'], $data['contra'], $id);
        $success = $stmtUpdate->execute();

        $stmtCheckUser->close();
        $stmtUpdate->close();
        $conn->close();
        
        return $success; // Devuelve true si la actualización fue exitosa
    } else {
        // Si el usuario no existe, insertarlo
        $insertQuery = "INSERT INTO usuario ( usuario, contra) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ss", $data['usuario'], $data['contra']);
        $success = $stmtInsert->execute();

        $stmtCheckUser->close();
        $stmtInsert->close();
        $conn->close();
        
        return $success; // Devuelve true si la inserción fue exitosa
    }
}
}
