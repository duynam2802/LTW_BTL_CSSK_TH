<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $user_id = $_SESSION['user_id'];
    $alerts = [];

    // 1. KIỂM TRA SỨC KHỎE TỔNG THỂ
    $healthQuery = "SELECT weight, height, systolic_bp, diastolic_bp, heart_rate, measure_date FROM health_records WHERE user_id = ? ORDER BY measure_date DESC LIMIT 1";
    $stmt = $conn->prepare($healthQuery);
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    $healthResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($healthResult) {
        $health = $healthResult;
        
        // Tính BMI
        if ($health['weight'] && $health['height']) {
            $bmi = $health['weight'] / pow($health['height'] / 100, 2);
            
            if ($bmi < 18.5) {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '🩺',
                    'title' => 'BMI thấp',
                    'message' => "⚠️ BMI của bạn (" . number_format($bmi, 1) . ") thấp hơn mức bình thường.",
                    'description' => 'BMI dưới 18.5 cho thấy bạn đang thiếu cân. Điều này có thể ảnh hưởng đến sức khỏe và hệ miễn dịch.',
                    'advice' => [
                        'Tăng cường ăn các thực phẩm giàu dinh dưỡng như thịt, cá, trứng, sữa',
                        'Bổ sung protein từ các nguồn thực vật như đậu, hạt',
                        'Tập luyện sức mạnh để tăng cơ bắp',
                        'Ăn 5-6 bữa nhỏ mỗi ngày thay vì 3 bữa lớn',
                        'Tham khảo ý kiến bác sĩ dinh dưỡng nếu cần'
                    ],
                    'severity' => 'warning'
                ];
            } elseif ($bmi > 25) {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '🩺',
                    'title' => 'BMI cao',
                    'message' => "⚠️ BMI của bạn (" . number_format($bmi, 1) . ") cao hơn mức bình thường.",
                    'description' => 'BMI trên 25 cho thấy bạn đang thừa cân. Điều này có thể làm tăng nguy cơ mắc các bệnh tim mạch, tiểu đường.',
                    'advice' => [
                        'Giảm lượng calo nạp vào hàng ngày',
                        'Tăng cường tập luyện cardio ít nhất 150 phút/tuần',
                        'Ăn nhiều rau xanh, trái cây và protein nạc',
                        'Hạn chế đồ ăn nhanh, đồ ngọt và đồ uống có gas',
                        'Theo dõi cân nặng định kỳ và đặt mục tiêu giảm cân an toàn'
                    ],
                    'severity' => 'warning'
                ];
            } else {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '✅',
                    'title' => 'BMI lý tưởng',
                    'message' => "🎉 BMI của bạn (" . number_format($bmi, 1) . ") trong mức bình thường.",
                    'description' => 'BMI trong khoảng 18.5-25 là mức lý tưởng cho sức khỏe. Điều này cho thấy bạn đang duy trì cân nặng hợp lý.',
                    'advice' => [
                        'Tiếp tục duy trì chế độ ăn uống cân bằng',
                        'Tập luyện đều đặn để giữ gìn sức khỏe',
                        'Theo dõi các chỉ số sức khỏe khác',
                        'Duy trì lối sống lành mạnh',
                        'Khám sức khỏe định kỳ hàng năm'
                    ],
                    'severity' => 'success'
                ];
            }
        }
        
        // Kiểm tra huyết áp
        if ($health['systolic_bp'] && $health['diastolic_bp']) {
            if ($health['systolic_bp'] > 140 || $health['diastolic_bp'] > 90) {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '❤️',
                    'title' => 'Huyết áp cao',
                    'message' => "⚠️ Huyết áp của bạn ({$health['systolic_bp']}/{$health['diastolic_bp']} mmHg) cao.",
                    'description' => 'Huyết áp cao (trên 140/90 mmHg) có thể làm tăng nguy cơ mắc bệnh tim, đột quỵ và các vấn đề sức khỏe nghiêm trọng khác.',
                    'advice' => [
                        'Giảm lượng muối trong chế độ ăn (dưới 2.300mg/ngày)',
                        'Tập thể dục aerobic ít nhất 150 phút/tuần',
                        'Duy trì cân nặng hợp lý',
                        'Hạn chế rượu bia và thuốc lá',
                        'Thư giãn, giảm stress và ngủ đủ giấc',
                        'Theo dõi huyết áp thường xuyên và tham khảo ý kiến bác sĩ'
                    ],
                    'severity' => 'warning'
                ];
            } elseif ($health['systolic_bp'] < 90 || $health['diastolic_bp'] < 60) {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '❤️',
                    'title' => 'Huyết áp thấp',
                    'message' => "ℹ️ Huyết áp của bạn ({$health['systolic_bp']}/{$health['diastolic_bp']} mmHg) thấp.",
                    'description' => 'Huyết áp thấp (dưới 90/60 mmHg) có thể gây chóng mặt, mệt mỏi và ngất xỉu. Tuy nhiên, một số người có huyết áp thấp tự nhiên.',
                    'advice' => [
                        'Tăng lượng muối trong chế độ ăn (theo chỉ định bác sĩ)',
                        'Uống đủ nước (2-3 lít/ngày)',
                        'Đứng dậy từ từ, tránh thay đổi tư thế đột ngột',
                        'Mang vớ nén nếu cần thiết',
                        'Ăn nhiều bữa nhỏ thay vì ít bữa lớn',
                        'Tham khảo ý kiến bác sĩ nếu có triệu chứng nghiêm trọng'
                    ],
                    'severity' => 'info'
                ];
            } else {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '❤️',
                    'title' => 'Huyết áp bình thường',
                    'message' => "✅ Huyết áp của bạn ({$health['systolic_bp']}/{$health['diastolic_bp']} mmHg) bình thường.",
                    'description' => 'Huyết áp trong khoảng 90-140/60-90 mmHg được coi là bình thường. Điều này cho thấy hệ tim mạch của bạn đang hoạt động tốt.',
                    'advice' => [
                        'Tiếp tục duy trì lối sống lành mạnh',
                        'Tập thể dục đều đặn',
                        'Ăn uống cân bằng, ít muối',
                        'Kiểm tra huyết áp định kỳ',
                        'Tránh stress và ngủ đủ giấc'
                    ],
                    'severity' => 'success'
                ];
            }
        }
        
        // Kiểm tra nhịp tim
        if ($health['heart_rate']) {
            if ($health['heart_rate'] > 100) {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '💓',
                    'title' => 'Nhịp tim cao',
                    'message' => "⚠️ Nhịp tim của bạn ({$health['heart_rate']} bpm) cao.",
                    'description' => 'Nhịp tim cao (trên 100 bpm) có thể do stress, lo lắng, thiếu ngủ, hoặc các vấn đề tim mạch. Cần theo dõi và điều chỉnh.',
                    'advice' => [
                        'Thực hành các kỹ thuật thư giãn như thiền, yoga, hít thở sâu',
                        'Giảm stress và lo lắng',
                        'Ngủ đủ 7-9 giờ mỗi đêm',
                        'Hạn chế caffeine và nicotine',
                        'Tập thể dục đều đặn nhưng không quá sức',
                        'Tham khảo ý kiến bác sĩ nếu nhịp tim cao kéo dài'
                    ],
                    'severity' => 'warning'
                ];
            } elseif ($health['heart_rate'] < 60) {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '💓',
                    'title' => 'Nhịp tim thấp',
                    'message' => "ℹ️ Nhịp tim của bạn ({$health['heart_rate']} bpm) thấp.",
                    'description' => 'Nhịp tim thấp (dưới 60 bpm) có thể là dấu hiệu của tim khỏe mạnh ở người tập luyện thường xuyên, nhưng cũng có thể do các vấn đề sức khỏe.',
                    'advice' => [
                        'Nếu bạn là vận động viên hoặc tập luyện thường xuyên, đây có thể là bình thường',
                        'Theo dõi các triệu chứng như mệt mỏi, chóng mặt',
                        'Duy trì chế độ tập luyện đều đặn',
                        'Khám sức khỏe định kỳ',
                        'Tham khảo ý kiến bác sĩ nếu có triệu chứng bất thường'
                    ],
                    'severity' => 'info'
                ];
            } else {
                $alerts[] = [
                    'type' => 'health',
                    'icon' => '💓',
                    'title' => 'Nhịp tim bình thường',
                    'message' => "✅ Nhịp tim của bạn ({$health['heart_rate']} bpm) bình thường.",
                    'description' => 'Nhịp tim trong khoảng 60-100 bpm được coi là bình thường. Điều này cho thấy tim của bạn đang hoạt động hiệu quả.',
                    'advice' => [
                        'Tiếp tục duy trì lối sống lành mạnh',
                        'Tập thể dục đều đặn để tăng cường sức khỏe tim mạch',
                        'Theo dõi nhịp tim định kỳ',
                        'Tránh stress và ngủ đủ giấc',
                        'Khám sức khỏe định kỳ hàng năm'
                    ],
                    'severity' => 'success'
                ];
            }
        }
    } else {
        $alerts[] = [
            'type' => 'health',
            'icon' => '📝',
            'title' => 'Thiếu dữ liệu sức khỏe',
            'message' => '📝 Bạn chưa ghi nhận chỉ số sức khỏe nào.',
            'description' => 'Việc theo dõi các chỉ số sức khỏe cơ bản giúp bạn hiểu rõ tình trạng sức khỏe và phát hiện sớm các vấn đề.',
            'advice' => [
                'Ghi nhận cân nặng và chiều cao để tính BMI',
                'Đo huyết áp định kỳ (ít nhất 1 lần/tháng)',
                'Theo dõi nhịp tim khi nghỉ ngơi',
                'Ghi chú các triệu chứng bất thường',
                'Khám sức khỏe tổng quát hàng năm'
            ],
            'severity' => 'info'
        ];
    }

    // 2. KIỂM TRA DINH DƯỠNG
    $nutritionQuery = "SELECT SUM(calories) as total_calories FROM nutrition_logs WHERE user_id = ? AND DATE(created_at) = CURDATE()";
    $stmt = $conn->prepare($nutritionQuery);
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    $nutritionResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $nutrition = $nutritionResult;
    
    $todayCalories = $nutrition['total_calories'] ?? 0;
    $calorieGoal = 2000;
    
    if ($todayCalories > 0) {
        if ($todayCalories < $calorieGoal * 0.7) {
            $alerts[] = [
                'type' => 'nutrition',
                'icon' => '🍽️',
                'title' => 'Thiếu calo',
                'message' => "📉 Lượng calo hôm nay ({$todayCalories} kcal) thấp hơn 70% nhu cầu.",
                'description' => 'Lượng calo quá thấp có thể khiến bạn mệt mỏi, thiếu năng lượng và ảnh hưởng đến sức khỏe tổng thể.',
                'advice' => [
                    'Tăng cường ăn các thực phẩm giàu dinh dưỡng',
                    'Thêm các bữa phụ lành mạnh (trái cây, hạt, sữa chua)',
                    'Ăn đủ 3 bữa chính với khẩu phần phù hợp',
                    'Bổ sung protein từ thịt, cá, trứng, đậu',
                    'Uống đủ nước và các loại nước ép tự nhiên',
                    'Tham khảo ý kiến chuyên gia dinh dưỡng nếu cần'
                ],
                'severity' => 'warning'
            ];
        } elseif ($todayCalories > $calorieGoal * 1.3) {
            $alerts[] = [
                'type' => 'nutrition',
                'icon' => '🍽️',
                'title' => 'Thừa calo',
                'message' => "📈 Lượng calo hôm nay ({$todayCalories} kcal) cao hơn 30% nhu cầu.",
                'description' => 'Lượng calo quá cao có thể dẫn đến tăng cân và các vấn đề sức khỏe liên quan.',
                'advice' => [
                    'Giảm khẩu phần ăn trong các bữa chính',
                    'Chọn thực phẩm ít calo như rau xanh, trái cây',
                    'Hạn chế đồ ăn nhanh, đồ ngọt và đồ uống có gas',
                    'Ăn chậm và nhai kỹ để cảm nhận no sớm hơn',
                    'Tăng cường tập luyện để đốt cháy calo thừa',
                    'Theo dõi cân nặng định kỳ'
                ],
                'severity' => 'warning'
            ];
        } else {
            $alerts[] = [
                'type' => 'nutrition',
                'icon' => '🍽️',
                'title' => 'Dinh dưỡng cân bằng',
                'message' => "✅ Lượng calo hôm nay ({$todayCalories} kcal) phù hợp với nhu cầu.",
                'description' => 'Lượng calo phù hợp giúp duy trì cân nặng và cung cấp đủ năng lượng cho hoạt động hàng ngày.',
                'advice' => [
                    'Tiếp tục duy trì chế độ ăn cân bằng',
                    'Đa dạng hóa thực phẩm để đảm bảo đủ dinh dưỡng',
                    'Ăn nhiều rau xanh và trái cây',
                    'Hạn chế đồ ăn chế biến sẵn',
                    'Uống đủ nước (2-3 lít/ngày)',
                    'Theo dõi dinh dưỡng đều đặn'
                ],
                'severity' => 'success'
            ];
        }
    } else {
        $alerts[] = [
            'type' => 'nutrition',
            'icon' => '🍽️',
            'title' => 'Thiếu dữ liệu dinh dưỡng',
            'message' => '📝 Bạn chưa ghi nhận bữa ăn nào hôm nay.',
            'description' => 'Theo dõi dinh dưỡng giúp bạn hiểu rõ chế độ ăn và đảm bảo cung cấp đủ dinh dưỡng cho cơ thể.',
            'advice' => [
                'Ghi nhận tất cả các bữa ăn trong ngày',
                'Ước tính lượng calo từ các món ăn',
                'Chụp ảnh bữa ăn để dễ dàng ghi nhận',
                'Sử dụng ứng dụng tính calo nếu cần',
                'Theo dõi cảm giác no và mức năng lượng',
                'Tham khảo ý kiến chuyên gia dinh dưỡng'
            ],
            'severity' => 'info'
        ];
    }

    // 3. KIỂM TRA LUYỆN TẬP
    $workoutQuery = "SELECT COUNT(*) as workout_count, SUM(calories_burned) as total_calories_burned FROM workout_logs WHERE user_id = ? AND YEARWEEK(workout_date, 1) = YEARWEEK(CURDATE(), 1)";
    $stmt = $conn->prepare($workoutQuery);
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    $workoutResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $workout = $workoutResult;
    
    $weeklyWorkouts = $workout['workout_count'] ?? 0;
    $weeklyCaloriesBurned = $workout['total_calories_burned'] ?? 0;
    $workoutGoal = 5;
    
    if ($weeklyWorkouts < $workoutGoal * 0.6) {
        $alerts[] = [
            'type' => 'exercise',
            'icon' => '💪',
            'title' => 'Thiếu vận động',
            'message' => "🏃‍♂️ Tuần này bạn mới tập {$weeklyWorkouts}/5 buổi.",
            'description' => 'Tập luyện ít hơn 3 buổi/tuần có thể ảnh hưởng đến sức khỏe tim mạch, cơ bắp và tinh thần.',
            'advice' => [
                'Bắt đầu với các bài tập đơn giản như đi bộ, chạy bộ',
                'Tập luyện ít nhất 30 phút mỗi buổi',
                'Kết hợp cardio và tập sức mạnh',
                'Tìm hoạt động thể thao bạn yêu thích',
                'Tập cùng bạn bè hoặc gia đình để tăng động lực',
                'Đặt mục tiêu nhỏ và tăng dần cường độ'
            ],
            'severity' => 'warning'
        ];
    } elseif ($weeklyWorkouts >= $workoutGoal) {
        $alerts[] = [
            'type' => 'exercise',
            'icon' => '💪',
            'title' => 'Hoàn thành mục tiêu tập luyện',
            'message' => "🎉 Tuyệt vời! Bạn đã hoàn thành mục tiêu tập luyện tuần này ({$weeklyWorkouts}/5 buổi).",
            'description' => 'Duy trì tập luyện đều đặn giúp tăng cường sức khỏe, cải thiện tâm trạng và giảm nguy cơ bệnh tật.',
            'advice' => [
                'Tiếp tục duy trì lịch tập luyện hiện tại',
                'Thử thách bản thân với các bài tập mới',
                'Tăng cường độ hoặc thời gian tập nếu có thể',
                'Nghỉ ngơi đầy đủ giữa các buổi tập',
                'Theo dõi tiến độ và đặt mục tiêu mới',
                'Chia sẻ thành tích với bạn bè để tạo động lực'
            ],
            'severity' => 'success'
        ];
    } else {
        $alerts[] = [
            'type' => 'exercise',
            'icon' => '💪',
            'title' => 'Tiến độ tập luyện tốt',
            'message' => "👍 Tuần này bạn đã tập {$weeklyWorkouts}/5 buổi.",
            'description' => 'Bạn đang trên đường đạt được mục tiêu tập luyện. Tiếp tục duy trì để có sức khỏe tốt nhất.',
            'advice' => [
                'Duy trì lịch tập luyện hiện tại',
                'Tăng thêm 1-2 buổi tập để đạt mục tiêu',
                'Đa dạng hóa các loại bài tập',
                'Theo dõi cảm giác sau mỗi buổi tập',
                'Đặt mục tiêu cụ thể cho tuần tiếp theo',
                'Tìm hiểu thêm về dinh dưỡng cho người tập luyện'
            ],
            'severity' => 'info'
        ];
    }

    // 4. KIỂM TRA GIẤC NGỦ
    $sleepQuery = "SELECT AVG(quality) as avg_quality, AVG(
        CASE 
            WHEN wake_time >= bedtime THEN TIMESTAMPDIFF(MINUTE, bedtime, wake_time)/60
            ELSE TIMESTAMPDIFF(MINUTE, bedtime, DATE_ADD(wake_time, INTERVAL 1 DAY))/60
        END
    ) as avg_duration FROM sleep_logs WHERE user_id = ? AND sleep_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $stmt = $conn->prepare($sleepQuery);
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    $sleepResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $sleep = $sleepResult;
    
    if ($sleep['avg_quality'] && $sleep['avg_duration']) {
        $avgQuality = round($sleep['avg_quality'], 1);
        $avgDuration = round($sleep['avg_duration'], 1);
        
        // Kiểm tra chất lượng giấc ngủ
        if ($avgQuality < 6) {
            $alerts[] = [
                'type' => 'sleep',
                'icon' => '🌙',
                'title' => 'Chất lượng giấc ngủ kém',
                'message' => "😴 Chất lượng giấc ngủ trung bình {$avgQuality}/10 thấp.",
                'description' => 'Chất lượng giấc ngủ kém có thể ảnh hưởng đến sức khỏe, tâm trạng và hiệu suất làm việc.',
                'advice' => [
                    'Tạo thói quen đi ngủ và thức dậy đúng giờ',
                    'Tạo môi trường ngủ thoải mái (tối, yên tĩnh, mát mẻ)',
                    'Hạn chế sử dụng thiết bị điện tử 1 giờ trước khi ngủ',
                    'Tránh caffeine và rượu bia vào buổi tối',
                    'Thực hành các kỹ thuật thư giãn trước khi ngủ',
                    'Tập thể dục đều đặn nhưng không tập gần giờ ngủ',
                    'Tham khảo ý kiến bác sĩ nếu mất ngủ kéo dài'
                ],
                'severity' => 'warning'
            ];
        } elseif ($avgQuality >= 8) {
            $alerts[] = [
                'type' => 'sleep',
                'icon' => '🌙',
                'title' => 'Chất lượng giấc ngủ tốt',
                'message' => "😊 Chất lượng giấc ngủ trung bình {$avgQuality}/10 rất tốt!",
                'description' => 'Chất lượng giấc ngủ tốt giúp cơ thể phục hồi, tăng cường hệ miễn dịch và cải thiện tâm trạng.',
                'advice' => [
                    'Tiếp tục duy trì thói quen ngủ hiện tại',
                    'Duy trì lịch trình ngủ đều đặn',
                    'Tạo môi trường ngủ lý tưởng',
                    'Theo dõi chất lượng giấc ngủ định kỳ',
                    'Chia sẻ bí quyết ngủ tốt với người khác',
                    'Khám sức khỏe định kỳ để đảm bảo sức khỏe tổng thể'
                ],
                'severity' => 'success'
            ];
        } else {
            $alerts[] = [
                'type' => 'sleep',
                'icon' => '🌙',
                'title' => 'Chất lượng giấc ngủ khá',
                'message' => "😴 Chất lượng giấc ngủ trung bình {$avgQuality}/10 khá tốt.",
                'description' => 'Chất lượng giấc ngủ khá tốt, nhưng vẫn có thể cải thiện để có sức khỏe tối ưu.',
                'advice' => [
                    'Tối ưu hóa môi trường ngủ',
                    'Thử các kỹ thuật thư giãn mới',
                    'Điều chỉnh thời gian đi ngủ',
                    'Hạn chế các yếu tố gây rối loạn giấc ngủ',
                    'Theo dõi và ghi chú các yếu tố ảnh hưởng',
                    'Tham khảo các phương pháp cải thiện giấc ngủ'
                ],
                'severity' => 'info'
            ];
        }
        
        // Kiểm tra thời gian ngủ
        if ($avgDuration < 6) {
            $alerts[] = [
                'type' => 'sleep',
                'icon' => '⏰',
                'title' => 'Thời gian ngủ ít',
                'message' => "⏰ Thời gian ngủ trung bình {$avgDuration} giờ/đêm ít.",
                'description' => 'Ngủ ít hơn 6 giờ/đêm có thể ảnh hưởng đến sức khỏe, tăng nguy cơ bệnh tật và giảm hiệu suất làm việc.',
                'advice' => [
                    'Đi ngủ sớm hơn để đảm bảo đủ 7-9 giờ ngủ',
                    'Tạo thói quen đi ngủ đúng giờ',
                    'Tránh các hoạt động kích thích trước khi ngủ',
                    'Tạo môi trường ngủ thoải mái',
                    'Hạn chế caffeine và rượu bia',
                    'Tham khảo ý kiến bác sĩ nếu khó ngủ kéo dài'
                ],
                'severity' => 'warning'
            ];
        } elseif ($avgDuration > 9) {
            $alerts[] = [
                'type' => 'sleep',
                'icon' => '⏰',
                'title' => 'Thời gian ngủ nhiều',
                'message' => "😴 Thời gian ngủ trung bình {$avgDuration} giờ/đêm nhiều.",
                'description' => 'Ngủ quá nhiều có thể là dấu hiệu của mệt mỏi, stress hoặc các vấn đề sức khỏe khác.',
                'advice' => [
                    'Điều chỉnh thời gian đi ngủ và thức dậy',
                    'Tăng cường hoạt động thể chất ban ngày',
                    'Kiểm tra các vấn đề sức khỏe tiềm ẩn',
                    'Cải thiện chất lượng giấc ngủ thay vì tăng thời gian',
                    'Tham khảo ý kiến bác sĩ nếu ngủ quá nhiều kéo dài',
                    'Theo dõi mức năng lượng và tâm trạng'
                ],
                'severity' => 'info'
            ];
        } else {
            $alerts[] = [
                'type' => 'sleep',
                'icon' => '⏰',
                'title' => 'Thời gian ngủ phù hợp',
                'message' => "✅ Thời gian ngủ trung bình {$avgDuration} giờ/đêm phù hợp.",
                'description' => 'Thời gian ngủ trong khoảng 7-9 giờ/đêm là lý tưởng cho sức khỏe và phục hồi cơ thể.',
                'advice' => [
                    'Tiếp tục duy trì lịch trình ngủ hiện tại',
                    'Đảm bảo chất lượng giấc ngủ tốt',
                    'Theo dõi cảm giác khi thức dậy',
                    'Duy trì thói quen ngủ đều đặn',
                    'Tối ưu hóa môi trường ngủ',
                    'Khám sức khỏe định kỳ'
                ],
                'severity' => 'success'
            ];
        }
    } else {
        $alerts[] = [
            'type' => 'sleep',
            'icon' => '🌙',
            'title' => 'Thiếu dữ liệu giấc ngủ',
            'message' => '📝 Bạn chưa ghi nhận dữ liệu giấc ngủ trong 7 ngày qua.',
            'description' => 'Theo dõi giấc ngủ giúp bạn hiểu rõ chất lượng và thời gian ngủ, từ đó cải thiện sức khỏe tổng thể.',
            'advice' => [
                'Ghi nhận thời gian đi ngủ và thức dậy hàng ngày',
                'Đánh giá chất lượng giấc ngủ theo thang điểm 1-10',
                'Ghi chú các yếu tố ảnh hưởng đến giấc ngủ',
                'Tạo thói quen theo dõi giấc ngủ đều đặn',
                'Sử dụng ứng dụng theo dõi giấc ngủ nếu cần',
                'Tham khảo ý kiến chuyên gia nếu có vấn đề về giấc ngủ'
            ],
            'severity' => 'info'
        ];
    }

    // 5. KIỂM TRA STREAK (CHUỖI NGÀY HOẠT ĐỘNG)
    $streakQuery = "SELECT COUNT(DISTINCT DATE(created_at)) as streak_days FROM (
        SELECT created_at FROM nutrition_logs WHERE user_id = ?
        UNION
        SELECT workout_date FROM workout_logs WHERE user_id = ?
        UNION  
        SELECT sleep_date FROM sleep_logs WHERE user_id = ?
    ) all_activities WHERE DATE(created_at) <= CURDATE()";
    $stmt = $conn->prepare($streakQuery);
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $user_id);
    $stmt->bindParam(3, $user_id);
    $stmt->execute();
    $streakResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $streak = $streakResult;
    
    $streakDays = $streak['streak_days'] ?? 0;
    
    if ($streakDays >= 7) {
        $alerts[] = [
            'type' => 'motivation',
            'icon' => '🔥',
            'title' => 'Chuỗi hoạt động xuất sắc',
            'message' => "🔥 Tuyệt vời! Bạn đã duy trì hoạt động sức khỏe liên tục {$streakDays} ngày.",
            'description' => 'Duy trì hoạt động sức khỏe đều đặn trong 7 ngày trở lên cho thấy bạn đang xây dựng thói quen lành mạnh bền vững.',
            'advice' => [
                'Tiếp tục duy trì lịch trình hoạt động hiện tại',
                'Đặt mục tiêu mới để thử thách bản thân',
                'Chia sẻ thành tích với bạn bè và gia đình',
                'Ghi nhận cảm giác và tiến độ hàng ngày',
                'Tự thưởng cho bản thân khi đạt mục tiêu',
                'Tìm hiểu thêm về các hoạt động sức khỏe mới'
            ],
            'severity' => 'success'
        ];
    } elseif ($streakDays >= 3) {
        $alerts[] = [
            'type' => 'motivation',
            'icon' => '👍',
            'title' => 'Chuỗi hoạt động tốt',
            'message' => "👍 Bạn đã duy trì hoạt động sức khỏe {$streakDays} ngày.",
            'description' => 'Bạn đang xây dựng thói quen lành mạnh. Tiếp tục duy trì để có sức khỏe tốt nhất.',
            'advice' => [
                'Duy trì lịch trình hoạt động hiện tại',
                'Tăng dần cường độ và tần suất hoạt động',
                'Đặt mục tiêu đạt 7 ngày liên tục',
                'Theo dõi và ghi chú tiến độ hàng ngày',
                'Tìm động lực từ những người xung quanh',
                'Tự thưởng cho những nỗ lực của bản thân'
            ],
            'severity' => 'info'
        ];
    }

    // 6. GỢI Ý TỔNG HỢP
    $warningCount = count(array_filter($alerts, function($a) { return $a['severity'] === 'warning'; }));
    $successCount = count(array_filter($alerts, function($a) { return $a['severity'] === 'success'; }));
    
    if ($warningCount === 0 && $successCount > 0) {
        $alerts[] = [
            'type' => 'general',
            'icon' => '🎉',
            'title' => 'Sức khỏe xuất sắc',
            'message' => '🎉 Tuyệt vời! Tất cả chỉ số sức khỏe của bạn đều trong mức tốt.',
            'description' => 'Bạn đang duy trì một lối sống lành mạnh với các chỉ số sức khỏe tối ưu. Điều này cho thấy sự nỗ lực và cam kết của bạn với sức khỏe.',
            'advice' => [
                'Tiếp tục duy trì lối sống lành mạnh hiện tại',
                'Chia sẻ kinh nghiệm với người khác',
                'Đặt mục tiêu mới để thử thách bản thân',
                'Khám sức khỏe định kỳ để duy trì tình trạng tốt',
                'Tìm hiểu thêm về các hoạt động sức khỏe mới',
                'Ghi nhận và ăn mừng những thành tích của bản thân'
            ],
            'severity' => 'success'
        ];
    } elseif ($warningCount > 0) {
        $alerts[] = [
            'type' => 'general',
            'icon' => '📋',
            'title' => 'Tóm tắt sức khỏe',
            'message' => "📋 Bạn có {$warningCount} vấn đề cần chú ý.",
            'description' => 'Có một số chỉ số sức khỏe cần được cải thiện. Hãy xem các cảnh báo chi tiết bên trên và thực hiện các lời khuyên để cải thiện sức khỏe.',
            'advice' => [
                'Ưu tiên giải quyết các vấn đề sức khỏe quan trọng trước',
                'Đặt mục tiêu cụ thể và thực tế cho từng vấn đề',
                'Theo dõi tiến độ cải thiện định kỳ',
                'Tham khảo ý kiến chuyên gia nếu cần thiết',
                'Duy trì thói quen theo dõi sức khỏe đều đặn',
                'Tìm kiếm sự hỗ trợ từ gia đình và bạn bè'
            ],
            'severity' => 'info'
        ];
    }

    // Sắp xếp alerts theo mức độ ưu tiên: warning > info > success
    usort($alerts, function($a, $b) {
        $priority = ['warning' => 3, 'info' => 2, 'success' => 1];
        return $priority[$b['severity']] - $priority[$a['severity']];
    });

    echo json_encode([
        'success' => true,
        'alerts' => $alerts,
        'summary' => [
            'total_alerts' => count($alerts),
            'warnings' => count(array_filter($alerts, function($a) { return $a['severity'] === 'warning'; })),
            'info' => count(array_filter($alerts, function($a) { return $a['severity'] === 'info'; })),
            'success' => count(array_filter($alerts, function($a) { return $a['severity'] === 'success'; }))
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}
?> 