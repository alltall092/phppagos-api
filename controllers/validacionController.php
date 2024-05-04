<?php
class ValidacionController{
    private $conn;

    public function __construct() {
        $this->conn = db::conexion();
    }
public function getValidacion(){
   // $conn = db::conexion(); // Obtener la conexión estática desde la clase db
    $query = "SELECT * FROM validacion";
    $result = $this->conn->query($query);

    $valid = [];
    while ($row = $result->fetch_assoc()) {
        $valid[] = $row;
    }

    echo json_encode($valid); 


}
public function postValidacion($bankData){
       // Preparar la consulta SQL para la inserción
       $query = "INSERT INTO validacion (idAportacion, numero) VALUES (?, ?)";
       $stmt = $this->conn->prepare($query);
       
       // Enlazar los parámetros con los valores proporcionados en $bankData
       $stmt->bind_param("ss", $bankData['idAportacion'], $bankData['numero']);
       
       // Ejecutar la consulta
       $stmt->execute();

}

}