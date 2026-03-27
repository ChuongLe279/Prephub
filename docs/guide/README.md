# Tổng quát

## Fork dự án về github mình và kết nối (dành cho các thành viên)
- Fork về
- Sau đó git clone về máy

`git clone https://github.com/<USERNAME>/IS207-UIT.git`

*USERNAME là username của github bản thân, đây chỉ là mẫu thôi*

- Vào thư mục (recommend đổi tên thành prehub thay vì IS207-UIT)

`cd IS207-UIT`

- Chạy các lệnh ở phần khởi động bên dưới để set up rồi bắt đầu làm việc, vui lòng đọc tại [workflow.md](./workflow.md)
## Clone dự án về máy (dành cho người khác)
- Clone repo trước

`git clone https://github.com/versenilvis/IS207-UIT.git`

- Vào thư mục

`cd IS207-UIT`

- Sau đó phải đổi qua nhánh dev để làm việc, vì nhánh main chỉ để merge những bản ổn định nhất

`git checkout -b dev`

## Yêu cầu hệ thống
Bây giờ dự án đã chuyển hẳn sang sử dụng docker để đồng bộ hoàn toàn bộ máy của frontend, backend và db

- Cài đặt **Docker desktop** (nếu trên windows/macos) hoặc **Docker** (trên linux)
- Tải về và hướng dẫn tại: https://www.docker.com/products/docker-desktop/
- **Make** để dùng Makefile: https://gnuwin32.sourceforge.net/packages/make.html
- Khuyến khích mọi người tải và dùng lệnh trên terminal (cli)
- Nói chung là cần Docker (để tải Bun, PHP, MySQL) và Make để dùng Makefile
  
## Nếu như chạy lần đầu tiên
### Khởi động

1. Mở terminal (hoặc powershell/cmd) tại ngay thư mục gốc của dự án `IS207-UIT/`
2. Nhập 1 lệnh duy nhất dưới đây để thiết lập và chạy cả 3 hệ thống client, server, database:
   ```bash
   make
   ```
   hoặc
   ```bash
   docker compose up -d
   ```

*Nếu chạy lần đầu thì phải đợi docker pull các image về*

3. Địa chỉ kết nối test thử:
   - **frontend giao diện ui**: truy cập `http://localhost:3000`
   - **backend chạy php**: truy cập `http://localhost:8000`
   - **mysql thông tin kết nối db**: dùng phần mềm (dbeaver, navicat, heidisql...) connect host `localhost` port `3306`, user là user trong .env, mật khẩu là mật khẩu trong .env, database trống tên `prephub`.

### Tắt
Khi nào code xong hoặc muốn tắt đi cho nhẹ ram thì gõ:
```bash
make down
```
hoặc
```bash
docker compose down
```

# Khác
- Cách làm việc với dự án [workflow.md](./workflow.md)
- Cách pull request [pull-request.md](./pull-request.md)
- Cách viết docs [docs.md](./docs.md)
- Cách commit [commit.md](./commit.md)