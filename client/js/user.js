/* Khi load trang sẽ tự động lấy danh sách đề thi từ db */
document.addEventListener("DOMContentLoaded", function(){
    load_tests();
});

/* Lấy danh sách đề thi và hiển thị lên trang user.php (Dạng tóm tắt) */
async function load_tests() {
    try {
        const response = await fetch('/api/tests/');
        if (!response.ok) {
            throw new Error("Lỗi không hiển thị được đề thi. Tải lại trang để thử lại.");
        }

        const test_list = await response.json();
        const tests = test_list.data;
        
        // Chỉ hiện thị các đề thi Active và đã được Unlock (Free hoặc đã mua)
        const visibleTests = tests.filter(test => Number(test.is_active) === 1 && test.is_unlocked === true);

        let test_grid = document.querySelector('.test-grid');
        if (!test_grid) return;
        test_grid.innerHTML = "";

        // Hiển thị tối đa 3 đề thi chưa làm/miễn phí
        const limit = Math.min(3, visibleTests.length);
        for (let i = 0; i < limit; i++){
            const test = visibleTests[i];
            test_grid.innerHTML += `
                <div class="test-card">
                    <div class="test-card-top">
                        <div class="test-type-badge badge-full"><i class="fas fa-file-alt" style="font-size:9px"></i> Full Test</div>
                        <div class="test-name">${test.title}</div>
                        <div class="test-meta">${test.total_questions} câu hỏi · ${test.duration / 60} phút</div>
                    </div>
                    <div class="test-card-bot">  
                        <a href="./exam.php?uuid=${test.uuid}" style="text-decoration: none; color: inherit;">
                            <div class="test-arrow">
                                Làm Bài <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            `;
        }
        
        if (visibleTests.length === 0) {
            test_grid.innerHTML = "<p class='text-muted'>Hiện chưa có đề thi nào khả dụng.</p>";
        }

    } catch (error) {
        console.error(error);
        if (document.querySelector(".test-grid")) {
            document.querySelector(".test-grid").innerHTML = `<p class='text-danger'>${error.message}</p>`;
        }
    }
}
