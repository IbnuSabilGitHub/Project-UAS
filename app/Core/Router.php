<?php

class Router {
    private $routes = [];
    
    public function __construct() {
        $this->registerRoutes();
    }
    
    /**
     * Daftarkan semua route aplikasi
     */
    private function registerRoutes() {
        // Authentication Routes
        $this->addRoute('GET', '/', 'AuthController', 'index');
        $this->addRoute('GET', '/admin/login', 'AuthController', 'adminLoginPage');
        $this->addRoute('POST', '/admin/login', 'AuthController', 'adminLogin');
        $this->addRoute('GET', '/karyawan/login', 'AuthController', 'karyawanLoginPage');
        $this->addRoute('POST', '/karyawan/login', 'AuthController', 'karyawanLogin');
        $this->addRoute('GET', '/logout', 'AuthController', 'logout');
        
        // Dashboard Routes
        $this->addRoute('GET', '/dashboard', 'AuthController', 'dashboard');
        $this->addRoute('GET', '/admin/dashboard', 'AuthController', 'adminDashboard');
        $this->addRoute('GET', '/karyawan/dashboard', 'AuthController', 'employeeDashboard');
        
        // Password Management
        $this->addRoute('GET', '/change-password', 'AuthController', 'changePasswordPage');
        $this->addRoute('POST', '/change-password', 'AuthController', 'changePassword');
        
        // Admin - Karyawan Management
        $this->addRoute('GET', '/admin/karyawan', 'KaryawanController', 'index');
        $this->addRoute('GET', '/admin/karyawan/create', 'KaryawanController', 'create');
        $this->addRoute('POST', '/admin/karyawan/store', 'KaryawanController', 'create_account');
        $this->addRoute('GET', '/admin/karyawan/edit', 'KaryawanController', 'edit');
        $this->addRoute('POST', '/admin/karyawan/update', 'KaryawanController', 'update');
        $this->addRoute('POST', '/admin/karyawan/delete', 'KaryawanController', 'delete');
        $this->addRoute('POST', '/admin/karyawan/deactivate', 'KaryawanController', 'deactivate');
        $this->addRoute('POST', '/admin/karyawan/activate', 'KaryawanController', 'activateAccount');
        
        // Admin - Cuti Management
        $this->addRoute('GET', '/admin/cuti', 'CutiController', 'index');
        $this->addRoute('POST', '/admin/cuti/approve', 'CutiController', 'approve');
        $this->addRoute('POST', '/admin/cuti/reject', 'CutiController', 'reject');
        $this->addRoute('POST', '/admin/cuti/delete', 'CutiController', 'delete');
        
        // Admin - Attendance Management
        $this->addRoute('GET', '/admin/attendance', 'AttendanceController', 'adminIndex');
        $this->addRoute('GET', '/admin/attendance/export', 'AttendanceController', 'export');
        
        // Karyawan - Attendance
        $this->addRoute('GET', '/karyawan/attendance', 'AttendanceController', 'index');
        $this->addRoute('POST', '/karyawan/attendance/checkin', 'AttendanceController', 'checkIn');
        $this->addRoute('POST', '/karyawan/attendance/checkout', 'AttendanceController', 'checkOut');
        
        // Karyawan - Leave Request
        $this->addRoute('GET', '/karyawan/leave', 'LeaveController', 'index');
        $this->addRoute('GET', '/karyawan/leave/create', 'LeaveController', 'create');
        $this->addRoute('POST', '/karyawan/leave/store', 'LeaveController', 'store');
        $this->addRoute('POST', '/karyawan/leave/delete', 'LeaveController', 'delete');
    }
    
    /**
     *  Helper untuk menambahkan route
     * 
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri URI route
     * @param string $controller Nama controller
     * @param string $action Nama action method
     */
    private function addRoute($method, $uri, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Selesaikan route berdasarkan URI dan method
     * 
     * @param string $uri URI route
     * @param string $method HTTP method (GET, POST, etc.)
     */
    public function resolve($uri, $method) {
        // Handle file preview routes (dynamic route)
        if ($this->handleFileRoute($uri, $method)) {
            return;
        }
        
        // Handle static routes
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $this->executeRoute($route['controller'], $route['action']);
                return;
            }
        }
        
        // 404 Not Found
        $this->handleNotFound();
    }
    
    /**
     * Mengeksekusi controller action
     * 
     * @param string $controllerName Nama controller
     * @param string $action Nama action method
     */
    private function executeRoute($controllerName, $action) {
        $controllerPath = __DIR__ . "/../Controllers/{$controllerName}.php";
        require_once $controllerPath;
        
        $controller = new $controllerName();
        $controller->$action();
    }
    
    /**
     * Menangani dynamic route untuk file
     * 
     * @param string $uri URI route
     * @param string $method HTTP method
     */
    private function handleFileRoute($uri, $method) {
        if (strpos($uri, '/file/leave/') === 0) {
            require_once __DIR__ . "/../Controllers/FileController.php";
            $fc = new FileController();
            
            // Extract ID dari URL: /file/leave/{id}
            $parts = explode('/', trim($uri, '/'));
            if (isset($parts[2]) && is_numeric($parts[2])) {
                $leaveId = (int)$parts[2];
                $fc->viewLeaveAttachment($leaveId);
                return true;
            }
            
            http_response_code(400);
            echo "Bad Request: Invalid file URL";
            return true;
        }
        
        return false;
    }
    
    /**
     * Tangani 404 Not Found
     */
    private function handleNotFound() {
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        exit;
    }
}


