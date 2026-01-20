<?php
class AdminController
{
    private $userModel;
    private $productModel;

    public function __construct()
    {
        // 1. Check Session (Must be logged in)
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
            exit;
        }

        // 2. Check Role (Must be 'admin')
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
            header('location: ' . URLROOT . '/pages/index');
            exit;
        }

        // 3. Load Models
        if (file_exists(APPROOT . '/models/User.php')) {
            require_once APPROOT . '/models/User.php';
            $this->userModel = new User();
        }

        if (file_exists(APPROOT . '/models/Product.php')) {
            require_once APPROOT . '/models/Product.php';
            $this->productModel = new Product();
        }
    }

    public function index()
    {
        // Init counts
        $pendingUsersCount = 0;
        $pendingProductsCount = 0;

        // Fetch counts
        if ($this->userModel && method_exists($this->userModel, 'getPendingUsers')) {
            $pendingUsers = $this->userModel->getPendingUsers();
            if ($pendingUsers) {
                $pendingUsersCount = count($pendingUsers);
            }
        }

        if ($this->productModel && method_exists($this->productModel, 'getPendingProducts')) {
            $pendingProducts = $this->productModel->getPendingProducts();
            if ($pendingProducts) {
                $pendingProductsCount = count($pendingProducts);
            }
        }

        $data = [
            'pending_users_count' => $pendingUsersCount,
            'pending_products_count' => $pendingProductsCount
        ];

        // Load the View
        if (file_exists(APPROOT . '/views/admin/index.php')) {
            require_once APPROOT . '/views/admin/index.php';
        } else {
            die('Admin View does not exist');
        }
    }


    // --- 1. PENDING USERS PAGE ---
    public function pending_users()
    {
        $users = $this->userModel->getPendingUsers();

        $data = [
            'users' => $users
        ];

        require_once APPROOT . '/views/admin/pending_users.php';
    }

    // --- 2. APPROVE ACTION ---
    public function approve_user($id)
    {
        if ($this->userModel->approveUser($id)) {
            header('location: ' . URLROOT . '/admin/pending_users');
        } else {
            die('Something went wrong');
        }
    }

    // --- 3. REJECT ACTION ---
    public function delete_user($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($this->userModel->deleteUser($id)) {
                header('location: ' . URLROOT . '/admin/users');
            } else {
                die('Something went wrong');
            }
        }
    }


    // --- 1. PENDING PRODUCTS PAGE ---
    public function pending_products()
    {
        $products = $this->productModel->getPendingProducts();

        $data = [
            'products' => $products
        ];

        require_once APPROOT . '/views/admin/pending_products.php';
    }

    // --- 2. APPROVE PRODUCT ---
    public function approve_product($id)
    {
        if ($this->productModel->approveProduct($id)) {
            header('location: ' . URLROOT . '/admin/pending_products');
        } else {
            die('Something went wrong');
        }
    }

    // --- 3. REJECT PRODUCT ---
    public function delete_product($id)
    {
        if ($this->productModel->deleteProductByAdmin($id)) {
            header('location: ' . URLROOT . '/admin/pending_products');
        } else {
            die('Something went wrong');
        }
    }


    // --- 1. USER LIST PAGE ---
    public function users()
    {
        $users = $this->userModel->getAllUsers();

        $data = [
            'users' => $users
        ];

        require_once APPROOT . '/views/admin/users.php';
    }

    // --- 2. AJAX SEARCH ENDPOINT ---
    public function search_users_json()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $query = $data->query ?? '';

            if (!empty($query)) {
                $results = $this->userModel->searchUsers($query);
            } else {
                $usersObj = $this->userModel->getAllUsers();
                $results = json_decode(json_encode($usersObj), true);
            }

            header('Content-Type: application/json');
            echo json_encode($results);
        }
    }

    // --- 1. PRODUCT LIST PAGE ---
    public function products()
    {
        $products = $this->productModel->getAllProducts();

        $data = [
            'products' => $products
        ];

        require_once APPROOT . '/views/admin/products.php';
    }

    // --- 2. AJAX SEARCH ENDPOINT ---
    public function search_products_json()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $query = $data->query ?? '';

            if (!empty($query)) {
                $results = $this->productModel->searchProducts($query);
            } else {
                $productsObj = $this->productModel->getAllProducts();
                $results = json_decode(json_encode($productsObj), true);
            }

            header('Content-Type: application/json');
            echo json_encode($results);
        }
    }

    // --- CREATE NEW ADMIN PAGE (FIXED) ---
    // CREATE NEW ADMIN PAGE (Full Registration Features)
    public function create_admin() {
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'fullname' => trim($_POST['fullname']),
                'email' => trim($_POST['email']),
                'student_id' => trim($_POST['student_id']),
                'mobile' => trim($_POST['mobile']),
                'department' => trim($_POST['department']),
                'batch' => trim($_POST['batch']),
                'gender' => trim($_POST['gender']),
                'security_answer' => trim($_POST['security_answer']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'mobile_err' => '',
                'student_id_err' => ''
            ];

            // --- VALIDATION (Same as Register) ---
            if(empty($data['email'])) { $data['email_err'] = 'Please enter email'; }
            if(empty($data['student_id'])) { $data['student_id_err'] = 'Please enter ID'; }
            if(empty($data['mobile'])) { $data['mobile_err'] = 'Please enter mobile'; }
            if(empty($data['password'])) { $data['password_err'] = 'Please enter password'; }
            
            // Validate Confirm Password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Check if email exists
            if($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email is already taken';
            }

            // If no errors, Create Admin
            if(empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['student_id_err'])) {
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->registerAdmin($data)) {
                    echo "<script>alert('New Admin Created Successfully!'); window.location.href='" . URLROOT . "/admin/users';</script>";
                } else {
                    die('Something went wrong');
                }
            } else {
                require_once APPROOT . '/views/admin/create_admin.php';
            }

        } else {
            // Init data
            $data = [
                'fullname' => '', 'email' => '', 'student_id' => '', 'mobile' => '',
                'department' => '', 'batch' => '', 'gender' => '', 'security_answer' => '',
                'password' => '', 'confirm_password' => '',
                'email_err' => '', 'password_err' => '', 'confirm_password_err' => '',
                'mobile_err' => '', 'student_id_err' => ''
            ];

            require_once APPROOT . '/views/admin/create_admin.php';
        }
    }

    // EDIT USER PAGE
    public function edit_user($id) {
        // Fetch existing user data
        $user = $this->userModel->getUserById($id);

        // Check for POST (Update Request)
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'fullname' => trim($_POST['fullname']),
                'email' => trim($_POST['email']),
                'student_id' => trim($_POST['student_id']),
                'mobile' => trim($_POST['mobile']),
                'department' => trim($_POST['department']),
                'batch' => trim($_POST['batch']),
                'gender' => trim($_POST['gender']),
                'role' => trim($_POST['role']),
                
                'user' => $user, // Keep user object in case of error
                'email_err' => '',
                'student_id_err' => ''
            ];

            // Basic Validation
            if(empty($data['email'])) { $data['email_err'] = 'Email is required'; }
            if(empty($data['student_id'])) { $data['student_id_err'] = 'ID is required'; }

            if(empty($data['email_err']) && empty($data['student_id_err'])) {
                // Update User
                if($this->userModel->updateUserByAdmin($data)) {
                    echo "<script>alert('User Updated Successfully!'); window.location.href='" . URLROOT . "/admin/users';</script>";
                } else {
                    die('Something went wrong');
                }
            } else {
                require_once APPROOT . '/views/admin/edit_user.php';
            }

        } else {
            // GET Request - Show the form with existing data
            $data = [
                'id' => $id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'mobile' => $user->mobile,
                'department' => $user->department,
                'batch' => $user->batch,
                'gender' => $user->gender,
                'role' => $user->role,
                'user' => $user,
                'email_err' => '',
                'student_id_err' => ''
            ];

            require_once APPROOT . '/views/admin/edit_user.php';
        }
    }

    // --- ADMIN ACCOUNT SETTINGS ---
    public function account() {
        // Fetch current admin data
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Handle Image Upload
            $imageName = ''; 
            
            if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
                $fileName = $_FILES['profile_photo']['name'];
                $fileTmp = $_FILES['profile_photo']['tmp_name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];

                if(in_array($ext, $allowed)) {
                    $newName = 'admin_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                    $dest = '../public/img/profiles/' . $newName;
                    
                    if(!is_dir('../public/img/profiles')) {
                        mkdir('../public/img/profiles');
                    }

                    if(move_uploaded_file($fileTmp, $dest)) {
                        $imageName = $newName;

                        // Delete Old Image
                        $oldImageDetails = $user->profile_image;
                        $oldImagePath = '../public/img/profiles/' . $oldImageDetails;

                        if(!empty($oldImageDetails) && file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }

            // 2. Prepare Data (Admins might not need batch, but we keep the structure)
            $data = [
                'id' => $_SESSION['user_id'],
                'mobile' => trim($_POST['mobile']),
                'secondary_email' => trim($_POST['secondary_email']),
                'batch' => $user->batch, // Admins keep existing batch/null
                'profile_image' => $imageName
            ];

            // 3. Update DB using the User Model
            if($this->userModel->updateProfile($data)) {
                echo "<script>alert('Admin Profile Updated!'); window.location.href='" . URLROOT . "/admin/account';</script>";
            } else {
                die('Something went wrong');
            }

        } else {
            // Load the Admin Account View
            $data = [
                'user' => $user
            ];
            require_once APPROOT . '/views/admin/account.php';
        }
    }

    // --- ADMIN NOTIFICATIONS PAGE ---
    public function notifications() {
        // 1. Fetch all pending items
        $pendingUsers = $this->userModel->getPendingUsers();
        $pendingProducts = $this->productModel->getPendingProducts();

        $data = [
            'users' => $pendingUsers,
            'products' => $pendingProducts
        ];

        // 2. Load the View
        require_once APPROOT . '/views/admin/notifications.php';
    }

}
?>