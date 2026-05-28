<?php
/**
 * Admin controller for dashboard API endpoints
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db/config.php';
require_once __DIR__ . '/../utils/response.php';

// kiểm tra quyền admin
function checkAdminAccess() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
        sendError("Forbidden: Bạn không có quyền truy cập", 403);
    }
}

// lấy thống kê tổng quan cho dashboard
function getAdminStats() {
    global $conn;
    checkAdminAccess();

    try {
        $total_users = (int)$conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $total_tests = (int)$conn->query("SELECT COUNT(*) FROM tests")->fetchColumn();
        $total_revenue = (int)$conn->query("SELECT COALESCE(SUM(price), 0) FROM transaction_history WHERE status = 'success'")->fetchColumn();
        $total_purchased_users = (int)$conn->query("SELECT COUNT(DISTINCT user_id) FROM transaction_history WHERE status = 'success'")->fetchColumn();

        sendJson([
            'success' => true,
            'data' => [
                'total_users' => $total_users,
                'total_tests' => $total_tests,
                'total_revenue' => $total_revenue,
                'total_purchased_users' => $total_purchased_users
            ]
        ]);
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// lấy danh sách người dùng phân trang
function getAdminUsers() {
    global $conn;
    checkAdminAccess();

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $role_filter = isset($_GET['role']) ? trim($_GET['role']) : '';
    $status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

    try {
        // tính toán thống kê nhỏ cho tab user
        $total_users = (int)$conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $new_users = (int)$conn->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")->fetchColumn();
        $inactive_users = (int)$conn->query("SELECT COUNT(*) FROM users WHERE id NOT IN (SELECT DISTINCT user_id FROM attempts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY))")->fetchColumn();

        // chuẩn bị truy vấn tìm kiếm và lọc
        $where_clauses = [];
        $params = [];
        
        if ($q !== '') {
            $where_clauses[] = "(email LIKE :q OR first_name LIKE :q OR last_name LIKE :q)";
            $params['q'] = '%' . $q . '%';
        }
        
        if ($role_filter !== '') {
            $where_clauses[] = "role = :role";
            $params['role'] = $role_filter;
        }
        
        if ($status_filter !== '') {
            if ($status_filter === 'banned') {
                $where_clauses[] = "is_banned = 1";
            } elseif ($status_filter === 'active') {
                $where_clauses[] = "is_banned = 0";
            }
        }
        
        $where_clause = "";
        if (count($where_clauses) > 0) {
            $where_clause = " WHERE " . implode(" AND ", $where_clauses);
        }

        // lấy tổng số lượng người dùng khớp tìm kiếm
        $count_stmt = $conn->prepare("SELECT COUNT(*) FROM users" . $where_clause);
        $count_stmt->execute($params);
        $total_filtered = (int)$count_stmt->fetchColumn();

        // lấy danh sách người dùng
        $sql = "SELECT id, uuid, first_name, last_name, email, role, is_banned, is_premium, has_course, premium_plan, premium_until, created_at,
                       (SELECT COUNT(DISTINCT a2.test_id) FROM attempts a2 WHERE a2.user_id = users.id) AS user_tests_attempted,
                       (SELECT COUNT(*) FROM tests WHERE is_active = 1) AS total_active_tests
                FROM users" . $where_clause . " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        sendJson([
            'success' => true,
            'data' => $users,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total_filtered
            ],
            'stats' => [
                'total_users' => $total_users,
                'new_users_month' => $new_users,
                'inactive_users_7d' => $inactive_users
            ]
        ]);
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// cập nhật vai trò người dùng và trạng thái ban
function updateAdminUser($userId) {
    global $conn;
    checkAdminAccess();

    $input = json_decode(file_get_contents('php://input'), true);
    $role = $input['role'] ?? null;
    $is_banned = isset($input['is_banned']) ? (int)$input['is_banned'] : null;

    if ($role === null && $is_banned === null) {
        sendError("Không có trường dữ liệu nào được cập nhật", 400);
    }

    try {
        // tạo truy vấn cập nhật động
        $fields = [];
        $params = ['id' => $userId];

        if ($role !== null) {
            if (!in_array($role, ['user', 'admin'])) {
                sendError("Role không hợp lệ", 400);
            }
            $fields[] = "role = :role";
            $params['role'] = $role;
        }

        if ($is_banned !== null) {
            $fields[] = "is_banned = :is_banned";
            $params['is_banned'] = $is_banned;
        }

        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        sendJson([
            'success' => true,
            'message' => 'Cập nhật user thành công'
        ]);
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// lấy danh sách lượt thi phân trang
function getAdminAttempts() {
    global $conn;
    checkAdminAccess();

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;

    try {
        // lấy tổng số lượt thi
        $total = (int)$conn->query("SELECT COUNT(*) FROM attempts")->fetchColumn();

        // lấy lượt thi với thông tin chi tiết người dùng và đề thi kèm tiến trình
        $sql = "SELECT 
                    a.id, a.uuid, a.listening_correct, a.reading_correct, a.listening_score, a.reading_score, a.total_score, a.time_spent, a.created_at,
                    u.first_name, u.last_name, u.email, u.is_premium, u.premium_plan, u.has_course,
                    t.title,
                    (SELECT COUNT(DISTINCT a2.test_id) FROM attempts a2 WHERE a2.user_id = a.user_id) AS user_tests_attempted,
                    (SELECT COUNT(*) FROM tests WHERE is_active = 1) AS total_active_tests
                FROM attempts a
                JOIN users u ON a.user_id = u.id
                JOIN tests t ON a.test_id = t.id
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        sendJson([
            'success' => true,
            'data' => $attempts,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total
            ]
        ]);
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// lấy tóm tắt doanh thu và lịch sử
function getAdminRevenue() {
    global $conn;
    checkAdminAccess();

    try {
        // tổng doanh thu tháng hiện tại
        $current_month_revenue = (int)$conn->query("SELECT COALESCE(SUM(price), 0) FROM transaction_history WHERE status = 'success' AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")->fetchColumn();
        
        // tổng doanh thu mọi thời gian
        $all_time_revenue = (int)$conn->query("SELECT COALESCE(SUM(price), 0) FROM transaction_history WHERE status = 'success'")->fetchColumn();

        // dữ liệu biểu đồ 12 tháng qua
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') AS month,
                    SUM(price) AS total
                FROM transaction_history
                WHERE status = 'success' AND created_at >= DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 11 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC";
        
        $chart_data = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        sendJson([
            'success' => true,
            'data' => [
                'current_month' => $current_month_revenue,
                'all_time' => $all_time_revenue,
                'chart' => $chart_data
            ]
        ]);
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// lấy danh sách giao dịch phân trang
function getAdminTransactions() {
    global $conn;
    checkAdminAccess();

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;

    try {
        // lấy tổng số giao dịch
        $total = (int)$conn->query("SELECT COUNT(*) FROM transaction_history WHERE status = 'success'")->fetchColumn();

        // lấy danh sách giao dịch
        $sql = "SELECT t.id, t.tx_id, t.plan_id, t.plan_name, t.price, t.period, t.created_at,
                       u.first_name, u.last_name, u.email
                FROM transaction_history t
                JOIN users u ON t.user_id = u.id
                WHERE t.status = 'success'
                ORDER BY t.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        sendJson([
            'success' => true,
            'data' => $transactions,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total
            ]
        ]);
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}
