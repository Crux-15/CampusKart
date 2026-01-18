    <?php
    class ProductsController
    {
        private $productModel;
        private $userModel; // Added User Model
        private $messageModel; // Added Message Model

        public function __construct()
        {
            if (!isset($_SESSION['user_id'])) {
                header('location: ' . URLROOT . '/users/login');
            }

            require_once APPROOT . '/models/Product.php';
            require_once APPROOT . '/models/User.php';
            require_once APPROOT . '/models/Message.php'; // <--- NEW

            $this->productModel = new Product();
            $this->userModel = new User();
            $this->messageModel = new Message(); // <--- NEW
        }

        public function add()
        {
            // Fetch User for Navbar
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
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

                $data = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => trim($_POST['title']),
                    'category' => trim($_POST['category']),
                    'price' => trim($_POST['price']),
                    'description' => trim($_POST['description']),
                    'image' => $imageName
                ];

                if ($this->productModel->addProduct($data)) {
                    header('location: ' . URLROOT . '/pages/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $data = [
                    'user' => $user // Pass user to view
                ];
                require_once '../app/views/products/add.php';
            }
        }

        // --- MY LISTINGS PAGE ---
        public function listings()
        {
            // 1. Get User Products
            $products = $this->productModel->getProductsByUser($_SESSION['user_id']);

            // 2. Get User Profile (For Navbar)
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
            // Fetch User for Navbar
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle Image Upload
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
                    'description' => trim($_POST['description']),
                    'image' => $imageName
                ];

                if ($this->productModel->updateProduct($data)) {
                    header('location: ' . URLROOT . '/products/listings');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Get existing product
                $product = $this->productModel->getProductById($id);

                // Check Ownership
                if ($product->user_id != $_SESSION['user_id']) {
                    header('location: ' . URLROOT . '/products/listings');
                }

                $data = [
                    'id' => $id,
                    'title' => $product->title,
                    'category' => $product->category,
                    'price' => $product->price,
                    'description' => $product->description,
                    'image' => $product->image,
                    'user' => $user // Pass user for Navbar
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
                }

                if ($this->productModel->deleteProduct($id)) {
                    if ($product->image != 'no_image.png') {
                        // Check file exists before deleting
                        if (file_exists('../public/img/' . $product->image)) {
                            unlink('../public/img/' . $product->image);
                        }
                    }
                    header('location: ' . URLROOT . '/products/listings');
                } else {
                    die('Something went wrong');
                }
            } else {
                header('location: ' . URLROOT . '/products/listings');
            }
        }

        // SHOW SINGLE PRODUCT
        public function show($id)
        {
            $product = $this->productModel->getProductById($id);
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            if (!$product) {
                header('location: ' . URLROOT . '/pages/index');
            }

            // Check if CURRENT user is interested (to color the button)
            $isInterested = $this->productModel->isInterested($id, $_SESSION['user_id']);

            // If I am the SELLER, fetch the list of interested buyers
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

        // HANDLE INTEREST CLICK
        public function toggleInterest($id)
        {
            if (!isset($_SESSION['user_id'])) {
                header('location: ' . URLROOT . '/users/login');
            }

            // Check current status
            if ($this->productModel->isInterested($id, $_SESSION['user_id'])) {
                // If yes -> Remove it
                $this->productModel->removeInterest($id, $_SESSION['user_id']);
            } else {
                // If no -> Add it
                $this->productModel->addInterest($id, $_SESSION['user_id']);
            }

            // Redirect back to the same product page
            header('location: ' . URLROOT . '/products/show/' . $id);
        }

        // HANDLE SENDING MESSAGE
        public function message($productId)
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Get product to find the owner (receiver)
                $product = $this->productModel->getProductById($productId);

                $data = [
                    'sender_id' => $_SESSION['user_id'],
                    'receiver_id' => $product->user_id,
                    'product_id' => $productId,
                    'message' => trim($_POST['message_body'])
                ];

                if (!empty($data['message'])) {
                    if ($this->messageModel->send($data)) {
                        // Success: Go back to product page with alert
                        echo "<script>alert('Message sent successfully!'); window.location.href='" . URLROOT . "/products/show/" . $productId . "';</script>";
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // Empty message? Just go back
                    header('location: ' . URLROOT . '/products/show/' . $productId);
                }
            }
        }

        // FETCH NOTIFICATIONS (AJAX)
        // NOTIFICATIONS PAGE
        public function notifications()
        {
            // Ensure logged in
            if (!isset($_SESSION['user_id'])) {
                header('location: ' . URLROOT . '/users/login');
            }

            // Get User (For Navbar)
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            // Fetch recent products
            // (This uses the getRecentProducts function already in your Product.php)
            $recentProducts = $this->productModel->getRecentProducts($_SESSION['user_id']);

            $data = [
                'notifications' => $recentProducts,
                'user' => $user
            ];

            // Load the View
            require_once '../app/views/products/notifications.php';
        }

        // LIVE SEARCH SUGGESTIONS (AJAX)
        public function suggest()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get the raw POST data (JSON)
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

        // AJAX SEARCH HANDLER
        // AJAX SEARCH & FILTER HANDLER
        public function search()
        {
            // Collect all possible filters from URL
            $filters = [
                'search'    => isset($_GET['search']) ? trim($_GET['search']) : '',
                'category'  => isset($_GET['cat']) ? trim($_GET['cat']) : '',
                'min_price' => isset($_GET['min_price']) ? trim($_GET['min_price']) : '',
                'max_price' => isset($_GET['max_price']) ? trim($_GET['max_price']) : '',
                'date_sort' => isset($_GET['date_sort']) ? trim($_GET['date_sort']) : ''
            ];

            // Call the smart model function
            $products = $this->productModel->getFilteredProducts($filters);

            // Prepare data
            $data = [
                'products' => $products
            ];

            // Load the partial view
            require_once APPROOT . '/views/products/results.php';
        }
    }
    ?>