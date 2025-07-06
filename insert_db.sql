INSERT INTO users (full_name, email, password, age, gender) VALUES
('Nguyễn Văn An', 'an.nguyen@uth.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 22, 'male'),
('Trần Thị Bình', 'binh.tran@uth.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 20, 'female'),
('Lê Văn Cường', 'cuong.le@uth.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 23, 'male'),
('Phạm Thị Dung', 'dung.pham@uth.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 21, 'female'),
('Hoàng Văn Em', 'em.hoang@uth.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 24, 'male');

-- Dữ liệu health records cho 7 ngày
INSERT INTO health_records (user_id, weight, height, systolic_bp, diastolic_bp, heart_rate, measure_date, notes) VALUES
-- Nguyễn Văn An (7 ngày)
(1, 70.0, 175, 120, 80, 72, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Cảm thấy khỏe mạnh'),
(1, 69.8, 175, 118, 78, 70, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Tập thể dục đều đặn'),
(1, 69.5, 175, 122, 82, 75, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Hơi mệt do thiếu ngủ'),
(1, 69.2, 175, 119, 79, 73, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Sức khỏe ổn định'),
(1, 68.9, 175, 121, 81, 71, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Cảm thấy nhẹ nhõm'),
(1, 68.7, 175, 117, 77, 69, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Tập luyện hiệu quả'),
(1, 68.5, 175, 120, 80, 72, CURDATE(), 'Mục tiêu giảm cân đạt được'),

-- Trần Thị Bình (7 ngày)
(2, 55.0, 160, 110, 70, 68, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Sức khỏe tốt'),
(2, 55.2, 160, 112, 72, 70, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Tăng cân nhẹ'),
(2, 55.1, 160, 111, 71, 69, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Ăn uống điều độ'),
(2, 54.9, 160, 109, 69, 67, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Tập yoga hiệu quả'),
(2, 54.8, 160, 110, 70, 68, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Cảm thấy thư thái'),
(2, 54.7, 160, 108, 68, 66, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Ngủ ngon hơn'),
(2, 54.6, 160, 110, 70, 68, CURDATE(), 'Sức khỏe ổn định'),

-- Lê Văn Cường (7 ngày)
(3, 75.0, 180, 125, 85, 75, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Cần tập thể dục nhiều hơn'),
(3, 74.8, 180, 124, 84, 74, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Bắt đầu chạy bộ'),
(3, 74.5, 180, 123, 83, 73, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Cảm thấy khỏe hơn'),
(3, 74.2, 180, 122, 82, 72, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Tập gym đều đặn'),
(3, 73.9, 180, 121, 81, 71, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Giảm cân hiệu quả'),
(3, 73.6, 180, 120, 80, 70, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Sức khỏe cải thiện'),
(3, 73.3, 180, 119, 79, 69, CURDATE(), 'Đạt mục tiêu giảm cân'),

-- Phạm Thị Dung (7 ngày)
(4, 52.0, 158, 108, 68, 66, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Sức khỏe tốt'),
(4, 52.1, 158, 109, 69, 67, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Tăng cân nhẹ'),
(4, 52.0, 158, 108, 68, 66, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Ăn uống lành mạnh'),
(4, 51.9, 158, 107, 67, 65, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Tập thể dục đều'),
(4, 51.8, 158, 108, 68, 66, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Cảm thấy khỏe'),
(4, 51.7, 158, 106, 66, 64, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Ngủ ngon'),
(4, 51.6, 158, 108, 68, 66, CURDATE(), 'Sức khỏe ổn định'),

-- Hoàng Văn Em (7 ngày)
(5, 78.0, 182, 128, 88, 78, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Cần giảm cân'),
(5, 77.7, 182, 127, 87, 77, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Bắt đầu tập thể dục'),
(5, 77.4, 182, 126, 86, 76, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Chạy bộ buổi sáng'),
(5, 77.1, 182, 125, 85, 75, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Tập gym đều đặn'),
(5, 76.8, 182, 124, 84, 74, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Giảm cân hiệu quả'),
(5, 76.5, 182, 123, 83, 73, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Sức khỏe cải thiện'),
(5, 76.2, 182, 122, 82, 72, CURDATE(), 'Đạt mục tiêu');

-- Dữ liệu nutrition logs cho 7 ngày
INSERT INTO nutrition_logs (user_id, meal_type, food_name, quantity, calories, created_at) VALUES
-- Nguyễn Văn An - Ngày 1
(1, 'breakfast', 'Bánh mì thịt', 200, 320, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(1, 'breakfast', 'Sữa tươi', 250, 150, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(1, 'lunch', 'Cơm gà nướng', 300, 650, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(1, 'lunch', 'Canh chua', 200, 80, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(1, 'dinner', 'Phở bò', 400, 450, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(1, 'snack', 'Táo', 150, 78, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),

-- Nguyễn Văn An - Ngày 2
(1, 'breakfast', 'Cháo gà', 300, 280, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(1, 'breakfast', 'Trứng luộc', 100, 155, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(1, 'lunch', 'Bún chả', 350, 580, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(1, 'dinner', 'Cơm tấm', 350, 720, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(1, 'snack', 'Chuối', 120, 105, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),

-- Trần Thị Bình - Ngày 1
(2, 'breakfast', 'Yogurt', 200, 120, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(2, 'breakfast', 'Bánh mì ngũ cốc', 100, 250, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(2, 'lunch', 'Salad rau củ', 250, 180, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(2, 'lunch', 'Cá hồi nướng', 150, 280, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(2, 'dinner', 'Súp rau', 300, 150, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(2, 'snack', 'Hạt hạnh nhân', 30, 170, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),

-- Trần Thị Bình - Ngày 2
(2, 'breakfast', 'Smoothie trái cây', 300, 200, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(2, 'lunch', 'Gỏi cuốn', 200, 220, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(2, 'dinner', 'Cơm gạo lứt', 200, 300, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(2, 'snack', 'Cam', 150, 62, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),

-- Lê Văn Cường - Ngày 1
(3, 'breakfast', 'Bánh mì trứng', 250, 380, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(3, 'breakfast', 'Cà phê sữa', 200, 120, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(3, 'lunch', 'Cơm sườn nướng', 400, 850, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(3, 'dinner', 'Bún bò', 450, 520, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(3, 'snack', 'Bánh flan', 150, 200, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),

-- Phạm Thị Dung - Ngày 1
(4, 'breakfast', 'Cháo yến mạch', 250, 200, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(4, 'lunch', 'Cơm chay', 250, 400, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(4, 'dinner', 'Súp bí đỏ', 300, 180, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(4, 'snack', 'Dâu tây', 100, 32, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),

-- Hoàng Văn Em - Ngày 1
(5, 'breakfast', 'Bánh cuốn', 300, 450, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(5, 'lunch', 'Cơm tấm sườn', 450, 950, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(5, 'dinner', 'Phở bò', 500, 560, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(5, 'snack', 'Bánh bao', 200, 300, DATE_SUB(CURDATE(), INTERVAL 6 DAY));

-- Dữ liệu workout logs cho 7 ngày
INSERT INTO workout_logs (user_id, workout_type, duration, calories_burned, workout_date, notes) VALUES
-- Nguyễn Văn An
(1, 'running', 30, 320, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Chạy bộ buổi sáng'),
(1, 'strength', 45, 280, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Tập ngực và vai'),
(1, 'cycling', 40, 350, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Đạp xe công viên'),
(1, 'yoga', 60, 180, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Yoga buổi tối'),
(1, 'swimming', 45, 400, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Bơi lội'),
(1, 'running', 35, 370, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Chạy bộ tăng tốc'),
(1, 'strength', 50, 320, CURDATE(), 'Tập toàn thân'),

-- Trần Thị Bình
(2, 'yoga', 45, 120, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Yoga buổi sáng'),
(2, 'pilates', 30, 150, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Pilates cơ bản'),
(2, 'walking', 60, 200, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Đi bộ công viên'),
(2, 'yoga', 50, 140, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Yoga nâng cao'),
(2, 'dancing', 40, 250, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Nhảy aerobic'),
(2, 'pilates', 35, 170, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Pilates nâng cao'),
(2, 'yoga', 55, 160, CURDATE(), 'Yoga thư giãn'),

-- Lê Văn Cường
(3, 'strength', 60, 400, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Tập gym toàn thân'),
(3, 'running', 25, 280, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Chạy bộ nhanh'),
(3, 'strength', 55, 380, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Tập ngực và tay'),
(3, 'cycling', 35, 320, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Đạp xe đường dài'),
(3, 'strength', 50, 350, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Tập lưng và vai'),
(3, 'running', 30, 340, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Chạy bộ buổi sáng'),
(3, 'strength', 65, 420, CURDATE(), 'Tập chân và mông'),

-- Phạm Thị Dung
(4, 'yoga', 40, 110, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Yoga cơ bản'),
(4, 'walking', 45, 150, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Đi bộ nhẹ nhàng'),
(4, 'pilates', 25, 120, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Pilates cơ bản'),
(4, 'yoga', 35, 100, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Yoga thư giãn'),
(4, 'walking', 50, 170, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Đi bộ công viên'),
(4, 'yoga', 45, 130, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Yoga nâng cao'),
(4, 'pilates', 30, 140, CURDATE(), 'Pilates toàn thân'),

-- Hoàng Văn Em
(5, 'strength', 45, 300, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Tập gym cơ bản'),
(5, 'running', 20, 220, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Chạy bộ chậm'),
(5, 'strength', 40, 280, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Tập ngực'),
(5, 'walking', 60, 200, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Đi bộ dài'),
(5, 'strength', 50, 320, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Tập tay và vai'),
(5, 'cycling', 30, 280, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Đạp xe nhẹ'),
(5, 'strength', 55, 350, CURDATE(), 'Tập toàn thân');

-- Dữ liệu sleep logs cho 7 ngày
INSERT INTO sleep_logs (user_id, bedtime, wake_time, sleep_date, quality, notes) VALUES
-- Nguyễn Văn An
(1, '23:00:00', '07:00:00', DATE_SUB(CURDATE(), INTERVAL 6 DAY), 8, 'Ngủ ngon'),
(1, '22:30:00', '06:30:00', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 9, 'Ngủ sâu'),
(1, '23:30:00', '07:30:00', DATE_SUB(CURDATE(), INTERVAL 4 DAY), 6, 'Hơi mệt'),
(1, '22:00:00', '06:00:00', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 8, 'Ngủ đủ giấc'),
(1, '23:15:00', '07:15:00', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 7, 'Ngủ ổn định'),
(1, '22:45:00', '06:45:00', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 9, 'Ngủ rất ngon'),
(1, '23:00:00', '07:00:00', CURDATE(), 8, 'Ngủ tốt'),

-- Trần Thị Bình
(2, '22:30:00', '06:30:00', DATE_SUB(CURDATE(), INTERVAL 6 DAY), 9, 'Ngủ sâu'),
(2, '22:00:00', '06:00:00', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 8, 'Ngủ ngon'),
(2, '23:00:00', '07:00:00', DATE_SUB(CURDATE(), INTERVAL 4 DAY), 7, 'Ngủ ổn'),
(2, '22:15:00', '06:15:00', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 9, 'Ngủ rất tốt'),
(2, '22:45:00', '06:45:00', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 8, 'Ngủ đủ giấc'),
(2, '22:30:00', '06:30:00', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 9, 'Ngủ sâu'),
(2, '22:00:00', '06:00:00', CURDATE(), 8, 'Ngủ ngon'),

-- Lê Văn Cường
(3, '00:00:00', '08:00:00', DATE_SUB(CURDATE(), INTERVAL 6 DAY), 6, 'Ngủ muộn'),
(3, '23:30:00', '07:30:00', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 7, 'Ngủ ổn'),
(3, '00:15:00', '08:15:00', DATE_SUB(CURDATE(), INTERVAL 4 DAY), 5, 'Thiếu ngủ'),
(3, '23:00:00', '07:00:00', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 8, 'Ngủ tốt'),
(3, '23:45:00', '07:45:00', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 7, 'Ngủ đủ'),
(3, '23:15:00', '07:15:00', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 8, 'Ngủ ngon'),
(3, '23:30:00', '07:30:00', CURDATE(), 7, 'Ngủ ổn định'),

-- Phạm Thị Dung
(4, '22:00:00', '06:00:00', DATE_SUB(CURDATE(), INTERVAL 6 DAY), 9, 'Ngủ rất ngon'),
(4, '22:15:00', '06:15:00', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 8, 'Ngủ sâu'),
(4, '22:30:00', '06:30:00', DATE_SUB(CURDATE(), INTERVAL 4 DAY), 9, 'Ngủ tốt'),
(4, '22:00:00', '06:00:00', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 8, 'Ngủ đủ giấc'),
(4, '22:45:00', '06:45:00', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 9, 'Ngủ rất sâu'),
(4, '22:15:00', '06:15:00', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 8, 'Ngủ ngon'),
(4, '22:30:00', '06:30:00', CURDATE(), 9, 'Ngủ tốt'),

-- Hoàng Văn Em
(5, '00:30:00', '08:30:00', DATE_SUB(CURDATE(), INTERVAL 6 DAY), 5, 'Ngủ muộn'),
(5, '00:00:00', '08:00:00', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 6, 'Thiếu ngủ'),
(5, '23:30:00', '07:30:00', DATE_SUB(CURDATE(), INTERVAL 4 DAY), 7, 'Ngủ ổn'),
(5, '00:15:00', '08:15:00', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 5, 'Ngủ không đủ'),
(5, '23:45:00', '07:45:00', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 7, 'Ngủ đủ'),
(5, '23:15:00', '07:15:00', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 8, 'Ngủ tốt'),
(5, '23:30:00', '07:30:00', CURDATE(), 7, 'Ngủ ổn định');

-- Dữ liệu goals cho các người dùng
INSERT INTO goals (user_id, goal_type, target_value, current_value, unit, target_date, is_active) VALUES
-- Nguyễn Văn An
(1, 'weight_loss', 65.0, 68.5, 'kg', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(1, 'running_distance', 10.0, 5.0, 'km', DATE_ADD(CURDATE(), INTERVAL 14 DAY), TRUE),
(1, 'sleep_hours', 8.0, 8.0, 'hours', DATE_ADD(CURDATE(), INTERVAL 7 DAY), TRUE),

-- Trần Thị Bình
(2, 'weight_maintenance', 55.0, 54.6, 'kg', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(2, 'yoga_sessions', 20.0, 7.0, 'sessions', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(2, 'sleep_quality', 9.0, 8.5, 'rating', DATE_ADD(CURDATE(), INTERVAL 14 DAY), TRUE),

-- Lê Văn Cường
(3, 'weight_loss', 70.0, 73.3, 'kg', DATE_ADD(CURDATE(), INTERVAL 45 DAY), TRUE),
(3, 'strength_workouts', 30.0, 7.0, 'sessions', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(3, 'running_distance', 15.0, 8.0, 'km', DATE_ADD(CURDATE(), INTERVAL 21 DAY), TRUE),

-- Phạm Thị Dung
(4, 'weight_maintenance', 52.0, 51.6, 'kg', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(4, 'yoga_sessions', 15.0, 7.0, 'sessions', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(4, 'sleep_hours', 8.0, 8.0, 'hours', DATE_ADD(CURDATE(), INTERVAL 7 DAY), TRUE),

-- Hoàng Văn Em
(5, 'weight_loss', 70.0, 76.2, 'kg', DATE_ADD(CURDATE(), INTERVAL 60 DAY), TRUE),
(5, 'strength_workouts', 25.0, 7.0, 'sessions', DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
(5, 'sleep_hours', 8.0, 7.5, 'hours', DATE_ADD(CURDATE(), INTERVAL 14 DAY), TRUE);
-- Thông báo hoàn thành
SELECT 'Test data đã được tạo thành công!' as message;
SELECT '5 người dùng với dữ liệu trong 7 ngày' as description;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_health_records FROM health_records;
SELECT COUNT(*) as total_nutrition_logs FROM nutrition_logs;
SELECT COUNT(*) as total_workout_logs FROM workout_logs;
SELECT COUNT(*) as total_sleep_logs FROM sleep_logs;
SELECT COUNT(*) as total_goals FROM goals;