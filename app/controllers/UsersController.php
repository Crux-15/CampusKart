<?php
class UsersController
{
    private $userModel;
    private $messageModel; // Added Message Model property

    public function __construct()
    {
        // Load the User Model
        require_once APPROOT . '/models/User.php';
        $this->userModel = new User();

        // Load the Message Model
        require_once APPROOT . '/models/Message.php';
        $this->messageModel = new Message();
    }

    // 1. The Default Method (Loads Login Page)
    public function index()
    {
        // If user is already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            echo "You are already logged in!";
            exit;
        }

        $data = [
            'username_err' => '',
            'password_err' => ''
        ];

        // Load the Login View
        if (file_exists('../app/views/users/login.php')) {
            require_once '../app/views/users/login.php';
        } else {
            die('View login.php not found');
        }
    }

    // 2. The Login Logic (Handles Form Submit)
    public function login()
    {
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get form data
            $data = [
                'username' => trim($_POST['username']), 
                'password' => trim($_POST['password']), 
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate Email/ID
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter ID or Email';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // If no errors, try to login
            if (empty($data['username_err']) && empty($data['password_err'])) {

                // Call the Model
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password or Username is incorrect';
                    require_once '../app/views/users/login.php';
                }
            } else {
                require_once '../app/views/users/login.php';
            }
        } else {
            $this->index();
        }
    }

    // SHOW FORGOT PASSWORD PAGE
    public function forgot_password() {
        if(file_exists('../app/views/users/forgot_password.php')) {
            require_once '../app/views/users/forgot_password.php';
        } else {
            die('View forgot_password.php not found');
        }
    }


    // 3. Helper to set Session Variables
    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->fullname;
        $_SESSION['user_role'] = $user->role;

        header('location: ' . URLROOT . '/pages/index');
    }

    // 4. Logout
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();

        header('location: ' . URLROOT . '/users/login');
    }

    // REGISTER FUNCTION
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'fullname' => trim($_POST['fullname']),
                'email' => trim($_POST['email']),
                'student_id' => trim($_POST['student_id']),
                'mobile' => trim($_POST['mobile']),
                'department' => trim($_POST['department']),
                'batch' => trim($_POST['batch']),
                'gender' => trim($_POST['gender']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'email_err' => '',
                'student_id_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            if(empty($data['student_id'])) {
                $data['student_id_err'] = 'Please enter Student ID';
            }

            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if(empty($data['email_err']) && empty($data['student_id_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                if($this->userModel->register($data)) {
                    header('location: ' . URLROOT . '/users/login');    
                } else {
                    die('Something went wrong');
                }
            } else {
                require_once '../app/views/users/register.php';
            }

        } else {
            $data = [
                'fullname' => '',
                'email' => '',
                'student_id' => '',
                'mobile' => '',
                'department' => '',
                'batch' => '',
                'gender' => '',
                'password' => '',
                'confirm_password' => '',
                'email_err' => '',
                'student_id_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            if(file_exists('../app/views/users/register.php')) {
                require_once '../app/views/users/register.php';
            } else {
                die("View register.php does not exist");
            }
        }
    }

    public function account() {
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
        }

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
                    $newName = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
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

            // 2. Prepare Data
            $data = [
                'id' => $_SESSION['user_id'],
                'mobile' => trim($_POST['mobile']),
                'secondary_email' => trim($_POST['secondary_email']),
                'profile_image' => $imageName
            ];

            // 3. Update DB
            if($this->userModel->updateProfile($data)) {
                echo "<script>alert('Profile Updated Successfully!'); window.location.href='" . URLROOT . "/users/account';</script>";
            } else {
                die('Something went wrong');
            }

        } else {
            $data = [
                'user' => $user
            ];
            require_once '../app/views/users/account.php';
        }
    }

    // --- MESSAGES (INBOX) ---
    public function messages() {
        if(!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/users/login');
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $messages = $this->messageModel->getInbox($_SESSION['user_id']);

        $data = [
            'user' => $user,
            'messages' => $messages
        ];

        require_once '../app/views/users/messages.php';
    }

    // SEND REPLY
    // SEND REPLY (AJAX VERSION)
    public function reply() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Ensure user is logged in
            if(!isset($_SESSION['user_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
                exit;
            }

            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => $_POST['receiver_id'],
                'product_id' => $_POST['product_id'],
                'message' => trim($_POST['message_body'])
            ];

            if(!empty($data['message'])) {
                if($this->messageModel->send($data)) {
                    // SUCCESS: Return JSON instead of Redirect
                    echo json_encode([
                        'status' => 'success', 
                        'message' => nl2br(htmlspecialchars($data['message'])), 
                        'time' => date('M d, h:i A')
                    ]);
                    exit;
                } else {
                    echo json_encode(['status' => 'error']);
                    exit;
                }
            }
        }
    }

    // DELETE CONVERSATION
    public function delete_conversation() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            if(!isset($_SESSION['user_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
                exit;
            }

            // Get the ID of the person we are talking to
            $partnerId = $_POST['partner_id'];

            if($this->messageModel->deleteConversation($_SESSION['user_id'], $partnerId)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Could not delete']);
            }
        }
    }

}
?>