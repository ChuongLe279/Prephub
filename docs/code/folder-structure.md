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
│   └── package.json       # cấu hình dependencies (nếu dùng npm/bunx)
│
├── server/                # backend api (php thuần)
│   ├── index.php          # entry point & api router (điều hướng request)
│   ├── config/
│   │   └── database.php   # kết nối pdo mysql
│   ├── controllers/       # xử lí logic các api endpoint
│   │   ├── auth-controller.php
│   │   ├── test-controller.php    # api lấy đề thi, danh sách câu hỏi
│   │   └── score-controller.php   # api nộp bài, chấm điểm, thống kê
│   ├── models/            # làm việc trực tiếp với database toeic
│   │   ├── user.php
│   │   ├── test.php       # bảng tests
│   │   ├── question.php   # module câu hỏi (đơn, nhóm, part)
│   │   └── attempt.php    # bảng lịch sử làm bài và điểm số
│   ├── middleware/        # kiểm tra token, phân quyền rbac
│   │   └── auth.php
│   └── utils/             # response json & helpers
│       ├── response.php
│       └── validator.php
│
├── docs/                  # tài liệu dự án
│   ├── README.md          # mục lục tổng
│   ├── research/          # kiến thức nền (toeic target, toeic schema)
│   ├── guide/             # quy tắc (commit, workflow)
│   └── code/
│
├── task/                  # quản lí tiến độ công việc
│   ├── general.md         # khó khăn, khối lượng cv, deadline
│   ├── p1.md              # task be+db hoàng
│   ├── p2.md              # task exam ui huy
│   ├── p3.md              # task admin khang
│   ├── p4.md              # task scoring nhân
│   └── p5.md              # task auth chương
│
└── .gitignore             # loại bỏ file rác, config nhạy cảm
