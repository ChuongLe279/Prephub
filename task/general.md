## Tổng quát

| # | Khó khăn | Mô tả | Giải pháp đề xuất | Mức độ |
|---|----------|------|------------------|--------|
| 1 | Thiết kế cấu trúc đề thi | Nhiều dạng câu hỏi (đơn, nhóm, audio, hình ảnh, part TOEIC), dễ bị design sai DB | Thiết kế DB dạng module: Question, Passage, Media, Part; dùng quan hệ 1-n và group_id để gom câu hỏi | Hard |
| 2 | Giao diện admin tạo đề | Nhập tay mất thời gian, import dễ lỗi | Cho phép import Excel/JSON có template chuẩn + validate dữ liệu trước khi lưu | Medium |
| 3 | Hệ thống làm bài thi | Quản lý state (đáp án, thời gian, chuyển câu) phức tạp | Lưu state tạm ở frontend (localStorage) + sync định kỳ lên server | Hard |
| 4 | Xử lý phần listening | Audio load chậm, khó kiểm soát tua lại | Dùng streaming audio + disable seek nếu cần + preload audio | Hard |
| 5 | Chấm điểm và hiển thị kết quả | Logic TOEIC không đơn giản | Tách module scoring riêng, dùng bảng mapping raw score → TOEIC score | Medium |
| 6 | Dashboard người dùng | Cần dữ liệu chi tiết để phân tích | Lưu mỗi attempt riêng + lưu từng câu đúng/sai để phân tích theo part | Medium |
| 7 | Hệ thống bán đề | Thanh toán, mở khóa đề, chống bypass | Lưu trạng thái purchase trong DB + check quyền ở backend API | Hard |
| 8 | Phân quyền người dùng | Guest/user/admin dễ bị truy cập sai | Dùng middleware auth + role-based access control (RBAC) | Medium |
| 9 | Bảo mật đề thi | Nguy cơ lộ đề, API, audio | Không trả full đề qua 1 API, check token, ẩn đáp án phía client | Hard |
|10 | Hiệu năng hệ thống | Load dữ liệu lớn dễ chậm | Pagination + lazy load + cache dữ liệu đề thi | Medium |
|11 | UI/UX | User dễ bị rối khi làm bài | Thiết kế giống real TOEIC: sidebar câu hỏi, highlight câu đã làm | Medium |
|12 | Kiểm thử hệ thống | Nhiều flow dễ bug | Viết test case cho từng flow + test edge cases (mất mạng, hết giờ) | Hard |
|13 | Bản quyền nội dung | Có thể vi phạm nếu dùng đề thật | Tự tạo đề hoặc dùng nguồn open/permission rõ ràng | Medium |
|14 | Phạm vi dự án | Dễ bị quá tải | Chia phase: MVP (thi + chấm điểm) → nâng cao (dashboard + payment) | Hard |

## Khối lượng công việc mỗi tuần
| Week | P1 (Backend + DB) | P2 (Exam UI) | P3 (Admin) | P4 (Scoring + Dashboard) | P5 (Auth + Integration + Payment + UI) |
|------|------------------|-------------|-----------|--------------------------|--------------------------------------|
| 1 | Thiết kế DB + setup project PHP | Setup layout Bootstrap + UI chọn đề | Tạo form nhập câu hỏi | Thiết kế bảng attempts/results | Xây login/register + layout chung (header, footer) |
| 2 | Xây API lấy đề thi | Trang làm bài (chọn đáp án, timer) | Tạo test + gán câu hỏi | Logic submit + chấm điểm | Kết nối frontend với API (hiển thị đề thật) |
| 3 | Tối ưu query + API | Sidebar + highlight câu | Import JSON/Excel | Trang kết quả chi tiết | Kết nối submit bài (frontend → backend) |
| 4 | Refactor DB + hỗ trợ listening data | Tích hợp audio listening | Validate dữ liệu | Dashboard (lịch sử, điểm) | Xây fake payment + unlock đề + hiển thị trạng thái mua |
| 5 | Fix bug backend | Fix UI/UX exam | Hoàn thiện admin UI | Improve dashboard | Test toàn hệ thống + fix bug integration + polish UI |

## Deadline
| Week | Deadline | Duration | Deliverables |
|------|----------|----------|--------------|
| 1 | 03/04/2026 (Friday) | 7 days | Hoàn thành DB schema, login/register, UI cơ bản, form nhập câu hỏi |
| 2 | 10/04/2026 (Friday) | 7 days | Hoàn thành trang làm bài, API đề thi, tạo test, chấm điểm cơ bản |
| 3 | 17/04/2026 (Friday) | 7 days | Hoàn thành import câu hỏi, sidebar exam, trang kết quả, integration submit |
| 4 | 24/04/2026 (Friday) | 7 days | Hoàn thành dashboard, listening, validate dữ liệu, fake payment |
| 5 | 01/05/2026 (Friday) | 7 days | Hoàn thành fix bug, tối ưu UI/UX, test toàn hệ thống |