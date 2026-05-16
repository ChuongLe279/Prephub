<?php
// Navbar mode: 'dark'  → starts transparent (white text on dark bg), scrolls to light bg
//             'light' → starts transparent (dark text on light bg), scrolls to dark bg
$navbarMode = $navbarMode ?? 'dark';
$scrollThreshold = $scrollThreshold ?? 50;

$userDropdownData = [
    'name' => trim(($_SESSION['first_name'] ?? 'User') . ' ' . ($_SESSION['last_name'] ?? '')),
    'email' => $_SESSION['email'] ?? 'user@example.com',
    'avatarUrl' => 'https://ui-avatars.com/api/?name=' . urlencode(trim(($_SESSION['first_name'] ?? 'User') . ' ' . ($_SESSION['last_name'] ?? ''))) . '&background=05102b&color=fff',
];

$menuItems = [
    ['label' => 'Hồ sơ', 'icon' => 'bx-user-circle', 'href' => 'profile.php'],
    ['label' => 'Thông báo', 'icon' => 'bx-bell', 'href' => '#'],
    ['label' => 'Đề thi', 'icon' => 'bx-edit', 'href' => 'tests.php'],
    ['label' => 'Nâng cấp', 'icon' => 'bxf bx-star', 'href' => 'pricing.php', 'badge' => 'PRO', 'highlight' => true],
];

$bottomItems = [
    ['label' => 'Phím tắt', 'icon' => 'bx-command', 'href' => '?panel=shortcuts'],
    ['label' => 'Có gì mới', 'icon' => 'bx-mail-open', 'href' => '#', 'external' => true],
    ['label' => 'Hỗ trợ', 'icon' => 'bx-help-circle', 'href' => '#', 'external' => true],
];

$shortcuts = [
    ['keys' => ['Ctrl', 'K'], 'desc' => 'Tìm kiếm nhanh'],
    ['keys' => ['G', 'D'], 'desc' => 'Bảng điều khiển'],
    ['keys' => ['Ctrl', '/'], 'desc' => 'Xem phím tắt'],
    ['keys' => ['Esc'], 'desc' => 'Đóng panel'],
];

