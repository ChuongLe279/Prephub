<?php
/**
 * Admin dashboard page
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// kiểm tra quyền admin phía PHP
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header("Location: home.php");
    exit();
}

$section = $_GET['section'] ?? 'overview';
$action = $_GET['action'] ?? '';
$test_id = $_GET['test_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrepHub Admin Dashboard</title>
    <?php include './components/metadata.php'; ?>
    <link href="../styles/adminStyle.css" rel="stylesheet">
    <?php if ($section === 'tests' && ($action === 'create' || $action === 'edit')): ?>
        <link href="../styles/questionsStyle.css" rel="stylesheet">
    <?php endif; ?>
    <!-- tải thư viện chart.js cdn vẽ biểu đồ -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
</head>
<body>

    <!-- thanh sidebar điều hướng -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="bx bxs-dashboard"></i>
            <span>PrepHub Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item <?php echo $section === 'overview' ? 'active' : ''; ?>" data-section="overview">
                <a class="sidebar-link">
                    <i class="bx bx-home-alt"></i>
                    <span>Tổng quan</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo $section === 'tests' ? 'active' : ''; ?>" data-section="tests">
                <a class="sidebar-link">
                    <i class="bx bx-book-open"></i>
                    <span>Quản lý đề thi</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo $section === 'users' ? 'active' : ''; ?>" data-section="users">
                <a class="sidebar-link">
                    <i class="bx bx-user"></i>
                    <span>Quản lý user</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo $section === 'attempts' ? 'active' : ''; ?>" data-section="attempts">
                <a class="sidebar-link">
                    <i class="bx bx-history"></i>
                    <span>Lịch sử làm bài</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo $section === 'revenue' ? 'active' : ''; ?>" data-section="revenue">
                <a class="sidebar-link">
                    <i class="bx bx-wallet"></i>
                    <span>Dòng tiền</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <a href="/client/pages/home.php" class="logout-link" style="color: #cbd5e1; margin-bottom: 12px; display: flex;">
                <i class="bx bx-arrow-back"></i>
                <span>Về trang chủ</span>
            </a>
            <a href="/server/controllers/log-out.php" class="logout-link">
                <i class="bx bx-log-out"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <!-- vùng hiển thị nội dung chính -->
    <main class="main-content">

        <!-- phân hệ: tổng quan -->
        <section id="section-overview" class="section-content <?php echo $section === 'overview' ? 'active' : ''; ?>">
            <div class="page-header">
                <h1 class="page-title">Tổng Quan Dashboard</h1>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Tổng số User</h3>
                        <p id="stat-total-users">...</p>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-group"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Tổng số Đề thi</h3>
                        <p id="stat-total-tests">...</p>
                    </div>
                    <div class="stat-icon orange">
                        <i class="bx bx-book-content"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Tổng doanh thu</h3>
                        <p id="stat-total-revenue">...</p>
                    </div>
                    <div class="stat-icon green">
                        <i class="bx bx-money"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>User đã mua gói</h3>
                        <p id="stat-total-purchased">...</p>
                    </div>
                    <div class="stat-icon red">
                        <i class="bx bx-credit-card"></i>
                    </div>
                </div>
            </div>

            <div class="data-card">
                <div class="card-header">
                    <h2 class="card-title">Hướng dẫn & phím tắt quản trị</h2>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <p style="margin-bottom: 12px; line-height: 1.6;">Chào mừng bạn đến với trang quản trị hệ thống PrepHub. Bạn có thể sử dụng các chức năng sau:</p>
                    <ul style="margin-left: 20px; line-height: 1.8; color: var(--text-muted);">
                        <li><strong>Quản lý đề thi</strong>: xem danh sách, cập nhật thông tin nhanh hoặc nhấn đúp chuột vào hàng để biên soạn chi tiết câu hỏi</li>
                        <li><strong>Quản lý user</strong>: xem thông tin, đổi quyền hạn tài khoản hoặc ban/unban người dùng vi phạm quy chế</li>
                        <li><strong>Lịch sử làm bài</strong>: theo dõi các bài thi thử mà học viên đã nộp để đánh giá tiến trình học tập</li>
                        <li><strong>Dòng tiền</strong>: xem báo cáo doanh thu dưới dạng biểu đồ cột và chi tiết các giao dịch mua gói dịch vụ</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- phân hệ: đề thi -->
        <section id="section-tests" class="section-content <?php echo $section === 'tests' ? 'active' : ''; ?>">
            <?php if ($action === 'create' || $action === 'edit'): ?>
                <div class="page-header">
                    <h1 class="page-title"><?php echo $action === 'create' ? 'Tạo Đề Thi Mới' : 'Biên Soạn Câu Hỏi'; ?></h1>
                    <a href="admin.php?section=tests" class="btn-primary" style="background-color: var(--text-muted);">
                        <i class="bx bx-chevron-left"></i> Quay lại danh sách
                    </a>
                </div>

                <div class="container-wrapper">
                    <!-- form tạo đề thi -->
                    <?php include('./components/questions/test-form.php'); ?>

                    <!-- cấu hình đề và phần thi -->
                    <?php include('./components/questions/test-config.php'); ?>

                    <!-- các nút hành động -->
                    <?php include('./components/questions/action-buttons.php'); ?>

                    <!-- vùng chứa danh sách câu hỏi -->
                    <div id="questions-container"></div>
                </div>

                <!-- các mẫu câu hỏi ẩn -->
                <?php include('./components/questions/question-templates.php'); ?>
            <?php else: ?>
                <div class="page-header">
                    <h1 class="page-title">Quản Lý Đề Thi</h1>
                    <a href="admin.php?section=tests&action=create" class="btn-primary">
                        <i class="bx bx-plus"></i> Tạo Bài Thi Mới
                    </a>
                </div>

                <div class="data-card">
                    <div class="toolbar">
                        <input type="text" id="search-tests" class="search-input" placeholder="Tìm kiếm theo tiêu đề...">
                        <select id="filter-tests-premium" class="select-filter">
                            <option value="">Tất cả phân loại</option>
                            <option value="Premium">Premium</option>
                            <option value="Thường">Thường</option>
                        </select>
                        <select id="filter-tests-status" class="select-filter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Hoạt động">Hoạt động</option>
                            <option value="Tạm ẩn">Tạm ẩn</option>
                        </select>
                    </div>

                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Phân loại</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th style="text-align: right;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="testTableBody">
                                <!-- hiển thị bằng JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- modal sửa thông tin đề thi -->
                <div class="modal-overlay" id="editModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Chỉnh Sửa Đề Thi</h3>
                            <button class="close-btn" id="closeModalBtn">&times;</button>
                        </div>
                        <form id="editForm">
                            <div class="modal-body">
                                <input type="hidden" id="edit_id">
                                <div class="form-group">
                                    <label>Tiêu đề</label>
                                    <input type="text" id="edit_title" required>
                                </div>
                                <div class="checkbox-group-wrapper">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="edit_premium">
                                        <label for="edit_premium">Premium</label>
                                    </div>
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="edit_active">
                                        <label for="edit_active">Hoạt động</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-danger" id="btnDelete" style="margin-right: auto;">Xóa</button>
                                <button type="button" class="btn-primary" style="background-color: var(--text-muted);" id="cancelModalBtn">Hủy</button>
                                <button type="submit" class="btn-primary">Lưu lại</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- phân hệ: người dùng -->
        <section id="section-users" class="section-content <?php echo $section === 'users' ? 'active' : ''; ?>">
            <div class="page-header">
                <h1 class="page-title">Quản Lý Người Dùng</h1>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Tổng User</h3>
                        <p id="user-stat-total">...</p>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-user-pin"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>User mới tháng này</h3>
                        <p id="user-stat-new">...</p>
                    </div>
                    <div class="stat-icon green">
                        <i class="bx bx-user-plus"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Không hoạt động 7 ngày</h3>
                        <p id="user-stat-inactive">...</p>
                    </div>
                    <div class="stat-icon orange">
                        <i class="bx bx-user-x"></i>
                    </div>
                </div>
            </div>

            <div class="data-card">
                <div class="toolbar">
                    <input type="text" id="search-users" class="search-input" placeholder="Tìm theo tên hoặc email...">
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Gói premium</th>
                                <th>Ngày đăng ký</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <!-- hiển thị bằng JS -->
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper" id="users-pagination">
                    <!-- phân trang người dùng -->
                </div>
            </div>
        </section>

        <!-- phân hệ: lượt thi -->
        <section id="section-attempts" class="section-content <?php echo $section === 'attempts' ? 'active' : ''; ?>">
            <div class="page-header">
                <h1 class="page-title">Lịch Sử Làm Bài</h1>
            </div>

            <div class="data-card">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Người làm</th>
                                <th>Bài thi</th>
                                <th>Số câu đúng</th>
                                <th>Tổng điểm</th>
                                <th>Thời gian làm</th>
                                <th>Tiến trình thi thử</th>
                                <th>Ngày nộp</th>
                            </tr>
                        </thead>
                        <tbody id="attemptTableBody">
                            <!-- hiển thị bằng JS -->
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper" id="attempts-pagination">
                    <!-- phân trang lượt thi -->
                </div>
            </div>
        </section>

        <!-- phân hệ: doanh thu -->
        <section id="section-revenue" class="section-content <?php echo $section === 'revenue' ? 'active' : ''; ?>">
            <div class="page-header">
                <h1 class="page-title">Dòng Tiền & Doanh Thu</h1>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Doanh thu tháng này</h3>
                        <p id="revenue-stat-month">...</p>
                    </div>
                    <div class="stat-icon green">
                        <i class="bx bx-trending-up"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Doanh thu mọi thời gian</h3>
                        <p id="revenue-stat-alltime">...</p>
                    </div>
                    <div class="stat-icon">
                        <i class="bx bx-line-chart"></i>
                    </div>
                </div>
            </div>

            <div class="data-card" style="margin-bottom: 30px;">
                <div class="card-header">
                    <h2 class="card-title">Biểu đồ doanh thu 12 tháng qua</h2>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="data-card">
                <div class="card-header">
                    <h2 class="card-title">Lịch sử giao dịch thành công</h2>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã giao dịch</th>
                                <th>Khách hàng</th>
                                <th>Gói mua</th>
                                <th>Số tiền</th>
                                <th>Thời hạn</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTableBody">
                            <!-- hiển thị bằng JS -->
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper" id="transactions-pagination">
                    <!-- phân trang giao dịch -->
                </div>
            </div>
        </section>

    </main>

    <!-- điều khiển javascript -->
    <?php if ($section === 'tests' && ($action === 'create' || $action === 'edit')): ?>
        <!-- tải các file script phục vụ form câu hỏi -->
        <script src="../js/questions/state.js"></script>
        <script src="../js/questions/ui.js"></script>
        <script src="../js/questions/api.js"></script>
        <script src="../js/questions/form-fill.js"></script>
        <script src="../js/questions/dom-builder.js"></script>
        <script src="../js/questions/validation.js"></script>
        <script src="../js/questions/utils.js"></script>
        <script src="../js/questions/main.js"></script>
    <?php endif; ?>

    <!-- tải file điều khiển chính dashboard -->
    <script src="../js/admin.js"></script>

</body>
</html>
