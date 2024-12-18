<?php
class UserModel {
    private $db;

    public function __construct($host, $user, $password, $dbname) {
        $this->db = new mysqli($host, $user, $password, $dbname);
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function createUser($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
        return $stmt->execute();
    }

    public function authenticateUser($username, $password) {
        $stmt = $this->db->prepare("SELECT password FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return password_verify($password, $row['password']);
        }
        return false;
    }
}
?>
