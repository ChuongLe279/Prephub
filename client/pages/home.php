<?php
session_start();
// XỬ LÝ NHẬP SAI TÀI KHOẢN HOẶC MẬT KHẨU
$errors = [
	'login' => $_SESSION['login_error'] ?? '',
	'register' => $_SESSION['register_error'] ?? '',
	'success' => $_SESSION['register_success'] ?? '',
];
$activeAuthForm = $_SESSION['active_form'] ?? 'login';
session_unset(); //Session vẫn còn hoạt động nhưng bỏ hết các biến

function showError($error) {
	return !empty($error) ? "<p class='error-message' style='color: #ef4444; font-size: 0.85rem; margin-bottom: 12px; font-weight: 500;'>$error</p>" : '';
}

function showSuccess($msg) {
	return !empty($msg) ? "<p class='success-message' style='color: #10b981; font-size: 0.85rem; margin-bottom: 12px; font-weight: 500;'>$msg</p>" : '';
}
?>
<!doctype html>
<html lang="vi">

<head>
	<?php include './components/metadata.php'; ?>
	<title>Prephub - Luyện thi TOEIC online</title>
</head>

<body>

	<?php include './components/homepage/navbar.php'; ?>

	<?php include './components/homepage/hero.php'; ?>

	<?php include './components/homepage/about.php'; ?>

	<?php include './components/homepage/tests.php'; ?>

	<?php include './components/homepage/pricing.php'; ?>

	<?php include './components/homepage/feedback.php'; ?>

	<?php include './components/homepage/banner.php'; ?>

	<?php include './components/homepage/footer.php'; ?>

	<?php include './components/homepage/loginModal.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		// navbar scroll
		const navbar = document.getElementById('mainNavbar');
		window.addEventListener('scroll', () => {
			navbar.classList.toggle('scrolled', window.scrollY > 50);
		});

		// dynamic nav indicator
		const navIndicator = document.getElementById('navIndicator');
		const navLinks = document.querySelectorAll('.nav-link');
		const navCenter = document.querySelector('.nav-center');

		function moveIndicator(element) {
			const rect = element.getBoundingClientRect();
			const parentRect = navCenter.getBoundingClientRect();
			navIndicator.style.width = `${rect.width - 32}px`; // Adjust for padding
			navIndicator.style.left = `${rect.left - parentRect.left + 16}px`;
		}

		function resetIndicator() {
			const activeLink = document.querySelector('.nav-link.active');
			if (activeLink) {
				moveIndicator(activeLink);
			} else {
				navIndicator.style.width = '0';
			}
		}

		navLinks.forEach(link => {
			link.addEventListener('mouseenter', (e) => moveIndicator(e.target));
		});

		navCenter.addEventListener('mouseleave', resetIndicator);

		// Initial position
		window.addEventListener('load', resetIndicator);
		window.addEventListener('resize', resetIndicator);

		// pricing toggle
		const priceToggle = document.getElementById('priceToggle');
		const monthlyBtn = document.getElementById('monthlyBtn');
		const yearlyBtn = document.getElementById('yearlyBtn');
		let isYearly = false;

		function updatePrices(yearly) {
			isYearly = yearly;
			priceToggle.classList.toggle('yearly', yearly);

			monthlyBtn.classList.toggle('active', !yearly);
			monthlyBtn.classList.toggle('inactive', yearly);
			yearlyBtn.classList.toggle('active', yearly);
			yearlyBtn.classList.toggle('inactive', !yearly);

			document.querySelectorAll('.plan-price').forEach(el => {
				const fullPrice = yearly ? el.dataset.yearly : el.dataset.monthly;
				const parts = fullPrice.split('/');
				if (parts.length === 2) {
					el.innerHTML = `${parts[0]}<span class="price-suffix">/${parts[1]}</span>`;
				} else {
					el.textContent = fullPrice;
				}
			});
		}

		priceToggle.addEventListener('click', () => updatePrices(!isYearly));
		monthlyBtn.addEventListener('click', () => updatePrices(false));
		yearlyBtn.addEventListener('click', () => updatePrices(true));

		// login modal
		const loginForm = document.getElementById('loginForm');
		const loginBtn = document.getElementById('loginBtn');
		const userAvatar = document.getElementById('userAvatar');
		// auth modal logic
		const authWrapper = document.getElementById('authWrapper');
		const toSignup = document.getElementById('toSignup');
		const toSignin = document.getElementById('toSignin');
		const loginModal = loginModalEl ? new bootstrap.Modal(loginModalEl) : null;

		if (toSignup && toSignin && authWrapper) {
			toSignup.addEventListener('click', (e) => {
				e.preventDefault();
				authWrapper.classList.add('signup-active');
			});
			toSignin.addEventListener('click', (e) => {
				e.preventDefault();
				authWrapper.classList.remove('signup-active');
			});
		}

		// Check if we need to show the modal (on error)
		<?php if (!empty($errors['login']) || !empty($errors['register']) || !empty($errors['success'])): ?>
			if (loginModal) {
				<?php if ($activeAuthForm === 'register'): ?>
					authWrapper.classList.add('signup-active');
				<?php endif; ?>
				loginModal.show();
			}
		<?php endif; ?>

		// eye toggle
		document.querySelectorAll('.eye-toggle').forEach(btn => {
			btn.addEventListener('click', function() {
				const input = this.previousElementSibling;
				const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
				input.setAttribute('type', type);
				const icon = this.querySelector('i');
				if (type === 'text') {
					icon.classList.replace('bx-hide', 'bx-show');
				} else {
					icon.classList.replace('bx-show', 'bx-hide');
				}
			});
		});
		});
	</script>
</body>

</html>