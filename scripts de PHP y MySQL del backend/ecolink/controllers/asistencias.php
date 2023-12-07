<?php
include_once 'config/database.php';
include_once 'models/asistencia.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Preparar un objeto Asistencia
$asistencia = new Asistencia($db);

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Leer asistencias
        $stmt = $asistencia->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $asistencias_arr = array();
            $asistencias_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $asistencia_item = array(
                    "id_asistencia" => $id_asistencia,
                    "id_usuario" => $id_usuario,
                    "id_evento" => $id_evento,
                    "estado_asistencia" => $estado_asistencia
                );

                array_push($asistencias_arr["records"], $asistencia_item);
            }

            http_response_code(200);
            echo json_encode($asistencias_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron asistencias."));
        }
        break;

    case 'POST':
        // Confirmar asistencia a un evento
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id_usuario) && !empty($data->id_evento)) {
            $asistencia->id_usuario = $data->id_usuario;
            $asistencia->id_evento = $data->id_evento;
            $asistencia->estado_asistencia = 1; // Confirmado por defecto

            if ($asistencia->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Asistencia confirmada exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo confirmar la asistencia."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para confirmar la asistencia."));
        }
        break;

    case 'PUT':
        // Actualizar el estado de asistencia (confirmar/cancelar)
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id_asistencia) && isset($data->estado_asistencia)) {
            $asistencia->id_asistencia = $data->id_asistencia;
            $asistencia->estado_asistencia = $data->estado_asistencia; // 1 o 0

            if ($asistencia->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Estado de asistencia actualizado exitosamente."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar el estado de asistencia."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos para actualizar el estado de asistencia."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido"));
        break;
}
?>