$panel = $_GET['panel'] ?? 'main';
?>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg" id="mainNavbar" data-navbar-bg="<?= $navbarMode ?>" data-scroll-threshold="<?= $scrollThreshold ?>">
	<div class="container-fluid position-relative">
		<!-- logo: locked left -->
		<a class="navbar-brand" href="home.php">
			<svg viewBox="0 0 2048 2048" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M 986.325 167.538 C 1008.83 166.293 1044.6 166.228 1067.01 167.658 C 1248.97 180.828 1418.33 265.449 1538.11 403.048 C 1633.56 509.36 1693.16 642.997 1708.48 785.049 C 1711.8 815.873 1713.82 855.392 1711.77 886.251 C 1692.28 1204.19 1457.23 1467.26 1143.47 1522.27 C 1066.75 1536.29 992.994 1531.15 915.685 1532.09 C 910.292 1532.15 895.396 1530.62 891.876 1533.72 C 887.797 1544.15 890.037 1682.94 890.025 1706.5 L 890.111 1781 C 890.13 1796.64 890.931 1812.7 888.529 1828.2 C 884.017 1857.32 858.076 1880.47 828.464 1881.07 C 802.066 1881.61 775.022 1881.21 748.404 1881.23 L 577.683 1881.02 L 462.033 1881.04 C 439.179 1881.05 416.002 1881.95 393.297 1879.54 C 363.945 1876.42 340.386 1848.82 339.748 1819.64 C 339.363 1802.06 339.636 1784.35 339.647 1766.72 L 339.685 1664.76 L 339.682 1351.33 L 339.721 1025.24 L 339.7 893.228 C 339.687 872.069 338.752 843.226 339.95 822.761 C 342.441 780.173 348.701 737.889 358.658 696.407 C 385.91 585.498 440.471 483.167 517.372 398.73 C 636.284 266.862 807.736 176.883 986.325 167.538 z" />
			</svg>
			<div class="logo-container">
				<span class="brand-text">Prephub</span>
				<span class="brand-subtext">Luyện thi TOEIC online</span>
			</div>
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
			aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<!-- links: centered -->
			<ul class="navbar-nav nav-center">
				<li class="nav-item"><a class="nav-link active" href="home.php">Trang chủ</a></li>
				<li class="nav-item"><a class="nav-link" href="exam.php">Đề thi</a></li>
				<li class="nav-item"><a class="nav-link" href="#about">Giới thiệu</a></li>
				<li class="nav-item"><a class="nav-link" href="#pricing">Nâng cấp</a></li>
				<div class="nav-indicator" id="navIndicator"></div>
			</ul>
			<!-- login: locked right -->
			<ul class="navbar-nav nav-right">
				<li class="nav-item">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="color: var(--primary); font-weight: 600;">
                                <div class="avatar-wrapper-custom">
                                    <img src="<?= htmlspecialchars($userDropdownData['avatarUrl']) ?>" alt="Avatar" class="avatar-img-custom">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg mt-2" aria-labelledby="userDropdown" style="border-radius: 16px;">
                                <div class="dropdown-panel-wrap">
                                    <!-- Main Panel -->
                                    <div id="mainPanelCustom">
                                        <!-- header: user info -->
                                        <div class="panel-header d-flex align-items-center gap-3">
                                            <div class="avatar-wrapper-custom">
                                                <img src="<?= htmlspecialchars($userDropdownData['avatarUrl']) ?>" alt="avatar" class="avatar-img-custom">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="user-name-text"><?= htmlspecialchars($userDropdownData['name']) ?></span>
                                                <span class="user-email-text"><?= htmlspecialchars($userDropdownData['email']) ?></span>
                                            </div>
                                        </div>

                                        <!-- upgrade banner -->
                                        <div class="px-3 py-3">
                                            <a href="pricing.php" class="upgrade-banner-custom d-flex align-items-center justify-content-center gap-2 text-decoration-none">
                                                <i class='bxf bx-crown text-dark fs-6'></i>
                                                <span class="fw-bold text-dark small">Nâng cấp tài khoản</span>
                                                <div class="shine-overlay-custom"></div>
                                            </a>
                                        </div>

                                        <!-- main menu -->
                                        <div class="px-2 py-1">
                                            <?php foreach ($menuItems as $item): ?>
                                                <a href="<?= htmlspecialchars($item['href']) ?>" class="menu-item-custom d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="menu-icon-custom <?= !empty($item['highlight']) ? 'menu-icon-star-custom' : '' ?>">
                                                            <i class='<?= $item['icon'] ?> fs-5'></i>
                                                        </div>
                                                        <span class="menu-label-custom"><?= htmlspecialchars($item['label']) ?></span>
                                                    </div>
                                                    <?php if (!empty($item['badge'])): ?>
                                                        <span class="pro-badge-custom"><?= htmlspecialchars($item['badge']) ?></span>
                                                    <?php endif; ?>
                                                </a>
                                                <?php if (!empty($item['highlight'])): ?>
                                                    <div class="divider-h"></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- bottom menu -->
                                        <div class="px-2 py-2">
                                            <?php foreach ($bottomItems as $item): ?>
                                                <?php if ($item['label'] === 'Phím tắt'): ?>
                                                    <a href="javascript:void(0)" onclick="togglePanelCustom('shortcutsPanelCustom')" class="menu-item-custom d-flex align-items-center justify-content-between">
                                                <?php else: ?>
                                                    <a href="<?= htmlspecialchars($item['href']) ?>" class="menu-item-custom d-flex align-items-center justify-content-between">
                                                <?php endif; ?>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="menu-icon-custom">
                                                            <i class='bx <?= $item['icon'] ?> fs-5'></i>
                                                        </div>
                                                        <span class="menu-label-custom"><?= htmlspecialchars($item['label']) ?></span>
                                                    </div>
                                                    <?php if (!empty($item['external'])): ?>
                                                        <div class="menu-icon-custom opacity-50">
                                                            <i class='bx bx-arrow-out-up-right-square fs-6'></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endforeach; ?>

                                            <!-- Sign out button -->
                                            <a href="../../server/controllers/log-out.php" class="signout-btn-custom d-flex align-items-center gap-3 w-100 mt-1">
                                                <div class="menu-icon-custom signout-icon-custom">
                                                    <i class='bx  bx-arrow-out-right-circle-half fs-5'></i>
                                                </div>
                                                <span class="signout-label-custom">Đăng xuất</span>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Shortcuts Panel -->
                                    <div id="shortcutsPanelCustom" class="d-none">
                                        <!-- header: back + title -->
                                        <div class="panel-header d-flex align-items-center gap-1">
                                            <a href="javascript:void(0)" onclick="togglePanelCustom('mainPanelCustom')" class="back-btn-custom">
                                                <i class='bx bx-arrow-left-stroke fs-5'></i>
                                            </a>
                                            <span class="user-name-text">Phím tắt</span>
                                        </div>

                                        <div class="shortcuts-body-custom px-4 py-3">
                                            <?php foreach ($shortcuts as $sc): ?>
                                                <div class="shortcut-row-custom d-flex align-items-center justify-content-between">
                                                    <span class="shortcut-desc-custom"><?= htmlspecialchars($sc['desc']) ?></span>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <?php foreach ($sc['keys'] as $key): ?>
                                                            <kbd class="shortcut-key-custom"><?= htmlspecialchars($key) ?></kbd>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                    <script>
                                        function togglePanelCustom(panelId) {
                                            document.getElementById('mainPanelCustom').classList.toggle('d-none', panelId === 'shortcutsPanelCustom');
                                            document.getElementById('shortcutsPanelCustom').classList.toggle('d-none', panelId === 'mainPanelCustom');
                                        }
                                    </script>
                            </div>
                        </div>
                    <?php else: ?>
					    <a href="javascript:void(0)" class="btn-login" id="loginBtn" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</a>
                    <?php endif; ?>
				</li>
			</ul>
		</div>
	</div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var navbar = document.getElementById('mainNavbar');
	if (!navbar) return;
	var threshold = parseInt(navbar.dataset.scrollThreshold) || 50;

	function updateScroll() {
		navbar.classList.toggle('scrolled', window.scrollY > threshold);
	}
	window.addEventListener('scroll', updateScroll);
	updateScroll();

	var navIndicator = document.getElementById('navIndicator');
	var navLinks = document.querySelectorAll('.nav-link');
	var navCenter = document.querySelector('.nav-center');

	if (navIndicator && navCenter) {
		function moveIndicator(element) {
			var rect = element.getBoundingClientRect();
			var parentRect = navCenter.getBoundingClientRect();
			navIndicator.style.width = (rect.width - 32) + 'px';
			navIndicator.style.left = (rect.left - parentRect.left + 16) + 'px';
		}

		function resetIndicator() {
			var activeLink = document.querySelector('.nav-link.active');
			if (activeLink) {
				moveIndicator(activeLink);
			} else {
				navIndicator.style.width = '0';
			}
		}

		navLinks.forEach(function(link) {
			link.addEventListener('mouseenter', function(e) { moveIndicator(e.target); });
		});

		navCenter.addEventListener('mouseleave', resetIndicator);
		window.addEventListener('load', resetIndicator);
		window.addEventListener('resize', resetIndicator);
		resetIndicator();
	}
});
</script>
