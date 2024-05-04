<?php
// Configuración de encabezados para permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// Especifica los métodos HTTP permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Especifica los encabezados permitidos
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
require_once('config/database.php');
require_once('controllers/UserController.php');
require_once('controllers/AportacionController.php');
require_once('controllers/bankController.php');
require_once('controllers/RegitroTokenController.php');
require_once('controllers/paisAportacionController.php');
require_once('controllers/TransferenciaController.php');
require_once('controllers/validacionController.php');
require_once('controllers/ImageController.php');
// Verificar el método de solicitud
$method = $_SERVER["REQUEST_METHOD"];
$request = $_SERVER['REQUEST_URI'];
$baseUrl="/php/api-pagos/";

switch ($request) {  
    case $baseUrl.'usuario':
        $controller = new UserController();
  
        if ($method === "GET") {

        $controller->getAllUsers();
        }
        
        break;
        case   $baseUrl.'aportacion': 
            $controller = new AportacionController();

            
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);
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
    

 

                // Devolver la respuesta como JSON
                header('Content-Type: application/json');
               // echo json_encode($result);
            } else if ($method === 'GET') {
                $result = $controller::getAllBank();
                // Devolver la respuesta como JSON
                header('Content-Type: application/json');
                echo json_encode($result);
            }
            break;
            case  $baseUrl.'validacion':
                $controller=new ValidacionController();
                if ($method === "POST") {
                    $conn = db::conexion();

                    // Obtener y decodificar los datos JSON
                    $rawData = file_get_contents("php://input");
                    $data = json_decode($rawData, true);
                    
                    // Verificar que se recibieron los datos esperados
                    if (isset($data['idAportacion'], $data['numero'])) {
                        // Obtener los valores de los parámetros y escaparlos
                        $idAportacion = mysqli_real_escape_string($conn, $data['idAportacion']);
                        $numero = mysqli_real_escape_string($conn, $data['numero']);
                    
                        // Construir la consulta SQL con los valores escapados
                        $query = "INSERT INTO validacion (idAportacion, numero) VALUES ('$idAportacion', '$numero')";
                    
                        // Ejecutar la consulta
                        $success = $conn->query($query);
                    
                        if ($success) {
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($conn)]);
                        }
                    } else {
                        // Si no se reciben los datos esperados
                        echo json_encode(['error' => 'Datos incompletos']);
                    }
                        // Si la preparación de la consulta falla
                        
                
                    
                } else if ($method === 'GET') {
                    
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$conn=db::conexion();

$query = "SELECT * FROM validacion";
$result = $conn->query($query);

$valid = [];
while ($row = $result->fetch_assoc()) {
    $valid[] = $row;
}

echo json_encode($valid); 

                  //  $result = $controller->getValidacion();
                    // Devolver la respuesta como JSON
                    
                    //header('Content-Type: application/json');
                    //echo json_encode($result);
                } 
                break;
               
        

           
         
    default:
      //  http_response_code(404);
       // echo json_encode(array("error" => "Ruta no encontrada"));
        break;
}

if (strpos($request, $baseUrl.'bank') === 0) {
    // Obtener el userId de la URI
    $parts = explode('/', $request);
    $userId = end($parts); // Obtener el último segmento de la URI

    // Crear la ruta base sin el userId para el switch case
    $baseRoute = $baseUrl.'bank';

    // Manejar las diferentes rutas de bank
    switch ($baseRoute) {
        case $baseUrl.'bank': // Ruta para obtener todos los bancos
            if ($method === 'GET') {
                $controller = new BankController();
                $result = $controller::getBank();
                header('Content-Type: application/json');
                echo json_encode($result);
            }
            case $baseUrl.'bank/'.$userId:
                
                    $rawData = file_get_contents("php://input");
                    $data = json_decode($rawData, true);
       
       // Verificar que los campos requeridos estén presentes en el array
       if (!empty($data['nombre']) || !empty($data['imagen'])) {
           $userId = intval($userId);
           $nombre = $data['nombre'];
           $imagen = $data['imagen'];
       
           // Luego procedes a llamar a createOrUpdate con los datos verificados
           $controller = new BankController();
           $result = $controller->createOrUpdate($userId, ['nombre' => $nombre, 'imagen' => $imagen]);
       } else {
           echo json_encode(['error' => 'Datos incompletos: nombre o imagen faltantes']);
       }
                   
            

            break;
           
        


        
     
    }



} 

