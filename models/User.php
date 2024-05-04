<?php
require_once('config/database.php');

class User
{
    
  
    public  static function  getAllUsers()
    {    // Obtener la conexión a la base de datos
        $conn = db::conexion();
        
        
        $result =$conn->query("select * from usuario");
    

        
        
        // Obtener los resultados como un array asociativo
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        
        // Mostrar los resultados
        echo json_encode($usuarios);
        
        // Cerrar la conexión
    
        

    }
    
    

    
/*
    public function getUserById($id)
    {
        $user = array_filter($this->users, function($user) use ($id) {
            return $user['id'] === $id;
        });

        if (!empty($user)) {
            return json_encode(array_values($user)[0]);
        } else {
            http_response_code(404);
            return json_encode(['error' => 'User not found']);
        }
    }*/
}


