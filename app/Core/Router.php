<?php

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->registerRoutes();
    }

    /**
     * Daftarkan semua route aplikasi
     */
    private function registerRoutes()
    {
        // Authentication Routes
        $this->addRoute('GET', '/', [AuthController::class, 'index']);
        $this->addRoute('GET', '/admin/login', [AuthController::class, 'adminLoginPage']);
        $this->addRoute('POST', '/admin/login', [AuthController::class, 'adminLogin']);
        $this->addRoute('GET', '/karyawan/login', [AuthController::class, 'karyawanLoginPage']);
        $this->addRoute('POST', '/karyawan/login', [AuthController::class, 'karyawanLogin']);
        $this->addRoute('GET', '/logout', [AuthController::class, 'logout']);

        // Dashboard Routes
        $this->addRoute('GET', '/dashboard', [AuthController::class, 'dashboard']);
        $this->addRoute('GET', '/admin/dashboard', [AuthController::class, 'adminDashboard']);
        $this->addRoute('GET', '/karyawan/dashboard', [AuthController::class, 'employeeDashboard']);

        // Password
        $this->addRoute('GET', '/change-password', [AuthController::class, 'changePasswordPage']);
        $this->addRoute('POST', '/change-password', [AuthController::class, 'changePassword']);

        // Admin - Karyawan
        $this->addRoute('GET', '/admin/karyawan', [KaryawanController::class, 'index']);
        $this->addRoute('GET', '/admin/karyawan/create', [KaryawanController::class, 'create']);
        $this->addRoute('POST', '/admin/karyawan/store', [KaryawanController::class, 'create_account']);
        $this->addRoute('GET', '/admin/karyawan/edit', [KaryawanController::class, 'edit']);
        $this->addRoute('POST', '/admin/karyawan/update', [KaryawanController::class, 'update']);
        $this->addRoute('POST', '/admin/karyawan/delete', [KaryawanController::class, 'delete']);
        $this->addRoute('POST', '/admin/karyawan/deactivate', [KaryawanController::class, 'deactivate']);
        $this->addRoute('POST', '/admin/karyawan/activate', [KaryawanController::class, 'activateAccount']);

        // Admin - Cuti
        $this->addRoute('GET', '/admin/cuti', [CutiController::class, 'index']);
        $this->addRoute('POST', '/admin/cuti/approve', [CutiController::class, 'approve']);
        $this->addRoute('POST', '/admin/cuti/reject', [CutiController::class, 'reject']);
        $this->addRoute('POST', '/admin/cuti/delete', [CutiController::class, 'delete']);

        // Admin - Attendance
        $this->addRoute('GET', '/admin/attendance', [AttendanceController::class, 'adminIndex']);
        $this->addRoute('GET', '/admin/attendance/export', [AttendanceController::class, 'export']);

        // Karyawan - Attendance
        $this->addRoute('GET', '/karyawan/attendance', [AttendanceController::class, 'index']);
        $this->addRoute('POST', '/karyawan/attendance/checkin', [AttendanceController::class, 'checkIn']);
        $this->addRoute('POST', '/karyawan/attendance/checkout', [AttendanceController::class, 'checkOut']);

        // Karyawan - Leave
        $this->addRoute('GET', '/karyawan/leave', [LeaveController::class, 'index']);
        $this->addRoute('GET', '/karyawan/leave/create', [LeaveController::class, 'create']);
        $this->addRoute('POST', '/karyawan/leave/store', [LeaveController::class, 'store']);
        $this->addRoute('POST', '/karyawan/leave/delete', [LeaveController::class, 'delete']);
    }

    /**
     *  Helper untuk menambahkan route
     * 
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri URI route
     * @param array $handler Array berisi controller dan action
     */
    private function addRoute($method, $uri, $handler){
        $this->routes[] = compact('method', 'uri', 'handler');
    }

    /**
     * Selesaikan route berdasarkan URI dan method
     * 
     * @param string $uri URI route
     * @param string $method HTTP method (GET, POST, etc.)
     */
    public function resolve($uri, $method){
        // Handle dynamic file routes
        if ($this->handleFileRoute($uri)) {
            return;
        }

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $this->executeRoute($route['handler']);
                return;
            }
        }

        $this->handleNotFound();
    }

    /**
     * Eksekusi controller dan action untuk route tertentu
     * 
     * @param array $handler Array berisi controller dan action
     * @return void
     */
    private function executeRoute($handler){
        [$controllerName, $action] = $handler;

        $controllerFile = __DIR__ . "/../Controllers/{$controllerName}.php";
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            exit("Controller file not found: {$controllerName}");
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            exit("Controller class not found: {$controllerName}");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            exit("Method {$action} not found in {$controllerName}");
        }

        $controller->$action();
    }

    /**
     * Menangani dynamic route untuk file
     * 
     * @param string $uri URI route
     * @param string $method HTTP method
     */
    private function handleFileRoute($uri){
        if (str_starts_with($uri, '/file/leave/')) {
            require_once __DIR__ . "/../Controllers/FileController.php";
            $controller = new FileController();

            $parts = explode('/', trim($uri, '/'));
            $id = $parts[2] ?? null;

            if ($id && is_numeric($id)) {
                $controller->viewLeaveAttachment((int)$id);
                return true;
            }

            http_response_code(400);
            exit("Bad Request: Invalid file id");
        }

        return false;
    }

    /**
     * Tangani 404 Not Found
     */
    private function handleNotFound()
    {
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        exit;
    }
}
