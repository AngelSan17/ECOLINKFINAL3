<?php
class Comentario {
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = "comentarios";

    // Propiedades del objeto
    public $id_comentario;
    public $id_tema;
    public $id_usuario;
    public $texto_comentario;
    public $fecha_comentario;

    // Constructor con conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer comentarios
    function read() {
        // Consulta para leer todos los comentarios
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un nuevo comentario
    function create() {
        // Consulta para insertar un nuevo comentario
        $query = "INSERT INTO " . $this->table_name . " (id_tema, id_usuario, texto_comentario) VALUES (:id_tema, :id_usuario, :texto_comentario)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar los datos
        $this->id_tema = htmlspecialchars(strip_tags($this->id_tema));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->texto_comentario = htmlspecialchars(strip_tags($this->texto_comentario));

        // Vincular los valores
        $stmt->bindParam(":id_tema", $this->id_tema);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":texto_comentario", $this->texto_comentario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Implementa los métodos update y delete si son necesarios
}
?>
