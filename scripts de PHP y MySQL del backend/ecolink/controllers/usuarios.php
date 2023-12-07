<?php
include_once 'config/database.php';
include_once 'models/usuario.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Preparar un objeto usuario
$usuario = new Usuario($db);

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Obtener usuarios
        $stmt = $usuario->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $usuarios_arr = array();
            $usuarios_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $usuario_item = array(
                    "id_usuario" => $id_usuario,
                    "nombre_usuario" => $nombre_usuario,
                    "email" => $email
                    
                );

                array_push($usuarios_arr["records"], $usuario_item);
            }

            http_response_code(200);
            echo json_encode($usuarios_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron usuarios."));
        }
        break;

    case 'POST':
        // Crear usuario
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id_usuario) && !empty($data->nombre_usuario) && !empty($data->email)) {
            $usuario->id_usuario = $data->id_usuario;
            $usuario->nombre_usuario = $data->nombre_usuario;
            $usuario->email = $data->email;
            

            if ($usuario->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Usuario Creado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el usuario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se creo el usuario, Informacion incompleta."));
        }
        break;

        case 'PUT':
            // Actualizar usuario
            $data = json_decode(file_get_contents("php://input"));
            $usuario->id_usuario = $data->id_usuario;
            $usuario->nombre_usuario = $data->nombre_usuario;
            $usuario->email = $data->email;
            // todos los campos necesarios están presentes
            
    
            if ($usuario->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Usuario actualizado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar el usuario."));
            }
            break;
    
        case 'DELETE':
            // Eliminar usuario
            
            $data = json_decode(file_get_contents("php://input"));
            $usuario->id_usuario = $data->id_usuario;
    
            if ($usuario->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Usuario eliminado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo eliminar el usuario."));
            }
            break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Metodo no permitido"));
        break;
}
?>
