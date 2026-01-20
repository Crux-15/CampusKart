<?php
class UsersController
{
    private $userModel;
    private $messageModel;

    public function __construct()
    {
        // Load the User Model
        require_once APPROOT . '/models/User.php';
        $this->userModel = new User();

        // Load the Message Model
        // (Ensure app/models/Message.php exists, or this will error)
        if(file_exists(APPROOT . '/models/Message.php')){
            require_once APPROOT . '/models/Message.php';
            $this->messageModel = new Message();
        }
    }

    // 1. The Default Method (Loads Login Page)
    public function index()
    {
        // FIX: If already logged in, send them to their dashboard
        if (isset($_SESSION['user_id'])) {
            // Check role to decide where to send them
            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                header('location: ' . URLROOT . '/admin/index');
            } else {
                header('location: ' . URLROOT . '/pages/index');
            }
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
                    // --- THE GATEKEEPER START ---
                    
                    // 1. CHECK STATUS: Is the user pending?
                    if($loggedInUser->status == 'pending') {
                        $data['password_err'] = 'Your account is pending Admin approval. Please wait.';
                        require_once '../app/views/users/login.php';
                    } 
                    // 2. CHECK STATUS: Is the user Banned or Rejected?
                    elseif($loggedInUser->status == 'rejected' || $loggedInUser->status == 'banned') {
                        $data['password_err'] = 'This account has been deactivated by the Admin.';
                        require_once '../app/views/users/login.php';
                    }
                    // 3. SUCCESS: User is approved
                    else {
                        // Create Session and Redirect
                        $this->createUserSession($loggedInUser);
                    }
                    // --- THE GATEKEEPER END ---

                } else {
                    $data['password_err'] = 'Password or Username is incorrect';
                    require_once '../app/views/users/login.php';
                }
            } else {
                require_once '../app/views/users/login.php';
            }
        } else {
            require_once '../app/views/users/login.php';
        }
    }

    // SHOW FORGOT PASSWORD PAGE
    // STEP 1: IDENTIFY USER
    // STEP 1: IDENTIFY USER (Fixed Variable Name)
    public function forgot_password() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'fullname' => trim($_POST['fullname']),
                'student_id' => trim($_POST['student_id']),
                'email' => trim($_POST['email']),
                'error_msg' => ''
            ];

            // Check DB for matching user
            $user = $this->userModel->findUserForReset($data['fullname'], $data['student_id'], $data['email']);

            if($user) {
                // SUCCESS: User found! Move to Step 2
                // FIX: Variable name changed from $viewData to $data
                $data = [
                    'user_id' => $user->id,
                    'error_msg' => ''
                ];
                require_once '../app/views/users/security_question.php';
            } else {
                // FAILED: No match
                $data['error_msg'] = 'No account found matching these details.';
                require_once '../app/views/users/forgot_password.php';
            }

        } else {
            // Load default page
            $data = ['error_msg' => ''];
            require_once '../app/views/users/forgot_password.php';
        }
    }

    // STEP 2: VERIFY SECURITY QUESTION (Fixed Variable Name)
    public function verify_security() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $userId = $_POST['user_id'];
            $answer = trim($_POST['security_answer']);

            if($this->userModel->checkSecurityAnswer($userId, $answer)) {
                // SUCCESS: Answer is correct!
                $_SESSION['reset_user_id'] = $userId;
                
                // Redirect to Step 3
                header('location: ' . URLROOT . '/users/new_password');
            } else {
                // FAILED: Wrong Answer
                // FIX: Variable name changed from $viewData to $data
                $data = [
                    'user_id' => $userId,
                    'error_msg' => 'Incorrect answer. Please try again.'
                ];
                require_once '../app/views/users/security_question.php';
            }
        } else {
            header('location: ' . URLROOT . '/users/forgot_password');
        }
    }

    // STEP 3: SET NEW PASSWORD
    public function new_password() {
        // SECURITY CHECK: Ensure user passed Step 2
        if(!isset($_SESSION['reset_user_id'])) {
            header('location: ' . URLROOT . '/users/login');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'error_msg' => ''
            ];

            // Validation
            if(empty($data['password'])) {
                $data['error_msg'] = 'Please enter a password';
            } elseif(strlen($data['password']) < 6) {
                $data['error_msg'] = 'Password must be at least 6 characters';
            }

            if(empty($data['confirm_password'])) {
                $data['error_msg'] = 'Please confirm your password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['error_msg'] = 'Passwords do not match';
                }
            }

            // If no errors, update DB
            if(empty($data['error_msg'])) {
                
                // Hash the new password
                $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
                $userId = $_SESSION['reset_user_id'];

                if($this->userModel->resetPassword($userId, $hashed_password)) {
                    // SUCCESS!
                    
                    // Clear the reset session so they can't use it again
                    unset($_SESSION['reset_user_id']);
                    
                    // Show success alert and go to login
                    echo "<script>
                        alert('Password reset successful! You can now login with your new password.');
                        window.location.href='" . URLROOT . "/users/login';
                    </script>";
                } else {
                    die('Something went wrong');
                }

            } else {
                // Load view with errors
                require_once '../app/views/users/new_password.php';
            }

        } else {
            // Load the view (GET request)
            $data = [
                'password' => '',
                'confirm_password' => '',
                'error_msg' => ''
            ];
            require_once '../app/views/users/new_password.php';
        }
    }




    // 3. Helper to set Session Variables
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->fullname;
        $_SESSION['user_role'] = $user->role; 

        if($user->role == 'admin') {
            header('location: ' . URLROOT . '/admin/index');
        } else {
            header('location: ' . URLROOT . '/pages/index');
        }
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

    // REGISTER FUNCTION (FIXED)
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get form data
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
                
                // Errors
                'name_err' => '', 'email_err' => '', 'student_id_err' => '',
                'mobile_err' => '', 'password_err' => '', 'confirm_password_err' => '',
                'security_err' => '' 
            ];

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Student ID
            if(empty($data['student_id'])) {
                $data['student_id_err'] = 'Please enter Student ID';
            }

            // Validate Security Answer
            if(empty($data['security_answer'])) {
                $data['security_err'] = 'Please answer the security question';
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // CHECK ALL ERRORS (Added security_err check)
            if(empty($data['email_err']) && empty($data['student_id_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['security_err'])) {
                
                // ----------------------------------------
                // CRITICAL FIX: Hash Password Before Saving
                // ----------------------------------------
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->register($data)) {
                    // Success -> Redirect to login
                    header('location: ' . URLROOT . '/users/login');    
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once '../app/views/users/register.php';
            }

        } else {
            // Init data for GET request
            $data = [
                'fullname' => '',
                'email' => '',
                'student_id' => '',
                'mobile' => '',
                'department' => '',
                'batch' => '',
                'gender' => '',
                'security_answer' => '',
                'password' => '',
                'confirm_password' => '',
                'email_err' => '',
                'student_id_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'security_err' => ''
            ];

            if(file_exists('../app/views/users/register.php')) {
                require_once '../app/views/users/register.php';
            } else {
                die("View register.php does not exist");
            }
        }
    }

    // ACCOUNT SETTINGS
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
            // Note: Make sure your form sends 'batch' if you want to update it
            $data = [
                'id' => $_SESSION['user_id'],
                'mobile' => trim($_POST['mobile']),
                'secondary_email' => trim($_POST['secondary_email']),
                // If form has batch, use it; otherwise keep old
                'batch' => isset($_POST['batch']) ? trim($_POST['batch']) : $user->batch,
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

        // Ensure Message Model is loaded
        if(!$this->messageModel) {
            die('Message Model not loaded.');
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $messages = $this->messageModel->getInbox($_SESSION['user_id']);

        $data = [
            'user' => $user,
            'messages' => $messages
        ];

        require_once '../app/views/users/messages.php';
    }

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
                    // SUCCESS: Return JSON
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