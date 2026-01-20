<?php
class PagesController {
    private $productModel;
    private $userModel; // Added User Model

    public function __construct() {
        // 1. Force Login
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
            exit;
        }

        // 2. Load Models
        require_once APPROOT . '/models/Product.php';
        require_once APPROOT . '/models/User.php'; // Load User Model
        
        $this->productModel = new Product();
        $this->userModel = new User(); // Init User Model
    }

    public function index() {
        
        // CHECK: Is it a search request?
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            // Yes -> Get filtered products
            $products = $this->productModel->searchProducts($_GET['search']);
        } else {
            // No -> Get all products
            $products = $this->productModel->getProducts();
        }

        // Get Current User Info
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'title' => 'CampusKart Home',
            'products' => $products,
            'user' => $user
        ];

        require_once '../app/views/pages/index.php';
    }
}
?>