# project folder structure

prephub/
├── client/                # frontend (vanilla js + bootstrap)
│   ├── index.html         # entry point (trang chủ)
│   ├── src/
│   │   ├── main.js        # khởi tạo app, router đơn giản chuyển trang
│   │   ├── api.js         # file dùng chung để gọi fetch/axios lên server php
│   │   ├── exam.js        # logic thi toeic (thời gian, highlight, chuyển câu)
│   │   ├── admin.js       # logic dashboard admin (form tạo đề, import json/excel)
│   │   ├── auth.js        # logic login, register, lưu auth token
│   │   └── components/    # ui components (sidebar, modal kết quả, part 1-7)
│   ├── styles/
│   │   └── main.css       # bootstrap custom, styles cho highlight, đáp án
│   ├── assets/
│   │   ├── audios/        # file mp3 listening part 1-4
│   │   └── images/        # ảnh part 1, part 3, 4, 7
│   └── package.json       # cấu hình dependencies (để chạy bun install)
│
├── server/                # backend api (php thuần)
│   ├── Dockerfile         # file thiết lập môi trường php-apache
│   ├── index.php          # entry point & api router (điều hướng request)
│   ├── config/
│   │   └── database.php   # kết nối pdo mysql
│   ├── controllers/       # xử lý logic các api endpoint
│   │   ├── auth-controller.php
│   │   ├── test-controller.php
│   │   └── score-controller.php
│   ├── models/            # tương tác trực tiếp với db thông qua pdo
│   │   ├── user.php
│   │   ├── test.php
│   │   ├── question.php
│   │   ├── attempt.php
│   │   └── payment.php    # đối tượng xử lý việc check khóa đề auth
│   ├── middleware/        # kiểm tra vòng bảo mật token, phân quyền rbac
│   │   └── auth.php
│   └── utils/             # response json & helpers
│       ├── response.php
│       └── validator.php
│
├── docs/                  # tài liệu dự án tổng hợp
│   ├── code/
│   │   └── folder-structure.md # sơ đồ cây thư mục đang xem này
│   ├── guide/             # thư viện các quy tắc chuẩn làm việc cho team
│   │   ├── README.md           # guide tổng cách clone source và set up
│   │   ├── commit.md           # chuẩn đánh dẫu chữ github commit
│   │   ├── docs.md             # chuẩn quy tắc viết document cho team
│   │   ├── pull-request.md     # chuẩn workflow đẩy code (no merge main)
│   │   └── workflow.md         # luồng thao tác kéo code origin/upstream
│   └── research/          # các bài viết nghiên cứu base (vd: features.md cũ)
│
├── task/                  # khu vực theo dõi phân đồ tiến độ công việc
│   ├── core/              # bản vẽ thiết kế sơ sở nền tảng cứng cho lập trình viên
│   │   ├── api.md
│   │   ├── client.md
│   │   ├── db.md
│   │   └── server.md
│   ├── features.md        # bảng kê phân rã chức năng toàn cục của web
│   ├── general.md         # list khó khăn chung, khối lượng, thông tin dead-line
│   ├── p1.md              # log công việc và tick cho backend/db
│   ├── p2.md              # log công việc exam ui 
│   ├── p3.md              # log công việc form admin ui
│   ├── p4.md              # log công việc module scoring tự chấm 
│   └── p5.md              # log công việc mảng thanh toán mock integration
│
├── docker-compose.yml     # file tổng quản tự động dựng 3 node mysql, php và bun
├── Makefile               # tệp config tổ hợp chuỗi phím rảnh tay (make up, make down)
├── .env.example           # form trắng chia sẻ cho clone git biến db
└── .gitignore             # loại trừ che giấu file .env thật tránh dính rò rỉ mã bảo mật
