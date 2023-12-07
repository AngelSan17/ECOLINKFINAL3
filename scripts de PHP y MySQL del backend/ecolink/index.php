<?php
include_once 'config/database.php';
include_once 'models/usuario.php';
include_once 'models/evento.php';
include_once 'models/tema_foro.php';
include_once 'models/comentario.php';
include_once 'models/asistencia.php';

$database = new Database();
$db = $database->getConnection();

// Obtener la parte de la URL que indica el recurso
$url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $url[1]; // Este índice depende de tu estructura URL específica

switch ($resource) {
    case 'usuarios':
        require 'controllers/usuarios.php';
        break;
    
    case 'eventos':
        require 'controllers/eventos.php';
        break;

    case 'temas_foro':
        require 'controllers/temas_foro.php';
        break;

    case 'comentarios':
        require 'controllers/comentarios.php';
        break;

     case 'asistencias':
        require 'controllers/asistencias.php';
        break;


    default:
        http_response_code(404);
        echo json_encode(array("message" => "Not found"));
        break;
}
?>
