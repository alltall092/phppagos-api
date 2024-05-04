<?php
// AportacionController.php
require_once('config/database.php'); // Importa la configuración de la base de datos
class AportacionController {
    public static  function createAportacion($data) {
        $conn = db::conexion(); 
         // Obtener la conexión estática desde la clase db
        $query = "INSERT INTO aportacion (timestamp) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $data['idEnviado']);

        if ($stmt->execute()) {
            $aportacionId = $stmt->insert_id;
            $response = ['message' => 'Aportacion creada exitosamente', 'aportacion_id' => $aportacionId];
        } else {
            $response = ['error' => 'Error al crear la aportacion: ' . $stmt->error];
        }

        echo json_encode($response); // Convertir la respuesta a JSON
    }

    public static function getAllBank() {
        $conn = db::conexion(); // Obtener la conexión estática desde la clase db
        $query = "SELECT * FROM aportacion";
        $result = $conn->query($query);

        $aportaciones = [];
        while ($row = $result->fetch_assoc()) {
            $aportaciones[] = $row;
        }

        echo json_encode($aportaciones); // Convertir la respuesta a JSON
    }
}
