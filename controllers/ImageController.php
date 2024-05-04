<?php
// Ruta de la carpeta donde se encuentra la imagen en el servidor
class ImageController {
    public function showImage($nombreImagen) {
        $rutaCarpeta = 'storage/';
        $rutaImagen = $rutaCarpeta . $nombreImagen;

        if (file_exists($rutaImagen)) {
            $imagen = file_get_contents($rutaImagen);
            $tipoMime = mime_content_type($rutaImagen);

            header("Content-Type: $tipoMime");
            header("Content-Length: " . filesize($rutaImagen));

            echo $imagen;
        } else {
            header("Content-Type: text/plain");
            echo "La imagen no existe";
        }
    }


    public static function getBank() {
        $conn = db::conexion(); // Obtener la conexión estática desde la clase db
        $query = "SELECT id, nombre, imagen FROM bank"; // Incluir el campo de imagen en la consulta
        $result = $conn->query($query);
    
        $bank = [];
        while ($row = $result->fetch_assoc()) {
            // Construir la URL completa de la imagen basada en la ruta de tu aplicación
            $row['imagen'] = 'http://localhost/php/api-pagos/imagenes/' . $row['imagen'];
            $bank[] = $row;
        }
    
        echo json_encode($bank); // Convertir la respuesta a JSON
    }
    
}

// Uso del controlador

    //$controller = new ImageController();
    
    //$controller->showImage("imagen1.webp");
