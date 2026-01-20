<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. LOGIN USER (Updated for Stability)
    public function login($emailOrId, $password) {
        // We use two different placeholders (:email and :sid) to prevent database errors
        $query = "SELECT * FROM users WHERE email = :email OR student_id = :sid";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind the same input to BOTH placeholders
        $stmt->bindValue(':email', $emailOrId);
        $stmt->bindValue(':sid', $emailOrId);
        
        $stmt->execute();
        
        // Fetch the user
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // Check if user exists
        if($row) {
            $hashed_password = $row->password;
            // Verify Password
            if(password_verify($password, $hashed_password)) {
                return $row; // Success
            } else {
                return false; // Wrong Password
            }
        } else {
            return false; // User not found
        }
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

    // 3. REGISTER NEW USER (Correct Column Order)
    public function register($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (fullname, email, student_id, mobile, department, batch, gender, password, security_answer, role, status) 
                 VALUES 
                 (:name, :email, :sid, :mobile, :dept, :batch, :gender, :pass, :sec_ans, 'student', 'pending')";
        
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
        $stmt->bindParam(':sec_ans', $data['security_answer']);

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
        $query = "UPDATE " . $this->table . " SET mobile = :mobile, batch = :batch, secondary_email = :sec_email";
        
        if(!empty($data['profile_image'])) {
            $query .= ", profile_image = :image";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':mobile', $data['mobile']);
        $stmt->bindParam(':batch', $data['batch']);
        $stmt->bindParam(':sec_email', $data['secondary_email']);
        $stmt->bindParam(':id', $data['id']);

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