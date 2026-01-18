<?php
class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. Get All Products
    public function getProducts() {
        $query = "SELECT *, 
                  products.id as productId, 
                  users.id as userId,
                  products.created_at as productCreated
                  FROM " . $this->table . " 
                  INNER JOIN users
                  ON products.user_id = users.id
                  ORDER BY products.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. Add Product
    public function addProduct($data) {
        $query = "INSERT INTO " . $this->table . " (user_id, title, category, price, description, image) VALUES (:user_id, :title, :category, :price, :description, :image)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image', $data['image']);

        return $stmt->execute();
    }

    // 3. Get Products by User
    public function getProductsByUser($id) {
        $query = "SELECT *, 
                  products.id as productId, 
                  users.id as userId,
                  products.created_at as productCreated
                  FROM " . $this->table . " 
                  INNER JOIN users
                  ON products.user_id = users.id
                  WHERE products.user_id = :id
                  ORDER BY products.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 4. Get Single Product (With Seller Info)
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
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // 5. Update Product
    public function updateProduct($data) {
        $query = "UPDATE " . $this->table . " SET title = :title, category = :category, price = :price, description = :description";
        if(!empty($data['image'])) {
            $query .= ", image = :image";
        }
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':id', $data['id']);

        if(!empty($data['image'])) {
            $stmt->bindParam(':image', $data['image']);
        }

        return $stmt->execute();
    }

    // 6. Delete Product
    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // --- INTEREST / BOOKING SYSTEM ---
    public function isInterested($productId, $userId) {
        $query = "SELECT * FROM interests WHERE product_id = :p_id AND user_id = :u_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':p_id', $productId);
        $stmt->bindParam(':u_id', $userId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function addInterest($productId, $userId) {
        $query = "INSERT INTO interests (product_id, user_id) VALUES (:p_id, :u_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':p_id', $productId);
        $stmt->bindParam(':u_id', $userId);
        return $stmt->execute();
    }

    public function removeInterest($productId, $userId) {
        $query = "DELETE FROM interests WHERE product_id = :p_id AND user_id = :u_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':p_id', $productId);
        $stmt->bindParam(':u_id', $userId);
        return $stmt->execute();
    }

    public function getInterestedBuyers($productId) {
        $query = "SELECT users.fullname, users.mobile, users.profile_image 
                  FROM interests 
                  INNER JOIN users ON interests.user_id = users.id 
                  WHERE interests.product_id = :p_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':p_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get recent products for notifications (Limit 5, exclude own)
    public function getRecentProducts($excludeUserId) {
        $query = "SELECT id, title, created_at 
                  FROM " . $this->table . " 
                  WHERE user_id != :uid 
                  ORDER BY created_at DESC 
                  LIMIT 5";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $excludeUserId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // SEARCH: Get full products based on keyword
    public function searchProducts($keyword) {
        $keyword = "%$keyword%";
        $query = "SELECT *, 
                  products.id as productId, 
                  users.id as userId,
                  products.created_at as productCreated
                  FROM " . $this->table . " 
                  INNER JOIN users ON products.user_id = users.id
                  WHERE title LIKE :key OR category LIKE :key OR description LIKE :key
                  ORDER BY products.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // SUGGEST: Get top 3 titles for the dropdown
    public function getSearchSuggestions($keyword) {
        $keyword = "%$keyword%";
        $query = "SELECT id, title FROM " . $this->table . " 
                  WHERE title LIKE :key 
                  LIMIT 3";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // FILTER: Advanced search with categories, price, and sort
    public function getFilteredProducts($filters) {
        // Base Query
        $sql = "SELECT *, 
                products.id as productId, 
                users.id as userId,
                products.created_at as productCreated
                FROM " . $this->table . " 
                INNER JOIN users ON products.user_id = users.id 
                WHERE 1=1"; 

        // 1. Search Keyword
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE :search OR description LIKE :search)";
        }

        // 2. Category
        if (!empty($filters['category']) && $filters['category'] != 'All') {
            $sql .= " AND category = :cat";
        }

        // 3. Min Price
        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min";
        }

        // 4. Max Price
        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max";
        }

        // 5. Sorting
        $sort = "DESC"; // Default Newest
        if (!empty($filters['date_sort']) && $filters['date_sort'] == 'oldest') {
            $sort = "ASC";
        }
        $sql .= " ORDER BY products.created_at " . $sort;

        // Prepare Statement
        $stmt = $this->conn->prepare($sql);

        // Bind Values
        if (!empty($filters['search'])) {
            $key = "%" . $filters['search'] . "%";
            $stmt->bindValue(':search', $key);
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


}
?>