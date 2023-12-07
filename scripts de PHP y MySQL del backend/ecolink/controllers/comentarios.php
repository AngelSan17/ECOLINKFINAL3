<?php
include_once 'config/database.php';
include_once 'models/comentario.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Preparar un objeto Comentario
$comentario = new Comentario($db);

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Leer comentarios
        $stmt = $comentario->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $comentarios_arr = array();
            $comentarios_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $comentario_item = array(
                    "id_comentario" => $id_comentario,
                    "id_tema" => $id_tema,
                    "id_usuario" => $id_usuario,
                    "texto_comentario" => $texto_comentario,
                    "fecha_comentario" => $fecha_comentario
                );

                array_push($comentarios_arr["records"], $comentario_item);
            }

            http_response_code(200);
            echo json_encode($comentarios_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron comentarios."));
        }
        break;

    case 'POST':
        // Crear un nuevo comentario
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id_tema) && !empty($data->id_usuario) && !empty($data->texto_comentario)) {
            $comentario->id_tema = $data->id_tema;
            $comentario->id_usuario = $data->id_usuario;
            $comentario->texto_comentario = $data->texto_comentario;
            // fecha_comentario se establece automáticamente en la base de datos

            if ($comentario->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Comentario creado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el comentario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se pudo crear el comentario. Datos incompletos."));
        }
        break;

    // Implementar los métodos PUT y DELETE si son necesarios y aplicables

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido"));
        break;
}
?>
