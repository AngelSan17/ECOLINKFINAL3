<?php
include_once 'config/database.php';
include_once 'models/tema_foro.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Preparar un objeto TemaForo
$temaForo = new TemaForo($db);

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Leer temas del foro
        $stmt = $temaForo->read();
        $num = $stmt->rowCount();
    
        if ($num > 0) {
            $temas_arr = array();
            $temas_arr["records"] = array();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $tema_item = array(
                    "id_tema" => $id_tema,
                    "id_usuario" => $id_usuario,
                    "nombre_tema" => $nombre_tema,
                    "descripcion" => $descripcion,
                    "fecha_creacion" => $fecha_creacion
                );
    
                array_push($temas_arr["records"], $tema_item);
            }
    
            http_response_code(200);
            echo json_encode($temas_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron temas en el foro."));
        }
        break;
    

        case 'POST':
            // Crear un nuevo tema del foro
            $data = json_decode(file_get_contents("php://input"));
        
            // Asegúrate de que los campos necesarios estén presentes
            if (
                !empty($data->id_usuario) &&
                !empty($data->nombre_tema) &&
                !empty($data->descripcion)
            ) {
                // Establecer las propiedades del objeto TemaForo
                $temaForo->id_usuario = $data->id_usuario;
                $temaForo->nombre_tema = $data->nombre_tema;
                $temaForo->descripcion = $data->descripcion;
                // La fecha de creación puede ser asignada automáticamente en el modelo o aquí
                // $temaForo->fecha_creacion = date('Y-m-d H:i:s');
        
                if ($temaForo->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Tema creado exitosamente."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "No se pudo crear el tema."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "No se pudo crear el tema. Datos incompletos."));
            }
            break;
        

            case 'PUT':
                
                $data = json_decode(file_get_contents("php://input"));
            
                if (!empty($data->id_tema) && !empty($data->id_usuario)) {
                    $temaForo->id_tema = $data->id_tema;
                    $temaForo->id_usuario = $data->id_usuario;
                    $temaForo->nombre_tema = $data->nombre_tema;
                    $temaForo->descripcion = $data->descripcion;
                    // No actualizamos la fecha de creación, ya que es un registro histórico
            
                    if ($temaForo->update()) {
                        http_response_code(200);
                        echo json_encode(array("message" => "Tema actualizado exitosamente."));
                    } else {
                        http_response_code(503);
                        echo json_encode(array("message" => "No se pudo actualizar el tema."));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "No se pudo actualizar el tema. Falta el ID o datos incompletos."));
                }
                break;
            

                case 'DELETE':
                    
                    $data = json_decode(file_get_contents("php://input"));
                    error_log("Datos recibidos: " . print_r($data, true));

                    if (!empty($data->id_tema) && !empty($data->id_usuario)) {
                        $temaForo->id_tema = $data->id_tema;
                        $temaForo->id_usuario = $data->id_usuario;
                
                        if ($temaForo->delete()) {
                            http_response_code(200);
                            echo json_encode(array("message" => "Tema eliminado exitosamente."));
                        } else {
                            http_response_code(503);
                            echo json_encode(array("message" => "No se pudo eliminar el tema."));
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "No se pudo eliminar el tema. Falta el ID."));
                    }
                    break;
                

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido"));
        break;
}
?>
