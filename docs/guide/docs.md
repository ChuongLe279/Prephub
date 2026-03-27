# Quy tắc viết docs

- Đảm bảo theo format của [TEMPLATE](./docs/code/TEMPLATE.md)
- Đảm bảo hiểu code mình viết để viết docs rõ ràng
- Nên có 1 luồng logic code rõ ràng, cách áp dụng như nào và cũng như code đó áp dụng ở đâu trong dự án
- Nên dùng AI để viết cho nhanh bằng [PROMPT](./docs/code/PROMPT.md)
- Từng function của code nên có comment trên function để hiểu nhanh về function đó
  
Ví dụ:
```typescript
/**Hàm này dùng để ..., có flow ...**/
function ABC(xyz) {
    return xyz
}
```