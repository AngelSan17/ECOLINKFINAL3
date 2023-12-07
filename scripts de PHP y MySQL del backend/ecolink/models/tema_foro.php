<?php
class TemaForo {
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = "temas_foro";

    // Propiedades del objeto
    public $id_tema;
    public $id_usuario;
    public $nombre_tema;
    public $descripcion;
    public $fecha_creacion;

    // Constructor con conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer todos los temas del foro
    function read() {
        // Consulta para leer todos los temas
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un nuevo tema del foro
    function create() {
        // Consulta para insertar un nuevo tema
        $query = "INSERT INTO " . $this->table_name . " SET id_usuario=:id_usuario, nombre_tema=:nombre_tema, descripcion=:descripcion, fecha_creacion=:fecha_creacion";

        $stmt = $this->conn->prepare($query);

        // Sanitizar los datos
        $this->nombre_tema=htmlspecialchars(strip_tags($this->nombre_tema));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
        // La fecha de creación  asignada automáticamente por MySQL

        // Vincular los valores
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombre_tema", $this->nombre_tema);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha_creacion", $this->fecha_creacion);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para actualizar un tema del foro
    function update() {
        // Consulta para actualizar un tema existente
        $query = "UPDATE " . $this->table_name . " SET nombre_tema=:nombre_tema, descripcion=:descripcion WHERE id_tema=:id_tema AND id_usuario=:id_usuario";

        $stmt = $this->conn->prepare($query);

        // Sanitizar los datos
        $this->id_tema=htmlspecialchars(strip_tags($this->id_tema));
        $this->nombre_tema=htmlspecialchars(strip_tags($this->nombre_tema));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));

        // Vincular los valores
        $stmt->bindParam(":id_tema", $this->id_tema);
        $stmt->bindParam(":nombre_tema", $this->nombre_tema);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":id_usuario", $this->id_usuario); // validar que el usuario tiene permiso para actualizar

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para eliminar un tema del foro
    function delete() {
        // Consulta para eliminar un tema
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tema=:id_tema AND id_usuario=:id_usuario";

        $stmt = $this->conn->prepare($query);

        // Sanitizar los datos
        $this->id_tema=htmlspecialchars(strip_tags($this->id_tema));
        $this->id_usuario=htmlspecialchars(strip_tags($this->id_usuario));

        // Vincular el id del tema y el usuario
        $stmt->bindParam(":id_tema", $this->id_tema);
        $stmt->bindParam(":id_usuario", $this->id_usuario); // Verificar que solo el usuario que creó el tema pueda eliminarlo

        // Ejecutar la consulta
        if ($stmt->execute()) {
            error_log("Tema eliminado: " . $this->id_tema);
            return true;
        } else {
            error_log("Error al eliminar tema: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }
}
?>
