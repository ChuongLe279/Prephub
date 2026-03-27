# Bảng thiết kế chi tiết chức năng

> Danh sách phân loại toàn bộ hệ thống các tính năng cần phát triển cho website luyện thi toeic prephub

| check list | module chính | chức năng con (tính năng) | mô tả chi tiết yêu cầu | quyền (role) | độ ưu tiên |
|:---:|:---|:---|:---|:---:|:---:|
|  | **tài khoản & bảo mật** | đăng ký tài khoản | tạo mới account với username, password đã mã hóa hash | guest | cao |
|  | | đăng nhập và đăng xuất | sử dụng cơ chế token hoặc session php để kiểm soát bảo mật | guest, user, admin | cao |
|  | | hồ sơ cá nhân | cập nhật thông tin email, đổi mật khẩu và avatar cho người dùng | user | trung bình |
|  | | phân quyền rbac | chặn truy cập trái phép, tách biệt khu vực quản trị viên và người làm bài | admin | cao |
|  | | điều khoản sử dụng | chi tiết về điều khoản dịch vụ, điều khoản về quyền riêng tư và bảo mật, ... | admin | thấp |
|  | **quản trị (admin panel)**| crud đề thi | khả năng thêm, sửa, xóa, lấy chi tiết các bộ đề (chia theo structure 7 parts) | admin | cao |
|  | | tự động hóa import data | đọc file excel/json định dạng trước để insert số lượng lớn dữ liệu bài thi (không dùng form nhập tay) | admin | cao |
|  | | quản lý tệp tin media | lưu trữ hợp lý các file mp3 listening và hình ảnh reading, tránh lặp data gây nặng máy chủ | admin | trung bình |
|  | | quản lý thành viên | kiểm soát danh sách user, chặn tài khoản cố tình vi phạm hoặc kiểm tra log người dùng | admin | trung bình |
|  | **danh sách đề (user)** | thư viện test | liệt kê toàn bộ hệ thống đề thi hiện có, cho phép tìm và xem tag trạng thái (đã làm, cần mua) | user | cao |
|  | | phân trang & tìm kiếm | áp dụng pagination chia trang, thanh search test dựa vào keyword giúp tăng tốc độ tải | user | cao |
|  | | lịch sử dashboard | trang theo dõi lại các kết quả đã làm trước đó, xem thống kê điểm số bản thân | user | trung bình |
|  | | bộ lọc lịch sử | filter danh sách attempt hiển thị điểm theo thời gian cao xuống thấp hoặc tìm lại theo từng đề | user | trung bình |
|  | **hệ thống thi (exam engine)**| giao diện 2 panel chuẩn | màn chia 2 (trái: đoạn văn/audio, phải: câu hỏi/đáp án), sidebar hiển thị list tick trạng thái câu | user | cao |
|  | | trình điều khiển audio | stream ổn định luồng file âm thanh bài nghe, hỗ trợ block seek (ngăn tua lại) | user | cao |
|  | | đồng hồ đếm ngược | timer đếm theo framework thời gian (ví dụ 120 phút), tự kick trigger event `submit` khi về 0 | user | cao |
|  | | lưu trữ dữ liệu tạm | lưu state đáp án tạm vào memory trình duyệt (localstorage) hỗ trợ mạng lag báo mất dữ liệu | user | trung bình |
|  | | nộp bài gián đoạn | auto submit ép nộp bài ngay tại phía local client nếu nhận thấy gián đoạn rớt nền tảng mạng quá lâu | user | thấp |
|  | **hệ thống chấm (scoring)** | chấm điểm backend | api tính toán so lệch đáp án db với dữ liệu đầu vào json, tuyệt đối không chấm bằng js ở frontend | server | cao |
|  | | mapping điểm thực tế | map điểm raw (tổng đúng) ra thang điểm đánh giá toeic quốc tế (cao nhất 990) dựa theo ma trận | server | cao |
|  | | chi tiết review kết quả | liệt kê giao diện tô đỏ xanh lại bảng bài làm so với đáp án gốc, cho phép học viên ôn lại | user | cao |
|  | **thanh toán & mô phỏng vip**| rào cản truy cập | cơ chế chặn user click vào bộ test dán nhãn vip/premium nếu record boolean db chưa sở hữu | server | trung bình |
|  | | form giả lập payment | form bấm nút mua chuyển trạng thái bảng khóa sang đã sỡ hữu (fake unlock logic frontend sang backend) | user | thấp |
