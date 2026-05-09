document.addEventListener("DOMContentLoaded", function(){
    load_tests();
});

/* Lấy danh sách đề thi và hiển thị lên trang user */
async function load_tests() {
    try {
        const response = await fetch('/api/tests/');
        if (!response.ok) {
            throw new Error("Lỗi không hiển thị được đề thi. Có thể do database trống hoặc lỗi server. Tải lại trang để thử lại.");
        }

        const test_list = await response.json();
        const tests = test_list.data;
        // CHỈ LỌC ĐỀ ACTIVE (Sẽ hiện cả Đề 1 Free và Đề 2 Premium)
        const visibleTests = tests.filter(test => Number(test.is_active) === 1);
        
        const MAX_COLS = 4;
        let test_grid = document.querySelector('.test-grid');
        test_grid.innerHTML = "";

        for (let i = 0; i < visibleTests.length; i += MAX_COLS) {
            let test_row = `<div class="test-row">`;
            for (let j = i; j < i + MAX_COLS && j < visibleTests.length; j++) {
                let currentTest = visibleTests[j];

                // Gắn mác Premium 
                let premiumBadge = currentTest.is_premium 
                    ? `<span class="badge bg-warning text-dark ml-2" style="font-size: 0.8rem;">Premium 👑</span>` 
                    : '';

                // Hiển thị nút bấm (Đã unlock thì Start Test, chưa thì bắt Đăng ký)
                let actionButton = currentTest.is_unlocked 
                    ? `<a href="./exam.php?uuid=${currentTest.uuid}" class="btn btn-outline-primary mt-auto enter-test">Start test</a>`
                    : `<a href="./premium.php" class="btn btn-warning mt-auto enter-test"><i class="fas fa-lock mr-1"></i> Đăng ký Premium</a>`;

                test_row += `
                    <div class="test-item">
                        <div class="card exam-card">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><b>${currentTest.title}</b> ${premiumBadge}</h5>
                                <div class="test-meta">
                                    <span class="testitem-info">
                                        <span class="far fa-clock mr-1"></span>
                                    </span>
                                    <span class="testitem-info">${currentTest.duration / 60} minutes | ${currentTest.total_questions} questions</span>
                                </div>
                                <p class="card-text">${currentTest.description}</p>
                                ${actionButton}
                            </div>
                        </div>
                    </div>
                `;
            }
            test_row += `</div>`;
            test_grid.innerHTML += test_row;    
        }
    } catch (error) {
        console.error(error);
        document.querySelector(".test-grid").innerHTML = error.message;
    }
}