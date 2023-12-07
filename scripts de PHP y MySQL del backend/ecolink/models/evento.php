<?php
class Evento {
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = "eventos";

    // Propiedades del objeto
    public $id_evento;
    public $nombre;
    public $descripcion;
    public $fecha;
    public $ubicacion;
    public $id_usuario;

    // Constructor con conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer eventos
    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un evento
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, fecha=:fecha, ubicacion=:ubicacion, id_usuario=:id_usuario";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":id_usuario", $this->id_usuario);

        // Ejecutar
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para actualizar un evento
    function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, fecha=:fecha, ubicacion=:ubicacion WHERE id_evento=:id_evento";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
       

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":id_evento", $this->id_evento);

        
        // Ejecutar
        if ($stmt->execute()) {
            return true;
        }

        return false;
       
    }

    // Método para eliminar un evento
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_evento=:id_evento";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->id_evento = htmlspecialchars(strip_tags($this->id_evento));

        // Vincular el id del evento
        $stmt->bindParam(":id_evento", $this->id_evento);

        // Ejecutar
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
