<?php
//Tránh việc người dùng gõ địa chỉ vào URL nhưng chưa đăng nhập
require_once '../../server/middleware/auth.php';
homeRedirect();

$firstName = $_SESSION['first_name'] ?? 'Người';
$lastName = $_SESSION['last_name'] ?? 'dùng';
$email = $_SESSION['email'] ?? 'user@email.com';

$fullName = trim($lastName . ' ' . $firstName);
$getInitial = function ($value) {
    return preg_match('/./u', trim($value), $match) ? $match[0] : '';
};
$initials = strtoupper($getInitial($firstName) . $getInitial($lastName));
//Thong báo nếu đã đổi tên thành công
$changeNameResult = $_SESSION['changeNameResult'] ?? null;
unset($_SESSION['changeNameResult']);
//Thong báo nếu đã đổi mật khẩu thành công
$changePassResult = $_SESSION['changePassResult'] ?? null;
$changePassType = $_SESSION['changePassType'] ?? 'success';
$isChangePassError = $changePassType === 'error' || $changePassResult === "Hãy kiểm tra xem bạn đã nhập đúng mật khẩu hay chưa.";
unset($_SESSION['changePassResult']);
unset($_SESSION['changePassType']);
$deletePasswordResult = $_SESSION['password_confirmation_result'] ?? null;
unset($_SESSION['password_confirmation_result']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ tài khoản</title>

    <?php include './components/metadata.php'; ?>

    <link rel="stylesheet" href="../styles/profile.css">
</head>

<body>
    <!-- GIỮ NGUYÊN NAVBAR -->
    <?php include './components/navBar.php'; ?>

    <div class="page">
        <!-- Thông báo nếu chuyển tên đã thành công hay chưa -->
        <?php if ($changeNameResult): ?>
            <div class="success-message">
                <?= htmlspecialchars($changeNameResult) ?>
            </div>
        <?php endif; ?>
        <!-- Thông báo nếu đổi mật khẩu đã thành công hay chưa -->
        <?php if ($changePassResult): ?>
            <div class="success-message <?= $isChangePassError ? 'error-message' : '' ?>">
                <?= htmlspecialchars($changePassResult) ?>
            </div>
        <?php endif; ?>
        <?php if ($deletePasswordResult): ?>
            <div class="success-message error-message">
                <?= htmlspecialchars($deletePasswordResult) ?>
            </div>
        <?php endif; ?>

        <!-- Tiêu đề của trang -->
        <div class="profile-hero">
            <div class="hero-left">
                <div class="hero-eyebrow">Tài khoản cá nhân</div>
                <h1>Hồ sơ của tôi</h1>
                <p>Quản lý thông tin tài khoản, bảo mật và trạng thái thành viên của bạn.</p>

                <div class="hero-actions">
                    <a href="tests.php" class="hero-btn primary-btn">
                        <i class="fas fa-play"></i>
                        Làm bài ngay
                    </a>

                    <a href="premium.php" class="hero-btn secondary-btn">
                        <i class="fas fa-crown"></i>
                        Nâng cấp Premium
                    </a>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-stat">
                    <div class="hero-stat-val">24</div>
                    <div class="hero-stat-label">Bài đã làm</div>
                </div>

                <div class="hero-stat">
                    <div class="hero-stat-val">580</div>
                    <div class="hero-stat-label">Điểm cao nhất</div>
                </div>

                
            </div>
        </div>

        <!-- Phần ND -->
        <div class="profile-layout">

            <!-- Cột trái -->
            <aside class="left-col">

                <div class="profile-card user-card">
                    <div class="avatar-wrap">
                        <div class="avatar-circle">
                            <?= htmlspecialchars($initials) ?>
                        </div>

                        <button class="avatar-edit" type="button" aria-label="Chỉnh sửa ảnh đại diện">
                            <i class="fas fa-pen"></i>
                        </button>
                    </div>

                    <div class="user-name"><?= htmlspecialchars($fullName) ?></div>
                    <div class="user-email"><?= htmlspecialchars($email) ?></div>

                    <div class="plan-pill">
                        <i class="fas fa-bolt"></i>
                        Gói miễn phí
                    </div>

                    <div class="user-divider"></div>

                    <div class="quick-stats">
                        <div class="quick-stat">
                            <div class="quick-icon blue">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <div class="quick-val">24</div>
                                <div class="quick-label">Bài đã làm</div>
                            </div>
                        </div>

                        <div class="quick-stat">
                            <div class="quick-icon green">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <div class="quick-val">580</div>
                                <div class="quick-label">Điểm TB</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-card premium-card">
                    <div class="premium-icon">
                        <i class="fas fa-crown"></i>
                    </div>

                    <div>
                        <h3>Mở khóa đề Premium</h3>
                        <p>Truy cập thêm nhiều bộ đề TOEIC chất lượng cao và theo dõi tiến độ chi tiết hơn.</p>
                    </div>

                    <a href="premium.php" class="premium-link">
                        Xem gói Premium
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </aside>

            <!-- Cột phải -->
            <section class="right-col">

                <!-- INFO ng dùng -->

                <form class="profile-card section-card" method="POST" action="../../server/controllers/profile-controller.php">
                    <div class="section-head">
                        <div class="section-title-wrap">
                            <div class="section-icon">
                                <i class="fas fa-user"></i>
                            </div>

                            <div>
                                <h2>Thông tin cá nhân</h2>
                                <p>Cập nhật thông tin cơ bản dùng cho tài khoản của bạn.</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="i-fname">Tên</label>
                            <input id="i-fname" name="first_name" type="text" value="<?= htmlspecialchars($firstName) ?>">
                        </div>

                        <div class="form-group">
                            <label for="i-lname">Họ</label>
                            <input id="i-lname" name="last_name" type="text" value="<?= htmlspecialchars($lastName) ?>">
                        </div>

                        <div class="form-group full">
                            <label for="i-email">Email</label>
                            <input id="i-email" type="email" value="<?= htmlspecialchars($email) ?>" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="changeName" value="changeUsername"> 
                    <!-- nút cập nhật tên -->
                    <div class="card-actions">
                        <button class="save-btn" type="submit">
                            <i class="fas fa-floppy-disk"></i>
                            Cập nhật tên
                        </button>
                    </div>
                </form>

                <!-- PASSWORD -->
                <form class="profile-card section-card" method="POST" action="../../server/controllers/profile-controller.php">
                    <div class="section-head">
                        <div class="section-title-wrap">
                            <div class="section-icon">
                                <i class="fas fa-lock"></i>
                            </div>

                            <div>
                                <h2>Đổi mật khẩu</h2>
                                <p>Sử dụng mật khẩu mạnh để bảo vệ tài khoản luyện thi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid password-grid">
                        <div class="form-group full">
                            <label for="current-password">Mật khẩu hiện tại</label>
                            <div class="password-box">
                                <input id="current-password" name="current_password" type="password" placeholder="Nhập mật khẩu hiện tại">
                                <button type="button" class="eye-toggle" aria-label="Hiển thị mật khẩu" onclick="togglePassword(this)">
                                    <img src="../img/eye_close.png" alt="" class="eye-icon">
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new-password">Mật khẩu mới</label>
                            <div class="password-box">
                                <input id="new-password" name="new_password" type="password" placeholder="Nhập mật khẩu mới">
                                <button type="button" class="eye-toggle" aria-label="Hiển thị mật khẩu" onclick="togglePassword(this)">
                                    <img src="../img/eye_close.png" alt="" class="eye-icon">
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm-password">Xác nhận mật khẩu</label>
                            <div class="password-box">
                                <input id="confirm-password" name="confirm_password" type="password" placeholder="Nhập lại mật khẩu">
                                <button type="button" class="eye-toggle" aria-label="Hiển thị mật khẩu" onclick="togglePassword(this)">
                                    <img src="../img/eye_close.png" alt="" class="eye-icon">
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="changePassword" value="changePassword">
                    <!--Nút đặt lại mật khaauir-->
                    <div class="card-actions">
                        <button class="save-btn" type="submit">
                            <i class="fas fa-shield-halved"></i>
                            Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
                <!-- CONNECT gg -->
                <div class="profile-card connected-card">
                    <div class="connected-left">
                        <div class="connected-icon google-icon">
                            <i class="fab fa-google"></i>
                        </div>

                        <div class="connected-text">
                            <h2>Tài khoản kết nối</h2>
                            <p>Chưa liên kết với Google</p>
                        </div>
                    </div>

                    <a href="#" class="connect-google-btn">
                        Kết nối
                    </a>
                </div>
                <!-- NÚT xóa tài khoản -->
                <div class="profile-card danger-card">
                    <div class="danger-left">
                        <div class="danger-icon">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>

                        <div>
                            <h2>Khu vực nguy hiểm</h2>
                            <p>Xóa tài khoản sẽ xóa toàn bộ dữ liệu luyện thi và không thể hoàn tác.</p>
                        </div>
                    </div>

                    <button class="danger-btn" id="open-delete-popup" type="button">
                        <i class="fas fa-trash-can"></i>
                        <!-- NÚT xóa tài khoản 1 -->
                        Xóa tài khoản
                    </button>
                </div>

            </section>

        </div>

    </div>

    <div class="delete-popup-overlay" id="delete-popup">
        <div class="delete-popup" role="dialog" aria-modal="true" aria-labelledby="delete-popup-title">
            <button class="delete-popup-close" id="close-delete-popup" type="button" aria-label="Đóng">
                <i class="fas fa-xmark"></i>
            </button>

            <div class="delete-popup-icon">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <!--Cửa sổ pop up nếu bấm nút xóa tài khoản-->
            <h2 id="delete-popup-title">Bạn có chắc chắn chưa?</h2>
            <p>Tài khoản và dữ liệu luyện thi của bạn sẽ bị xóa vĩnh viễn. Hành động này không thể hoàn tác.</p>

            <form method="POST" action="../../server/controllers/profile-controller.php">
                <div class="delete-password-field">
                    <label for="delete-account-password">Nhập mật khẩu để xác nhận</label>
                    <div class="password-box">
                <!--Nút nhập lại mật khẩu để xác nhận là có cho xóa hay không-->
                        <input id="delete-account-password" name="password_confirmation_delete" type="password" placeholder="Nhập lại mật khẩu">
                        <button type="button" class="eye-toggle" aria-label="Hiển thị mật khẩu" onclick="togglePassword(this)">
                            <img src="../img/eye_close.png" alt="" class="eye-icon">
                        </button>
                    </div>
                </div>

                <div class="delete-popup-actions">
                    <button class="delete-cancel-btn" id="cancel-delete-popup" type="button">Hủy</button>
                    <!-- NÚT xóa tài khoản 2 -->
                    <input type="hidden" name="deleteAccount" value="deleteAccount">
                    <button class="delete-confirm-btn" type="submit">Xóa tài khoản</button>
                </div>
            </form>
        </div>
    </div>

    <?php include './components/footer.php'; ?>

    <script src="../js/profile.js"></script>
</body>

</html>
