/*Khi load trang sẽ tự động lấy danh sách đề thi từ db*/
document.addEventListener("DOMContentLoaded", function(){
    load_tests();
})


/*Lấy danh sách đề thi và hiển thị lên trang user*/
async function load_tests() {
    try {
        const response = await fetch('/api/tests/');
        if (!response.ok) {
            throw new Error("Lỗi không hiển thị được đề thi. Có thể do database trống hoặc lỗi server. Tải lại trang để thử lại.");
        }

        const test_list = await response.json();
        const tests = test_list.data;
        //Chỉ hiện thi các đề thi active và không phải premium 
        const visibleTests = tests.filter(test => Number(test.is_active) === 1 && test.is_unlocked === true);

        let test_grid = document.querySelector('.test-grid');
        test_grid.innerHTML = "";

        //Hiển thị 3 đề thi MIỄN PHÍ và is_active ở trang user.php
        //Nếu có thời gian thì sẽ xử lý thêm về phần UI badge (hiện tại đang hard-code bade FULL TEST) (chỉ là UI th, không ảnh hưởng gì đâu)
        //Sau khi có logic nộp bài, trang user.php chỉ hiện thị đề thi chưa làm
        for (let i = 0; i < 3; i++){
            test_grid.innerHTML += `
                <div class="test-card">
                    <div class="test-card-top">
                        <div class="test-type-badge badge-full"><i class="fas fa-file-alt" style="font-size:9px"></i>Full Test</div>
                        <div class="test-name">${visibleTests[i].title}</div>
                        <div class="test-meta">${visibleTests[i].total_questions} câu hỏi · ${visibleTests[i].duration / 60} phút</div>
                    </div>
                    <div class="test-card-bot">  
                        <a href="./exam.php?uuid=${visibleTests[i].uuid}" style="text-decoration: none; color: inherit;">
                            <div class="test-arrow">
                                Làm Bài <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error(error);
        document.querySelector(".test-grid").innerHTML = error.message;
    }
}
