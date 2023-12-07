<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $nombre_usuario;
    public $email;
    // ... otros atributos ...

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer todos los usuarios
    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un nuevo usuario
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET id_usuario=:id_usuario, nombre_usuario=:nombre_usuario, email=:email";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Vincular datos
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para actualizar un usuario
    function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre_usuario = :nombre_usuario, email = :email WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);

        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para eliminar un usuario
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);

        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));

        $stmt->bindParam(1, $this->id_usuario);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
