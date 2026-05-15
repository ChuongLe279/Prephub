<?php
session_start();
header('Content-Type: application/json');

// giả lập việc xử lý thanh toán thành công
// trong thực tế, đây sẽ là endpoint nhận callback từ ngân hàng hoặc verify giao dịch qua API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // lưu trạng thái premium vào session để demo
    $_SESSION['is_premium'] = true;
    $_SESSION['premium_until'] = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    echo json_encode([
        'success' => true,
        'message' => 'Nâng cấp tài khoản thành công'
    ]);
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
