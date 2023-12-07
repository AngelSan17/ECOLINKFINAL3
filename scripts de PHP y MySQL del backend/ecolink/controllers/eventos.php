<?php
include_once 'config/database.php';
include_once 'models/evento.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Preparar un objeto evento
$evento = new Evento($db);

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Leer eventos
        $stmt = $evento->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $eventos_arr = array();
            $eventos_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $evento_item = array(
                    "id_evento" => $id_evento,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "fecha" => $fecha,
                    "ubicacion" => $ubicacion,
                    "id_usuario" => $id_usuario
                );

                array_push($eventos_arr["records"], $evento_item);
            }

            http_response_code(200);
            echo json_encode($eventos_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron eventos."));
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"));
        
            if (!empty($data->nombre) && !empty($data->fecha) && !empty($data->id_usuario)) {
                $evento->nombre = $data->nombre;
                $evento->descripcion = $data->descripcion;
                $evento->fecha = $data->fecha;
                $evento->ubicacion = $data->ubicacion;
                $evento->id_usuario = $data->id_usuario;
        
                if ($evento->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Evento creado exitosamente."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "No se pudo crear el evento."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "No se pudo crear el evento. Datos incompletos."));
            }
            break;
        

         case 'PUT':
             $data = json_decode(file_get_contents("php://input"));
            
            if (!empty($data->id_evento)) {
                    $evento->id_evento = $data->id_evento;
                    $evento->nombre = $data->nombre;
                    $evento->descripcion = $data->descripcion;
                    $evento->fecha = $data->fecha;
                    $evento->ubicacion = $data->ubicacion;
                    $evento->id_usuario = $data->id_usuario; 
            
                 if ($evento->update()) {
                        http_response_code(200);
                        echo json_encode(array("message" => "Evento actualizado exitosamente."));
                } else {
                        http_response_code(503);
                        echo json_encode(array("message" => "No se pudo actualizar el evento."));
                 }
            } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "No se pudo actualizar el evento. Falta el ID."));
            }
            break;
            

            case 'DELETE':
                $data = json_decode(file_get_contents("php://input"));
            
                if (!empty($data->id_evento)) {
                    $evento->id_evento = $data->id_evento;
            
                    if ($evento->delete()) {
                        http_response_code(200);
                        echo json_encode(array("message" => "Evento eliminado exitosamente."));
                    } else {
                        http_response_code(503);
                        echo json_encode(array("message" => "No se pudo eliminar el evento."));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "No se pudo eliminar el evento. Falta el ID."));
                }
                break;
            
}
?>
