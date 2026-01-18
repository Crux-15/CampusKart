<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. LOGIN USER
    public function login($email_or_id, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :input OR student_id = :input LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':input', $email_or_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        if($row) {
            // In a real app, use password_verify($password, $row->password)
            if($password == $row->password) {
                return $row;
            }
        }
        return false;
    }

    // 2. FIND USER BY EMAIL (For Registration Check)
    public function findUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // 3. REGISTER NEW USER
    public function register($data) {
        $query = "INSERT INTO " . $this->table . " (fullname, email, student_id, mobile, department, batch, gender, password, role) VALUES (:name, :email, :sid, :mobile, :dept, :batch, :gender, :pass, 'student')";
        
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':name', $data['fullname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':sid', $data['student_id']);
        $stmt->bindParam(':mobile', $data['mobile']);
        $stmt->bindParam(':dept', $data['department']);
        $stmt->bindParam(':batch', $data['batch']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':pass', $data['password']);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get User by ID (For the Account Page)
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Update User Profile
    public function updateProfile($data) {
        // We only update Mobile, Batch, Secondary Email, and Profile Image (if provided)
        $query = "UPDATE " . $this->table . " SET mobile = :mobile, batch = :batch, secondary_email = :sec_email";
        
        // Only update image if a new one was uploaded
        if(!empty($data['profile_image'])) {
            $query .= ", profile_image = :image";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind Values
        $stmt->bindParam(':mobile', $data['mobile']);
        $stmt->bindParam(':batch', $data['batch']);
        $stmt->bindParam(':sec_email', $data['secondary_email']);
        $stmt->bindParam(':id', $data['id']);

        //add image param if exists
        if(!empty($data['profile_image'])) {
            $stmt->bindParam(':image', $data['profile_image']);
        }

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>