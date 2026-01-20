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
// --- ADMIN FEATURES ---

    // 1. Get All Pending Users
    public function getPendingUsers() {
        $query = "SELECT * FROM " . $this->table . " WHERE role = 'student' AND status = 'pending' ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. Get ALL Users
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 3. Approve a User
    public function approveUser($id) {
        $query = "UPDATE " . $this->table . " SET status = 'approved' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 4. Delete/Reject a User
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // 5. Create New Admin
    // 5. Create New Admin (Updated for Database Constraints)
    // 5. Create New Admin (Now supports ALL fields)
    public function registerAdmin($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (fullname, email, password, student_id, mobile, department, batch, gender, role, status, security_answer) 
                 VALUES 
                 (:name, :email, :pass, :sid, :mobile, :dept, :batch, :gender, 'admin', 'approved', :sec_ans)";
        
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':name', $data['fullname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':pass', $data['password']);
        $stmt->bindParam(':sid', $data['student_id']);
        $stmt->bindParam(':mobile', $data['mobile']);
        $stmt->bindParam(':dept', $data['department']);
        $stmt->bindParam(':batch', $data['batch']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':sec_ans', $data['security_answer']);

        return $stmt->execute();
    }

    // --- SEARCH FEATURE (AJAX) ---
    public function searchUsers($term) {
        $query = "SELECT * FROM users WHERE student_id LIKE :term OR fullname LIKE :term";
        $stmt = $this->conn->prepare($query);
        
        $searchTerm = "%" . $term . "%";
        $stmt->bindParam(':term', $searchTerm);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADMIN: Update User Details
    public function updateUserByAdmin($data) {
        $query = "UPDATE " . $this->table . " SET 
                  fullname = :name, 
                  email = :email, 
                  student_id = :sid, 
                  mobile = :mobile, 
                  department = :dept, 
                  batch = :batch, 
                  gender = :gender, 
                  role = :role 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':name', $data['fullname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':sid', $data['student_id']);
        $stmt->bindParam(':mobile', $data['mobile']);
        $stmt->bindParam(':dept', $data['department']);
        $stmt->bindParam(':batch', $data['batch']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':id', $data['id']);

        return $stmt->execute();
    }

    // CHECK USER FOR RESET (Name + ID + Email)
    public function findUserForReset($name, $sid, $email) {
        $query = "SELECT * FROM users WHERE fullname = :name AND student_id = :sid AND email = :email";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':sid', $sid);
        $stmt->bindValue(':email', $email);
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // VERIFY SECURITY ANSWER
    public function checkSecurityAnswer($id, $answer) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Check if answer matches (Case Insensitive)
        if ($user && strtolower(trim($user->security_answer)) == strtolower(trim($answer))) {
            return true;
        } else {
            return false;
        }
    }

    // RESET PASSWORD (Final Step)
    public function resetPassword($id, $newHash) {
        $query = "UPDATE users SET password = :pass WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindValue(':pass', $newHash);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

}
?>