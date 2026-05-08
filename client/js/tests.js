/*Khi load trang sẽ tự động lấy danh sách đề thi từ db*/
document.addEventListener("DOMContentLoaded", function(){
    load_tests();
})

//Ô filter đề thi ở đầu trang
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
    });
});

/*Lấy danh sách đề thi và hiển thị lên trang user*/
async function load_tests() {
    try {
        const response = await fetch('/api/tests/');
        if (!response.ok) {
            throw new Error("Lỗi không hiển thị được đề thi. Có thể do database trống hoặc lỗi server. Tải lại trang để thử lại.");
        }

        const test_list = await response.json();
        const tests = test_list.data;
        //Chỉ hiện thi các đề thi active và LÀ FREE
        const freeTests = tests.filter(test => Number(test.is_active) === 1 && test.is_unlocked === true);
        //Chỉ hiện thi các đề thi active và LÀ PREMIUM
        const premiumTests = tests.filter(test => Number(test.is_active) === 1 && test.is_unlocked === false);
        //Số tests tối đa hiển thị trên một dòng
        const MAX_COLS = 4;

        //Hiển thị đề thi FREE
        let grid_free = document.querySelector('.grid-free');
        grid_free.innerHTML = "";
        //Tối đa 4 test trên cùng 1 dòng
        freeTests.forEach(test => {
            grid_free.innerHTML += `
                <div class="test-card">
                    <div class="card-top">
                        <h3 class="card-title">${test.title}</h3>
                        <span class="badge-free">Miễn phí</span>
                    </div>

                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="fa-regular fa-clock"></i>
                            ${test.duration / 60} phút
                        </span>

                        <span class="meta-item">
                            <i class="fa-regular fa-rectangle-list"></i>
                            ${test.total_questions} câu
                        </span>
                    </div>

                    <p class="card-desc">
                        Bài kiểm tra ngữ pháp tập trung vào các điểm thường gặp trong đề thi TOEIC.
                    </p>

                    <div class="card-tags">
                        <span class="tag">Full Test</span>
                    </div>

                    <div class="card-footer">
                        <div class="card-stats">
                            <span class="stat-item">
                                <i class="fa-regular fa-user"></i>
                                4,821 lượt
                            </span>

                            <span class="stat-divider"></span>

                            <span class="stat-item score">
                                <i class="fa-regular fa-star"></i>
                                TB 72đ
                            </span>
                        </div>

                        <a href="./exam.php?uuid=${test.uuid}" class="btn-start" style="text-decoration: none;">Làm bài</a>
                    </div>
                </div>
            `;
        });
        

        // Hiển thị đề thi PREMIUM
        let grid_premium = document.querySelector('.grid-premium');
        grid_premium.innerHTML = "";
        //Tối đa 4 test trên cùng 1 dòng
        premiumTests.forEach(test => {
            grid_premium.innerHTML += `
                <div class="test-card premium">
                    <!-- Title đề thi -->
                    <div class="card-top">
                        <div class="card-title">${test.title}</div>
                        <span class="badge badge-premium">✦ Premium</span>
                    </div>

                    <!-- Hiển thị thời gian và số câu -->
                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="fa-regular fa-clock"></i>
                            ${test.duration / 60} phút
                        </span>

                        <span class="meta-item">
                            <i class="fa-regular fa-rectangle-list"></i>
                            ${test.total_questions} câu
                        </span>
                    </div>

                    <!-- Hiển thị test description -->
                    <div class="card-desc">
                        Đề thi mô phỏng chuẩn TOEIC 2024 gồm cả Listening và Reading, kèm giải thích chi tiết.
                    </div>

                    <div class="card-tags">
                        <span class="tag">Full Test</span>
                    </div>

                    <!-- Card footer -->
                    <div class="card-footer">
                        <div class="card-stats">
                            <div class="stat-item">
                                <i class="fa-regular fa-user"></i>
                                <span class="stat-count">6,540 lượt</span>
                            </div>

                            <div class="stat-divider"></div>

                            <div class="stat-item">
                                <i class="fa-regular fa-star"></i>
                                <span class="stat-score">TB 665đ</span>
                            </div>
                        </div>
                        <a href="./exam.php?uuid=${test.uuid}" class="btn-start" style="text-decoration: none;">Làm bài ✦</a>
                    </div>
                </div>
            `;
        });

    } catch (error) {
        console.error(error);
        document.querySelector(".test-grid").innerHTML = error.message;
    }
}