if (strpos($request, $baseUrl.'registro-token') === 0) {
    // Obtener el userId de la URI
    $parts = explode('/', $request);
    $userId = end($parts); // Obtener el último segmento de la URI

    // Crear la ruta base sin el userId para el switch case
    $baseRoute = $baseUrl.'registro-token';

    // Manejar las diferentes rutas de bank
    switch ($baseRoute) {
        case $baseUrl.'registro-token': // Ruta para obtener todos los bancos
            if ($method === 'GET') {

            $controller = new RegistroTokenController();
               $result = $controller->getToken();
                header('Content-Type: application/json');
                echo json_encode($result);
            }
            case $baseUrl.'registro-token/'.$userId:
                    $rawData = file_get_contents("php://input");
                    $data = json_decode($rawData, true);
       
       // Verificar que los campos requeridos estén presentes en el array
       if (!empty($data['token'])) {
           $userId = intval($userId);
           $token = $data['token'];
           
       
           // Luego procedes a llamar a createOrUpdate con los datos verificados
           $controller = new RegistroTokenController();
           $result = $controller->createOrUpdate($userId, ['token' => $token]);
           echo json_encode($result);
       } else {
           echo json_encode(['error' => 'Datos incompletos']);
       }
              
                   }

        }

        if (strpos($request, $baseUrl.'paisaportacion') === 0) {
            // Obtener el userId de la URI
            $parts = explode('/', $request);
            $userId = end($parts); // Obtener el último segmento de la URI
        
            // Crear la ruta base sin el userId para el switch case
            $baseRoute = $baseUrl.'paisaportacion';
        
            // Manejar las diferentes rutas de bank
            switch ($baseRoute) {
                case $baseUrl.'paisaportacion': // Ruta para obtener todos los bancos
                    if ($method === 'GET') {
        
                    $controller = new paisAportacionController();
                       $result = $controller->getPais();
                        header('Content-Type: application/json');
                        echo json_encode($result);
                    }
                    case $baseUrl.'paisaportacion/'.$userId:
                        header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// Especifica los métodos HTTP permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Especifica los encabezados permitidos
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
                        if ($method === 'PUT') {
                            $rawData = file_get_contents("php://input");
                            $data = json_decode($rawData, true);
                
                   $userId = intval($userId);
               
                   // Luego procedes a llamar a createOrUpdate con los datos verificados
                   $controller = new paisAportacionController();
                   $result = $controller->createOrUpdate($userId,$data);
                   echo json_encode($result);
        
                        }
                
            
                      
              }
        
                }
        

                
        if (strpos($request, $baseUrl.'transferencia') === 0) {
            // Obtener el userId de la URI
            $parts = explode('/', $request);
            $userId = end($parts); // Obtener el último segmento de la URI
        
            // Crear la ruta base sin el userId para el switch case
            $baseRoute = $baseUrl.'transferencia';
        
            // Manejar las diferentes rutas de bank
            switch ($baseRoute) {
                case $baseUrl.'transferencia': // Ruta para obtener todos los bancos
                    if ($method === 'GET') {
        
                    $controller = new TransferenciaController();
                       $result = $controller->getTransfer();
                        header('Content-Type: application/json');
                        echo json_encode($result);
                    }
                    case $baseUrl.'transferencia/'.$userId:
                            $rawData = file_get_contents("php://input");
                            $data = json_decode($rawData, true);
               
               // Verificar que los campos requeridos estén presentes en el array
               if (!empty($data['tipoDeTransferencia']) && !empty($data['parentesco']) && !empty($data['tiempo']) && !empty($data['motivo'])) {
                $userId = intval($userId);
                $tipoDeTransferencia = $data['tipoDeTransferencia'];
                $parentesco = $data['parentesco'];
                $tiempo = $data['tiempo'];
                $motivo = $data['motivo'];
            
                // Luego procedes a llamar a createOrUpdate con los datos verificados
                $controller = new TransferenciaController();
                $result = $controller->createOrUpdate($userId, [
                    'tipoDeTransferencia' => $tipoDeTransferencia,
                    'parentesco' => $parentesco,
                    'tiempo' => $tiempo,
                    'motivo' => $motivo
                ]);
            
                echo json_encode($result);
               } else {
                   echo json_encode(['error' => 'Datos incompletos']);
               }
                      
                           }
        
                }
        
                
                // Agregar la parte del switch case aquí para manejar la URL específica
               // Obtener la URL actual
            

               // Verificar si la URL tiene la estructura esperada
               $requestUrl = $_SERVER['REQUEST_URI'];

               // URL base donde se esperan las imágenes
               
               
               // Verificar si la URL tiene la estructura esperada para acceder a una imagen
               if (strpos($requestUrl, $baseUrl . 'imagenes/') === 0) {
                   // Obtener el nombre de la imagen desde la URL
                   $parts = explode('/', $requestUrl);
                   $nombreImagen = end($parts);
               
                   // Llamar al método showImage del controlador de imágenes
                   $controller = new ImageController();
                   $controller->showImage("imagen1.webp");
               } 

               if (strpos($request, $baseUrl.'usuarios') === 0) {
                // Obtener el userId de la URI
                $parts = explode('/', $request);
                $userId = end($parts); // Obtener el último segmento de la URI
            
                // Crear la ruta base sin el userId para el switch case
                $baseRoute = $baseUrl.'usuarios';
            
                // Manejar las diferentes rutas de bank
                switch ($baseRoute) {
                    case $baseUrl.'usuarios': // Ruta para obtener todos los bancos
                        if ($method === 'PUT') {
                    $controller=new UserController();
// Recibir datos JSON enviados por POST
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);
$id=intval($userId);
$conn = db::conexion();

    // Verificar si el usuario ya existe utilizando el ID
   /* $existingUserQuery = "SELECT id FROM usuario WHERE id = ?";
    $stmtCheckUser = $conn->prepare($existingUserQuery);
    $stmtCheckUser->bind_param("i", $id);
    $stmtCheckUser->execute();
    $stmtCheckUser->store_result();*/

    // Si el usuario ya existe, realizar la actualización
    
       // $updateQuery = "UPDATE usuario SET usuario = ?, contra = ? WHERE id = ?";
      //  $stmtUpdate = $conn->prepare($updateQuery);
       // $stmtUpdate->bind_param("ssi", $data['usuario'], $data['contra'], $id); // Usar $data['id']
        //$success = $stmtUpdate->execute();
        $insertQuery = "INSERT INTO usuario ( usuario, contra) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ss", $data['usuario'], $data['contra']);
        $success = $stmtInsert->execute();

       // $stmtCheckUser->close();
       /// $stmtUpdate->close();
        //$conn->close();
        
        echo json_encode("insertado con exitos",$success); // Devuelve true si la actualización fue exitosa
    /*else {
        // Si el usuario no existe, insertarlo
        $insertQuery = "INSERT INTO usuario ( usuario, contra) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ss", $data['usuario'], $data['contra']);
        $success = $stmtInsert->execute();

        $stmtCheckUser->close();
        $stmtInsert->close();
        $conn->close();
        
        echo json_encode($success); // Devuelve true si la inserción fue exitosa
    }*/
// Verificar si se recibieron los datos esperados
/*
if (!empty($data['usuario']) && !empty($data['contra'])) {
    $userId=intval($userId);
    $usuario=$data['usuario'];
    $contra=$data['contra'];
    $result = $controller::createUser($userId,['usuario'=>$usuario,'contra'=>$contra]);
    echo json_encode(['success' => $result]);
} else {
    echo json_encode(['error' => 'Datos incompletos']);
}*/




                        }else {
                            echo json_encode(['error' => 'Datos incompletos compatible']);
                        }
                    }
                    }
            