<?php
class Message {
    private $conn;
    private $table = 'messages';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Send a new message
    public function send($data) {
        $query = "INSERT INTO " . $this->table . " (sender_id, receiver_id, product_id, message) VALUES (:sender_id, :receiver_id, :product_id, :message)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':sender_id', $data['sender_id']);
        $stmt->bindParam(':receiver_id', $data['receiver_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':message', $data['message']);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get Inbox Messages for a specific user
    // Get Full Conversation History
    public function getInbox($userId) {
        // This query fetches messages where the user is EITHER the sender OR receiver.
        // It joins the users table to get the "Other Person's" details.
        $query = "SELECT m.*, 
                         p.title as product_title,
                         -- If I am sender, get receiver's name. If I am receiver, get sender's name.
                         u.fullname as other_name,
                         u.profile_image as other_image,
                         u.id as other_id
                  FROM " . $this->table . " m
                  INNER JOIN products p ON m.product_id = p.id
                  INNER JOIN users u ON (CASE WHEN m.sender_id = :uid THEN m.receiver_id ELSE m.sender_id END) = u.id
                  WHERE m.receiver_id = :uid OR m.sender_id = :uid
                  ORDER BY m.created_at ASC"; // Oldest first (like a real chat)

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Delete entire conversation between two users
    public function deleteConversation($userId, $partnerId) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE (sender_id = :uid AND receiver_id = :pid) 
                     OR (sender_id = :pid AND receiver_id = :uid)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':pid', $partnerId);
        
        return $stmt->execute();
    }

    
}
?>