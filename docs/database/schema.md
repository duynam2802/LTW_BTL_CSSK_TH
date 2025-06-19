# Thiết kế cơ sở dữ liệu

## Bảng `user` 


| Tên cột       | Kiểu dữ liệu | Ràng buộc                 | Ghi chú                   |
| ------------- | ------------ | ------------------------- | ------------------------- |
| id            | INT          | PK, AUTO_INCREMENT        | Khóa chính                |
| full_name     | VARCHAR(100) | NOT NULL                  | Họ và tên                 |
| email         | VARCHAR(255) | NOT NULL, UNIQUE          | Địa chỉ email             |
| password_hash | VARCHAR(255) | NOT NULL                  | Mã hóa mật khẩu           |
| gender        | ENUM         |                           | 'male', 'female', 'other' |
| birth_date    | DATE         |                           | Ngày sinh                 |
| created_at    | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP | Thời điểm tạo tài khoản   |

---


## Bảng `goals` – Mục tiêu cá nhân

| Tên cột       | Kiểu dữ liệu | Ràng buộc                 | Ghi chú                        |
| ------------- | ------------ | ------------------------- | ------------------------------ |
| id            | INT          | PK, AUTO_INCREMENT        |                                |
| user_id       | INT          | FK → users(id)            | Khóa ngoại đến bảng người dùng |
| description   | TEXT         |                           | VD: "Giảm 5kg trong 2 tháng"   |
| target_weight | FLOAT        |                           | Đơn vị: kg                     |
| target_date   | DATE         |                           | Ngày kết thúc mục tiêu         |
| created_at    | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP |


## Bảng `health_metrics` – Chỉ số sức khỏe

| Tên cột        | Kiểu dữ liệu | Ràng buộc          | Ghi chú                           |
| -------------- | ------------ | ------------------ | --------------------------------- |
| id             | INT          | PK, AUTO_INCREMENT |                                   |
| user_id        | INT          | FK → users(id)     |                                   |
| recorded_at    | TIMESTAMP    | NOT NULL           | Ngày giờ ghi nhận                 |
| weight         | FLOAT        |                    | Cân nặng (kg)                     |
| height         | FLOAT        |                    | Chiều cao (cm)                    |
| bmi            | FLOAT        |                    | BMI = cân nặng / (chiều cao m)^2  |
| blood_pressure | VARCHAR(20)  |                    | VD: 120/80                        |
| heart_rate     | INT          |                    | Nhịp tim                          |
| note           | TEXT         |                    | Ghi chú sức khỏe (mệt, stress...) |

---


## Bảng `activities` – Hoạt động thể chất

| Tên cột         | Kiểu dữ liệu | Ràng buộc                 | Ghi chú                    |
| --------------- | ------------ | ------------------------- | -------------------------- |
| id              | INT          | PK, AUTO_INCREMENT        |                            |
| user_id         | INT          | FK → users(id)            |                            |
| activity_type   | VARCHAR(50)  | NOT NULL                  | VD: chạy bộ, nâng tạ, yoga |
| duration_min    | INT          |                           | Số phút tập luyện          |
| distance_km     | FLOAT        |                           | Khoảng cách nếu có (km)    |
| reps            | INT          |                           | Số lần lặp (ví dụ tập tạ)  |
| weight_kg       | FLOAT        |                           | Tạ bao nhiêu kg            |
| calories_burned | FLOAT        |                           | Ước lượng hoặc nhập tay    |
| recorded_at     | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP | Ngày giờ ghi nhận          |

---

## Bảng `meals` – Ghi nhận chế độ dinh dưỡng

| Tên cột     | Kiểu dữ liệu | Ràng buộc                 | Ghi chú                            |
| ----------- | ------------ | ------------------------- | ---------------------------------- |
| id          | INT          | PK, AUTO_INCREMENT        |                                    |
| user_id     | INT          | FK → users(id)            |                                    |
| meal_time   | ENUM         |                           | 'breakfast', 'lunch', 'dinner'     |
| description | TEXT         |                           | Mô tả món ăn (có thể JSON sau này) |
| calories    | FLOAT        |                           | Tổng kcal của bữa ăn               |
| protein_g   | FLOAT        |                           | Gam protein                        |
| carb_g      | FLOAT        |                           | Gam carbohydrate                   |
| fat_g       | FLOAT        |                           | Gam chất béo                       |
| recorded_at | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP |                                    |



## Mối quan hệ giữa các bảng

- `users` **1 → n** `goals`
- `users` **1 → n** `health_metrics`
- `users` **1 → n** `activities`
- `users` **1 → n** `meals`

---
