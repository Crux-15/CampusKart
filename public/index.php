<?php
// 1. Start Session
session_start();

// 2. Define Folder Paths
define('APPROOT', dirname(dirname(__FILE__)) . '/app');
define('URLROOT', 'http://localhost/CampusKart');

// 3. Load Database Config
require_once APPROOT . '/config/database.php';

// --- ROUTER LOGIC ---

// Get the URL (e.g., "user/login")
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'users/login';
$url = explode('/', $url);

// A. Determine Controller
// Default to 'UserController' if no controller is specified
$controllerName = 'UserController'; 
if (isset($url[0]) && !empty($url[0])) {
    $controllerName = ucfirst($url[0]) . 'Controller';
}

// B. Load the Controller File
if (file_exists(APPROOT . '/controllers/' . $controllerName . '.php')) {
    require_once APPROOT . '/controllers/' . $controllerName . '.php';
    $controller = new $controllerName();

    // C. Determine Method (Function)
    $methodName = 'index'; // Default method
    if (isset($url[1]) && !empty($url[1])) {
        $methodName = $url[1];
    }

    // D. Run the Method
    if (method_exists($controller, $methodName)) {
        // Pass any remaining URL parameters
        $params = array_slice($url, 2);
        call_user_func_array([$controller, $methodName], $params);
    } else {
        echo "<h1>404 Error</h1><p>Method '$methodName' not found.</p>";
    }
} else {
    echo "<h1>404 Error</h1><p>Controller '$controllerName' not found.</p>";
}
?>