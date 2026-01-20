<?php
class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ==========================
    // STUDENT FEATURES
    // ==========================

    // 1. ADD PRODUCT
    public function addProduct($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, title, price, category, condition_type, description, image, status) 
                  VALUES (:user_id, :title, :price, :category, :condition, :desc, :img, 'pending')";
        
        $stmt = $this->conn->prepare($query);

        // Bind Values
        $stmt->bindValue(':user_id', $data['user_id']);
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':category', $data['category']);
        $stmt->bindValue(':condition', $data['condition']); 
        $stmt->bindValue(':desc', $data['description']);
        $stmt->bindValue(':img', $data['image']);

        return $stmt->execute();
    }

    // 2. GET APPROVED PRODUCTS (Homepage)
    public function getProducts() {
        $query = "SELECT products.*, products.id as productId, 
                         users.fullname, users.profile_image, users.student_id 
                  FROM " . $this->table . " 
                  INNER JOIN users ON products.user_id = users.id 
                  WHERE products.status = 'approved' 
                  ORDER BY products.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 3. GET PRODUCT BY ID (Details)
    public function getProductById($id) {
        $query = "SELECT products.*, 
                         users.fullname, 
                         users.department, 
                         users.batch, 
                         users.profile_image as seller_image,
                         users.id as seller_id
                  FROM " . $this->table . "
                  INNER JOIN users ON products.user_id = users.id
                  WHERE products.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // 4. GET PRODUCTS BY USER (My Listings)
    public function getProductsByUser($id) {
        $query = "SELECT products.*, products.id as productId 
                  FROM " . $this->table . " 
                  WHERE user_id = :id 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 5. UPDATE PRODUCT
    public function updateProduct($data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, price = :price, category = :category, 
                      condition_type = :condition, description = :desc";
        
        if (!empty($data['image'])) {
            $query .= ", image = :img";
        }
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':category', $data['category']);
        $stmt->bindValue(':condition', $data['condition']);
        $stmt->bindValue(':desc', $data['description']);
        $stmt->bindValue(':id', $data['id']);

        if (!empty($data['image'])) {
            $stmt->bindValue(':img', $data['image']);
        }

        return $stmt->execute();
    }

    // 6. DELETE PRODUCT
    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // 7. SEARCH FILTER
    public function getFilteredProducts($filters) {
        $sql = "SELECT products.*, products.id as productId 
                FROM " . $this->table . " 
                INNER JOIN users ON products.user_id = users.id 
                WHERE products.status = 'approved'";
        
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE :search OR description LIKE :search)";
        }
        if (!empty($filters['category']) && $filters['category'] != 'All') {
            $sql .= " AND category = :cat";
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min";
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max";
        }

        // Sorting
        $sort = "DESC";
        if (!empty($filters['date_sort']) && $filters['date_sort'] == 'oldest') {
            $sort = "ASC";
        }
        $sql .= " ORDER BY products.created_at " . $sort;

        $stmt = $this->conn->prepare($sql);

        if (!empty($filters['search'])) {
            $stmt->bindValue(':search', '%' . $filters['search'] . '%');
        }
        if (!empty($filters['category']) && $filters['category'] != 'All') {
            $stmt->bindValue(':cat', $filters['category']);
        }
        if (!empty($filters['min_price'])) {
            $stmt->bindValue(':min', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $stmt->bindValue(':max', $filters['max_price']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ==========================
    // INTEREST & NOTIFICATIONS
    // ==========================

    public function isInterested($productId, $userId) {
        $query = "SELECT * FROM interests WHERE product_id = :pid AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pid', $productId);
        $stmt->bindValue(':uid', $userId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function addInterest($productId, $userId) {
        $query = "INSERT INTO interests (product_id, user_id) VALUES (:pid, :uid)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pid', $productId);
        $stmt->bindValue(':uid', $userId);
        return $stmt->execute();
    }

    public function removeInterest($productId, $userId) {
        $query = "DELETE FROM interests WHERE product_id = :pid AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pid', $productId);
        $stmt->bindValue(':uid', $userId);
        return $stmt->execute();
    }

    public function getInterestedBuyers($productId) {
        $query = "SELECT users.fullname, users.mobile, users.profile_image, users.email 
                  FROM interests 
                  INNER JOIN users ON interests.user_id = users.id 
                  WHERE interests.product_id = :pid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pid', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRecentProducts($excludeUserId) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE status = 'approved' AND user_id != :uid 
                  ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':uid', $excludeUserId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSearchSuggestions($keyword) {
        $query = "SELECT id, title FROM " . $this->table . " WHERE title LIKE :key LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':key', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ==========================
    // ADMIN FEATURES
    // ==========================

    public function getPendingProducts() {
        $query = "SELECT products.*, users.fullname, users.student_id 
                  FROM " . $this->table . " 
                  INNER JOIN users ON products.user_id = users.id 
                  WHERE products.status = 'pending' 
                  ORDER BY products.created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllProducts() {
        // Added users.student_id to the SELECT list
        $query = "SELECT products.*, users.fullname, users.student_id FROM " . $this->table . " 
                  INNER JOIN users ON products.user_id = users.id 
                  ORDER BY products.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function approveProduct($id) {
        $query = "UPDATE " . $this->table . " SET status = 'approved' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function deleteProductByAdmin($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function searchProducts($term) {
        $query = "SELECT * FROM " . $this->table . " WHERE title LIKE :term OR category LIKE :term";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':term', "%$term%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>