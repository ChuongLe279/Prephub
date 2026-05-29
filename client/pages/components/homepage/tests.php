<section class="test-section container" id="tests">
    <div class="section-header">
        <div>
            <span class="section-badge">Đề thi mới</span>
            <h2 class="section-title">Cập nhật đề thi mới<br>nhất 2026.</h2>
        </div>
        <a href="tests.php" class="view-more-link">Xem thêm<i class='bx bx-chevron-right'></i></a>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-3" id="testSkeleton">
        <?php for ($i = 0; $i < 8; $i++): ?>
            <div class="col">
                <div class="test-card test-skeleton">
                    <div class="sk-base sk-line sk-title"></div>
                    <div class="sk-base sk-line sk-meta"></div>
                    <div class="sk-tags">
                        <div class="sk-base sk-tag"></div>
                        <div class="sk-base sk-tag"></div>
                    </div>
                    <div class="sk-base sk-btn"></div>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-3 d-none" id="testContainer"></div>
    <div class="test-error d-none" id="testError">Không thể tải đề thi. <a href="#">Thử lại</a></div>
</section>

<script>
    (function () {
        const container = document.getElementById('testContainer');
        const skeleton = document.getElementById('testSkeleton');
        const errorBox = document.getElementById('testError');
        const LIMIT = 8;
        const REFRESH = 30000; // tự refresh sau 30s để bắt đề mới

        function formatDuration(seconds) {
            const m = Math.round(seconds / 60);
            return m >= 60 ? `${Math.floor(m / 60)} giờ ${m % 60 ? m % 60 + ' phút' : ''}`.trim() : `${m} phút`;
        }

        // sinh tags từ title
        function inferTags(title) {
            const t = title.toLowerCase();
            const tags = [];
            if (t.includes('full') || t.includes('practice')) tags.push('Full test');
            if (t.includes('listen')) tags.push('Listening');
            if (t.includes('read')) tags.push('Reading');
            if (t.includes('vocab')) tags.push('Vocab');
            if (tags.length === 0) tags.push('TOEIC');
            return tags;
        }

        // kiểm tra đề có mới trong 7 ngày không
        function isNew(createdAt) {
            return (Date.now() - new Date(createdAt).getTime()) < 7 * 86400000;
        }

        function buildCard(test, index) {
            const tags = inferTags(test.title);
            const dur = formatDuration(test.duration || 7200); // Mặc định 120 phút nếu không có
            const qCount = test.total_questions || 200; // Mặc định 200 câu
            const newBadge = isNew(test.created_at) ? '<span class="test-new-badge">Mới</span>' : '';
            const premTag = test.is_premium ? '<span class="test-tag test-tag-premium"><i class="bx bx-lock"></i> Premium</span>' : '';
            
            // Xử lý tiêu đề hiển thị
            let displayTitle = test.title;
            if (test.is_premium == false) {
                displayTitle = `Đề thi TOEIC số ${index + 1}`;
            } else {
                displayTitle = `Đề thi độc quyền TOEIC 2026 số ${index + 1}`;
            }
            
            // Xử lý class nút bấm: Bỏ require-login-btn cho đề Premium chưa mua
            const btnClass = test.is_unlocked 
                ? 'test-btn featured require-login-btn' 
                : (test.is_premium ? 'test-btn' : 'test-btn require-login-btn');
                
            const btnLabel = test.is_unlocked ? 'Làm ngay' : (test.is_premium ? 'Mở khóa' : 'Chi tiết');
            
            // Xử lý đường dẫn: premium chưa unlock → về pricing, còn lại → vào exam
            const href = (!test.is_unlocked && test.is_premium)
                ? 'pricing.php'
                : `exam.php?id=${encodeURIComponent(test.uuid)}`;

            return `<div class="col">
            <div class="test-card ${test.is_premium ? 'premium' : ''}">
                <div class="test-card-top">
                    <h5>${escHtml(displayTitle)}</h5>
                    <p class="test-meta">${qCount} câu hỏi · ${dur}</p>
                </div>
                <div class="test-tags">${tags.map(t => `<span class="test-tag">#${t}</span>`).join('')}${premTag}${newBadge}</div>
                <a href="${href}" class="${btnClass}">${btnLabel}</a>
            </div>
        </div>`;
        }

        function escHtml(s) {
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        async function loadTests() {
            try {
                const res = await fetch('/api/tests');
                const json = await res.json();

                if (!json.success || !Array.isArray(json.data)) throw new Error();

                // Lấy 8 đề mới nhất đang active
                const tests = json.data.filter(t => t.is_active).slice(0, LIMIT);

                skeleton.classList.add('d-none');
                errorBox.classList.add('d-none');

                // Pass index vào buildCard để đánh số thứ tự
                container.innerHTML = tests.map((test, index) => buildCard(test, index)).join('');
                container.classList.remove('d-none');
            } catch {
                skeleton.classList.add('d-none');
                errorBox.classList.remove('d-none');
            }
        }

        loadTests();
        setInterval(loadTests, REFRESH); // tự reload để bắt đề mới

        // retry khi click thử lại
        document.getElementById('testError').addEventListener('click', e => {
            if (e.target.tagName === 'A') { e.preventDefault(); loadTests(); }
        });
        
        // Sự kiện click để hiện modal đăng nhập sử dụng event delegation
        document.addEventListener('click', function (e) {
            const actionBtn = e.target.closest('.require-login-btn');
            
            if (actionBtn) {
                // Kiểm tra biến window.isUserLoggedIn
                if (typeof window.isUserLoggedIn !== 'undefined' && window.isUserLoggedIn === false) {
                    e.preventDefault(); 
                    e.stopPropagation(); // Chặn sủi bọt sự kiện, chống nhảy trang
                    
                    const loginModalBtn = document.getElementById('loginBtn');
                    if (loginModalBtn) {
                        loginModalBtn.click();
                    } else if (typeof bootstrap !== 'undefined') {
                        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                        loginModal.show();
                    }
                }
            }
        });
    })();
</script>