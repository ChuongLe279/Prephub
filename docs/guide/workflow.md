# Luồng làm việc

## Giai đoạn đầu
- Hiện tại sau khi set up xong mọi người sẽ làm việc trên branch dev
- Nhớ 2 điều, repo đã forked của bản thân là **origin**, còn repo gốc là **upstream**
- Để đỡ tốn thời gian thì đặt tên nhánh là dev cho nhanh, không cần đặt tên theo task
<br>

- Đầu tiên phải kết nối với upstream

`git remote add upstream https://github.com/versenilvis/IS207-UIT.git`

- Dùng lệnh này để check xem mình đã có kết nối thành công chưa:

`git remote -v`

<br>

- Kết quả sẽ kiểu như này:

`origin`: Repo của bạn (để bạn push code của mình lên).

`upstream`: Repo gốc (để bạn fetch code mới về).

## Làm việc
### Branch
Không cần thiết là lúc nào repo của bản thân cũng phải luôn up-to-date với repo gốc nhưng mà nên đảm bảo không conflict, nếu có thì phải resolve

- Khi muốn pull code repo gốc về thì dùng lệnh:

`git pull upstream dev` (Lưu ý lệnh này sẽ lấy toàn bộ code trên repo về và thêm vào các code mới)

`git pull upstream dev --rebase` (Lệnh này để lấy code về nhưng có xử lí thêm phần code cuả mình nữa)

> [!NOTE]
> upstream là repo gốc  
> dev là nhánh cần làm việc

### Code
- Code rõ ràng, có thể có comment hoặc không
- Phải giải thích được những gì mình làm

### Docs
- Viết docs rõ ràng, theo cách viết docs trong [docs.md](./docs.md)
- Làm xong phần việc của mình phải viết docs

### Commit
- Nếu dùng VSCode thì vào tab Source Control, bấm dấu "+" chọn các file cần commit
- Viết commit đúng theo chuẩn của [commit.md](./commit.md)
- Viết xong bấm nút commit
- Nếu dùng terminal thì gõ

`git push origin dev`

### Push code
- Push lên repo của mình
- Xong sẽ có 1 nút contribute, bấm mở rồi bấm Open pull request (hoặc nó sẽ có sẵn nút Open pull request)
- **VUI LÒNG NHỚ KỸ LÀ PULL REQUEST VỚI REPO GỐC THÌ PHẢI CHỌN NHÁNH DEV TRÊN REPO GỐC, KHÔNG ĐƯỢC PULL REQUEST VÀO NHÁNH MAIN**
- Viết mô tả cũng như tiêu đề theo chuẩn của [pull-request](./pull-request.md)

- Xong thì đợi review và merge code