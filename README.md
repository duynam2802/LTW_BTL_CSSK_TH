# UTH Health & Fitness Tracking System

Hệ thống theo dõi sức khỏe và thể dục dành cho sinh viên Đại học Giao thông Vận tải TP.HCM.

## Tính năng

- **Đăng nhập/Đăng ký**: Xác thực người dùng an toàn
- **Dashboard**: Tổng quan về tình trạng sức khỏe
- **Theo dõi sức khỏe**: Ghi nhận cân nặng, BMI, huyết áp, nhịp tim
- **Quản lý dinh dưỡng**: Theo dõi calo và bữa ăn hàng ngày
- **Lịch trình luyện tập**: Ghi nhận và theo dõi các buổi tập
- **Quản lý giấc ngủ**: Theo dõi chất lượng giấc ngủ
- **Hồ sơ cá nhân**: Quản lý thông tin và mục tiêu

## Công nghệ sử dụng

- **Frontend**: HTML5, CSS3, JavaScript 
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+

## Cài đặt

### Yêu cầu hệ thống

- PHP 7.4 hoặc cao hơn
- MySQL 5.7 hoặc cao hơn
- Web server (Apache/Nginx)

### Hướng dẫn cài đặt


**Cấu hình database**:
   - Tạo database MySQL mới
   - Import file `database.sql`
   - Cập nhật thông tin kết nối trong `api/config/database.php`


## Tài khoản demo

- **Email**: admin@uth.edu.vn
- **Password**: password

## Cấu trúc thư mục

```
LTW_BTL_CSSK_TH/
├── index.php              # Trang chính
├── index_html.html        # Trang chính (HTML version)
├── home.html              # Trang chủ
├── login.html             # Trang đăng nhập
├── register.html          # Trang đăng ký
├── UserGuide.html         # Hướng dẫn sử dụng
├── assets/
│   ├── css/
│   │   ├── style.css      # CSS chính
│   │   ├── auth.css       # CSS cho authentication
│   │   ├── home.css       # CSS cho trang chủ
│   │   ├── footer.css     # CSS cho footer
│   │   ├── toast.css      # CSS cho thông báo
│   │   └── UserGuide.css  # CSS cho hướng dẫn
│   ├── js/
│   │   ├── script.js      # JavaScript chính
│   │   ├── auth.js        # JavaScript cho authentication
│   │   └── UserGuide.js   # JavaScript cho hướng dẫn
│   └── img/               # Thư mục hình ảnh
├── api/
│   ├── config/
│   │   └── database.php   # Cấu hình database
│   ├── auth/              # API authentication
│   │   ├── login.php
│   │   ├── logout.php
│   │   └── register.php
│   ├── dashboard/         # API dashboard
│   │   ├── activities.php
│   │   ├── goals.php
│   │   └── stats.php
│   ├── health/            # API sức khỏe
│   │   ├── add.php
│   │   ├── delete.php
│   │   └── stats.php
│   ├── nutrition/         # API dinh dưỡng
│   │   ├── add.php
│   │   ├── delete.php
│   │   ├── stats.php
│   │   ├── stats_macro.php
│   │   ├── today.php
│   │   ├── history.php
│   │   └── full_history.php
│   ├── workouts/          # API luyện tập
│   │   ├── add.php
│   │   ├── delete.php
│   │   ├── stats.php
│   │   ├── today.php
│   │   └── history.php
│   ├── sleep/             # API giấc ngủ
│   │   ├── add.php
│   │   ├── delete.php
│   │   ├── stats.php
│   │   └── history.php
│   └── alert/             # API thông báo
│       └── today.php
├── docs/                  # Tài liệu
│   ├── database/
│   │   └── schema.md
│   └── external-apis.md
├── supabase/              # Supabase migrations
│   └── migrations/
├── database.sql           # Schema database
└── README.md
```

## API Endpoints

### Authentication
- `POST /api/auth/login.php` - Đăng nhập
- `POST /api/auth/register.php` - Đăng ký
- `POST /api/auth/logout.php` - Đăng xuất

### Dashboard
- `GET /api/dashboard/stats.php` - Thống kê tổng quan
- `GET /api/dashboard/goals.php` - Mục tiêu
- `GET /api/dashboard/activities.php` - Hoạt động gần đây

### Health
- `POST /api/health/add.php` - Thêm chỉ số sức khỏe
- `GET /api/health/stats.php` - Thống kê sức khỏe

### Nutrition
- `POST /api/nutrition/add.php` - Thêm món ăn
- `GET /api/nutrition/stats.php` - Thống kê dinh dưỡng
- `GET /api/nutrition/today.php` - Bữa ăn hôm nay

### Workouts
- `POST /api/workouts/add.php` - Thêm buổi tập
- `GET /api/workouts/stats.php` - Thống kê luyện tập
- `GET /api/workouts/history.php` - Lịch sử luyện tập

### Sleep
- `POST /api/sleep/add.php` - Thêm dữ liệu giấc ngủ
- `GET /api/sleep/stats.php` - Thống kê giấc ngủ
- `GET /api/sleep/history.php` - Lịch sử giấc ngủ


## Bảo mật
- Password hashing với bcrypt
- Session management
- SQL injection prevention
- XSS protection
- CSRF protection
