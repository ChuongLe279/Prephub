<?php
session_start();

//require_once '../../server/middleware/auth.php';
//requireAuth();

$firstName = $_SESSION['first_name'] ?? 'Người';
$lastName = $_SESSION['last_name'] ?? 'dùng';
$email = $_SESSION['email'] ?? 'user@email.com';

$fullName = trim($lastName . ' ' . $firstName);
$getInitial = function ($value) {
    return preg_match('/./u', trim($value), $match) ? $match[0] : '';
};
$initials = strtoupper($getInitial($firstName) . $getInitial($lastName));
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

        <!-- PROFILE HERO -->
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

                <div class="hero-stat">
                    <div class="hero-stat-val">73%</div>
                    <div class="hero-stat-label">Độ chính xác</div>
                </div>
            </div>
        </div>

        <!-- PROFILE CONTENT -->
        <div class="profile-layout">

            <!-- LEFT COLUMN -->
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

            <!-- RIGHT COLUMN -->
            <section class="right-col">

                <!-- ACCOUNT INFO -->
                <div class="profile-card section-card">
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
                            <input id="i-fname" type="text" value="<?= htmlspecialchars($firstName) ?>">
                        </div>

                        <div class="form-group">
                            <label for="i-lname">Họ</label>
                            <input id="i-lname" type="text" value="<?= htmlspecialchars($lastName) ?>">
                        </div>

                        <div class="form-group full">
                            <label for="i-email">Email</label>
                            <input id="i-email" type="email" value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div class="form-group">
                            <label for="i-phone">Số điện thoại</label>
                            <input id="i-phone" type="text" placeholder="+84 90 000 0000">
                        </div>

                        <div class="form-group">
                            <label for="i-dob">Ngày sinh</label>
                            <input id="i-dob" type="text" placeholder="01/01/2000">
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="save-btn" type="button">
                            <i class="fas fa-floppy-disk"></i>
                            Cập nhật thông tin
                        </button>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="profile-card section-card">
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
                                <input id="current-password" type="password" placeholder="Nhập mật khẩu hiện tại">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new-password">Mật khẩu mới</label>
                            <div class="password-box">
                                <input id="new-password" type="password" placeholder="Nhập mật khẩu mới">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm-password">Xác nhận mật khẩu</label>
                            <div class="password-box">
                                <input id="confirm-password" type="password" placeholder="Nhập lại mật khẩu">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="save-btn" type="button">
                            <i class="fas fa-shield-halved"></i>
                            Cập nhật mật khẩu
                        </button>
                    </div>
                </div>
                <!-- CONNECTED ACCOUNT -->
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
                <!-- DANGER ZONE -->
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

                    <button class="danger-btn" type="button">
                        <i class="fas fa-trash-can"></i>
                        Xóa tài khoản
                    </button>
                </div>

            </section>

        </div>

    </div>

    <?php include './components/footer.php'; ?>

    <script src="../js/profile.js"></script>
</body>

</html>