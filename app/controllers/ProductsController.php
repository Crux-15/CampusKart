<?php
class ProductsController
{
    private $productModel;
    private $userModel;
    private $messageModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
            exit;
        }

        require_once APPROOT . '/models/Product.php';
        require_once APPROOT . '/models/User.php';
        
        // Ensure Message Model exists before loading
        if(file_exists(APPROOT . '/models/Message.php')){
            require_once APPROOT . '/models/Message.php';
            $this->messageModel = new Message();
        }

        $this->productModel = new Product();
        $this->userModel = new User();
    }

    // --- ADD PRODUCT (SELL) ---
    public function add()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // 1. Image Handling
            $imageName = 'no_image.png';
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
                $fileName = $_FILES['product_image']['name'];
                $fileTmp = $_FILES['product_image']['tmp_name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];

                if (in_array($ext, $allowed)) {
                    $newFileName = time() . '_' . $fileName;
                    $dest = '../public/img/' . $newFileName;
                    if (move_uploaded_file($fileTmp, $dest)) {
                        $imageName = $newFileName;
                    }
                }
            }

            // 2. Prepare Data
            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'price' => trim($_POST['price']),
                // Map 'condition' from form to DB column
                'condition' => trim($_POST['condition']), 
                'description' => trim($_POST['description']),
                'image' => $imageName
            ];

            // 3. Add to DB & Show Alert
            if ($this->productModel->addProduct($data)) {
                echo "<script>
                    alert('Success! Your product has been submitted for Admin Approval. It will appear on the homepage once approved.');
                    window.location.href='" . URLROOT . "/pages/index';
                </script>";
                exit;
            } else {
                die('Something went wrong');
            }
        } else {
            $data = [ 'user' => $user ];
            require_once '../app/views/products/add.php';
        }
    }

    // --- MY LISTINGS ---
    public function listings()
    {
        $products = $this->productModel->getProductsByUser($_SESSION['user_id']);
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'products' => $products,
            'user' => $user
        ];

        require_once '../app/views/products/listings.php';
    }

    // --- EDIT PRODUCT ---
    public function edit($id)
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle Image
            $imageName = '';
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
                $fileName = $_FILES['product_image']['name'];
                $fileTmp = $_FILES['product_image']['tmp_name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];

                if (in_array($ext, $allowed)) {
                    $newName = time() . '_' . $fileName;
                    $dest = '../public/img/' . $newName;
                    if (move_uploaded_file($fileTmp, $dest)) {
                        $imageName = $newName;
                    }
                }
            }

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'price' => trim($_POST['price']),
                'condition' => trim($_POST['condition']), // Added condition
                'description' => trim($_POST['description']),
                'image' => $imageName
            ];

            if ($this->productModel->updateProduct($data)) {
                header('location: ' . URLROOT . '/products/listings');
            } else {
                die('Something went wrong');
            }
        } else {
            $product = $this->productModel->getProductById($id);

            // Security Check
            if ($product->user_id != $_SESSION['user_id']) {
                header('location: ' . URLROOT . '/products/listings');
                exit;
            }

            $data = [
                'id' => $id,
                'title' => $product->title,
                'category' => $product->category,
                'price' => $product->price,
                'condition' => $product->condition_type, // Map DB column
                'description' => $product->description,
                'image' => $product->image,
                'user' => $user
            ];

            require_once '../app/views/products/edit.php';
        }
    }

    // --- DELETE PRODUCT ---
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $this->productModel->getProductById($id);

            if ($product->user_id != $_SESSION['user_id']) {
                header('location: ' . URLROOT . '/products/listings');
                exit;
            }

            if ($this->productModel->deleteProduct($id)) {
                // Delete image if exists
                if ($product->image != 'no_image.png' && file_exists('../public/img/' . $product->image)) {
                    unlink('../public/img/' . $product->image);
                }
                header('location: ' . URLROOT . '/products/listings');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/products/listings');
        }
    }

    // --- SHOW PRODUCT DETAILS ---
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if (!$product) {
            header('location: ' . URLROOT . '/pages/index');
            exit;
        }

        $isInterested = $this->productModel->isInterested($id, $_SESSION['user_id']);
        
        $interestedBuyers = [];
        if ($product->user_id == $_SESSION['user_id']) {
            $interestedBuyers = $this->productModel->getInterestedBuyers($id);
        }

        $data = [
            'product' => $product,
            'user' => $user,
            'isInterested' => $isInterested,
            'interestedBuyers' => $interestedBuyers
        ];

        require_once '../app/views/products/show.php';
    }

    // --- TOGGLE INTEREST ---
    public function toggleInterest($id)
    {
        if ($this->productModel->isInterested($id, $_SESSION['user_id'])) {
            $this->productModel->removeInterest($id, $_SESSION['user_id']);
        } else {
            $this->productModel->addInterest($id, $_SESSION['user_id']);
        }
        header('location: ' . URLROOT . '/products/show/' . $id);
    }

    // --- SEND MESSAGE ---
    public function message($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $this->productModel->getProductById($productId);
            
            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => $product->user_id,
                'product_id' => $productId,
                'message' => trim($_POST['message_body'])
            ];

            if ($this->messageModel && !empty($data['message'])) {
                if ($this->messageModel->send($data)) {
                    echo "<script>alert('Message sent successfully!'); window.location.href='" . URLROOT . "/products/show/" . $productId . "';</script>";
                }
            } else {
                header('location: ' . URLROOT . '/products/show/' . $productId);
            }
        }
    }

    // --- NOTIFICATIONS PAGE ---
    public function notifications()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        // Fetch recent products for student feed
        $recentProducts = $this->productModel->getRecentProducts($_SESSION['user_id']);

        $data = [
            'notifications' => $recentProducts,
            'user' => $user
        ];

        require_once '../app/views/products/notifications.php';
    }

    // --- SEARCH & FILTER (AJAX) ---
    public function search()
    {
        $filters = [
            'search'    => isset($_GET['search']) ? trim($_GET['search']) : '',
            'category'  => isset($_GET['cat']) ? trim($_GET['cat']) : '',
            'min_price' => isset($_GET['min_price']) ? trim($_GET['min_price']) : '',
            'max_price' => isset($_GET['max_price']) ? trim($_GET['max_price']) : ''
        ];

        $products = $this->productModel->getFilteredProducts($filters);
        $data = ['products' => $products];

        require_once APPROOT . '/views/products/results.php';
    }

    // --- SUGGESTIONS (AJAX) ---
    public function suggest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $keyword = $data->keyword ?? '';

            if (!empty($keyword)) {
                $results = $this->productModel->getSearchSuggestions($keyword);
                echo json_encode($results);
            } else {
                echo json_encode([]);
            }
        }
    }
}
?>