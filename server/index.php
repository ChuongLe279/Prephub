<?php
// entry point & api router (điều hướng request)

// Tắt hiển thị lỗi trực tiếp, ghi vào log thay vì hiển thị để tránh hỏng JSON
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Xử lý CORS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Thiết lập handler để bắt các lỗi nghiêm trọng và trả về JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) return;
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $errstr,
        'error' => [
            'type' => $errno,
            'file' => $errfile,
            'line' => $errline
        ]
    ]);
    exit();
});

set_exception_handler(function($exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage(),
        'error' => true
    ]);
    exit();
});

try {
    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/utils/response.php';

    // Phân tích request từ cả 2 nguồn (để tương thích cả 2 branch)
    $request = $_GET['request'] ?? '';
    if (empty($request)) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Clean path cho các môi trường thư mục con
        $request = str_replace(['/IS207-UIT/server/api/', '/api/'], '', $path);
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    $parts = explode('/', trim($request, '/'));
    $resource = $parts[0] ?? '';

    // ĐIỀU HƯỚNG
    if ($resource === 'tests') {
        require_once __DIR__ . '/controllers/test-controller.php';
        
        if (isset($parts[1]) && is_numeric($parts[1])) {
            require_once __DIR__ . '/middleware/auth.php';
            requireAuth();
            getTestCore($parts[1]);
        } else {
            getTestList();
        }
    } elseif ($resource === 'auth') {
        require_once __DIR__ . '/controllers/auth-controller.php';
        $action = $parts[1] ?? '';
        
        switch ($action) {
            case 'login': handleLogin(); break;
            case 'register': handleRegister(); break;
            case 'logout': handleLogout(); break;
            default: sendError("Không tìm thấy chức năng xác thực", 404);
        }
    } elseif ($resource === 'questions') {
        // Tích hợp với route mới từ nhánh dev
        require_once __DIR__ . '/routes/api.php';
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Route not found', 'request' => $request]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ]);
}
