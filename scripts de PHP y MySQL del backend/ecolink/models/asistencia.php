<?php
class Asistencia {
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = "asistencias";

    // Propiedades del objeto
    public $id_asistencia;
    public $id_usuario;
    public $id_evento;
    public $estado_asistencia; // booleano: 1 para confirmado, 0 para cancelado

    // Constructor con conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer asistencias
    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Consulta para leer asistencias de un evento específico
    function readByEvento($id_evento) {
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_evento = :id_evento";
    
        $stmt = $this->conn->prepare($query);
    
        // Sanitizar y vincular el id del evento
        $id_evento = htmlspecialchars(strip_tags($id_evento));
        $stmt->bindParam(":id_evento", $id_evento);
    
        $stmt->execute();
        return $stmt;
    }

    // Método para crear una nueva asistencia
    function create() {
        $query = "INSERT INTO " . $this->table_name . " (id_usuario, id_evento, estado_asistencia) VALUES (:id_usuario, :id_evento, 1)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->id_evento = htmlspecialchars(strip_tags($this->id_evento));

        // Vincular valores
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":id_evento", $this->id_evento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para actualizar el estado de asistencia
    function update() {
        $query = "UPDATE " . $this->table_name . " SET estado_asistencia = :estado_asistencia WHERE id_asistencia = :id_asistencia";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->id_asistencia = htmlspecialchars(strip_tags($this->id_asistencia));
        $this->estado_asistencia = htmlspecialchars(strip_tags($this->estado_asistencia));

        // Vincular valores
        $stmt->bindParam(":id_asistencia", $this->id_asistencia);
        $stmt->bindParam(":estado_asistencia", $this->estado_asistencia);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // No se incluye un método delete ya que no es aplicable en este caso
}
?>
