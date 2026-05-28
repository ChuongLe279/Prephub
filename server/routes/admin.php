<?php
/**
 * Router cho admin API endpoints
 */

require_once __DIR__ . '/../controllers/admin-controller.php';
require_once __DIR__ . '/../middleware/auth.php';

// kiểm tra quyền admin
requireAdmin();

$sub_resource = $parts[1] ?? '';

switch ($sub_resource) {
    case 'stats':
        if ($method === 'GET') {
            getAdminStats();
        } else {
            sendError("Phương thức không được hỗ trợ", 405);
        }
        break;

    case 'users':
        if ($method === 'GET') {
            getAdminUsers();
        } elseif ($method === 'PUT') {
            $user_id = $parts[2] ?? '';
            if (empty($user_id)) {
                sendError("Thiếu ID người dùng", 400);
            }
            updateAdminUser((int)$user_id);
        } else {
            sendError("Phương thức không được hỗ trợ", 405);
        }
        break;

    case 'attempts':
        if ($method === 'GET') {
            getAdminAttempts();
        } else {
            sendError("Phương thức không được hỗ trợ", 405);
        }
        break;

    case 'revenue':
        if ($method === 'GET') {
            getAdminRevenue();
        } else {
            sendError("Phương thức không được hỗ trợ", 405);
        }
        break;

    case 'transactions':
        if ($method === 'GET') {
            getAdminTransactions();
        } else {
            sendError("Phương thức không được hỗ trợ", 405);
        }
        break;

    default:
        sendError("Admin API endpoint not found", 404);
        break;
}
