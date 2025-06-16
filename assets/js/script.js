// Navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation items and content sections
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');
    
    // Add click event listeners to navigation items
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Hide all content sections
            contentSections.forEach(section => section.classList.remove('active'));
            
            // Show the corresponding content section
            const targetSection = this.getAttribute('data-section');
            const targetElement = document.getElementById(targetSection);
            if (targetElement) {
                targetElement.classList.add('active');
            }
        });
    });
    
    // Meal selector functionality
    const mealButtons = document.querySelectorAll('.meal-btn');
    mealButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all meal buttons
            mealButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
        });
    });
    
    // Sleep quality slider
    const sleepQualitySlider = document.getElementById('sleepQuality');
    const qualityValueSpan = document.getElementById('qualityValue');
    
    if (sleepQualitySlider && qualityValueSpan) {
        sleepQualitySlider.addEventListener('input', function() {
            qualityValueSpan.textContent = this.value;
        });
    }
    
    // Form submissions
    const healthForm = document.querySelector('.health-form');
    if (healthForm) {
        healthForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const weight = document.getElementById('weight').value;
            const height = document.getElementById('height').value;
            const bloodPressure = document.getElementById('bloodPressure').value;
            const heartRate = document.getElementById('heartRate').value;
            const notes = document.getElementById('healthNotes').value;
            
            // Calculate BMI if weight and height are provided
            if (weight && height) {
                const bmi = (weight / Math.pow(height / 100, 2)).toFixed(1);
                alert(`Dữ liệu sức khỏe đã được lưu!\nBMI của bạn: ${bmi} kg/m²`);
            } else {
                alert('Dữ liệu sức khỏe đã được lưu!');
            }
            
            // Reset form
            this.reset();
        });
    }
    
    const nutritionForm = document.querySelector('.nutrition-form');
    if (nutritionForm) {
        nutritionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const foodName = document.getElementById('foodName').value;
            const foodQuantity = document.getElementById('foodQuantity').value;
            const activeMeal = document.querySelector('.meal-btn.active');
            
            if (foodName && foodQuantity && activeMeal) {
                const mealType = activeMeal.textContent.trim();
                alert(`Đã thêm "${foodName}" (${foodQuantity}) vào ${mealType}!`);
                
                // Reset form
                this.reset();
            } else {
                alert('Vui lòng điền đầy đủ thông tin!');
            }
        });
    }
    
    const sleepForm = document.querySelector('.sleep-form');
    if (sleepForm) {
        sleepForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const bedtime = document.getElementById('bedtime').value;
            const wakeTime = document.getElementById('wakeTime').value;
            const quality = document.getElementById('sleepQuality').value;
            const notes = document.getElementById('sleepNotes').value;
            
            if (bedtime && wakeTime) {
                // Calculate sleep duration
                const bedtimeDate = new Date(`2024-01-01 ${bedtime}`);
                let wakeTimeDate = new Date(`2024-01-01 ${wakeTime}`);
                
                // If wake time is earlier than bedtime, assume it's the next day
                if (wakeTimeDate < bedtimeDate) {
                    wakeTimeDate.setDate(wakeTimeDate.getDate() + 1);
                }
                
                const duration = (wakeTimeDate - bedtimeDate) / (1000 * 60 * 60);
                
                alert(`Dữ liệu giấc ngủ đã được lưu!\nThời lượng ngủ: ${duration.toFixed(1)} giờ\nChất lượng: ${quality}/10`);
                
                // Reset form
                this.reset();
                document.getElementById('qualityValue').textContent = '5';
            } else {
                alert('Vui lòng điền giờ đi ngủ và thức dậy!');
            }
        });
    }
    
    // Workout start buttons
    const startButtons = document.querySelectorAll('.btn-start');
    startButtons.forEach(button => {
        button.addEventListener('click', function() {
            const workoutName = this.closest('.workout-item').querySelector('h4').textContent;
            
            if (this.textContent.includes('Bắt đầu')) {
                this.innerHTML = '⏸️ Đang tập';
                this.style.background = '#059669';
                alert(`Đã bắt đầu bài tập: ${workoutName}`);
                
                // Simulate workout timer
                let seconds = 0;
                const timer = setInterval(() => {
                    seconds++;
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    const timeString = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
                    this.innerHTML = `⏸️ ${timeString}`;
                }, 1000);
                
                // Stop timer after 5 seconds for demo
                setTimeout(() => {
                    clearInterval(timer);
                    this.innerHTML = '▶️ Bắt đầu';
                    this.style.background = '#3b82f6';
                    alert(`Đã hoàn thành bài tập: ${workoutName}`);
                }, 5000);
            }
        });
    });
    
    // Stress relief activity buttons
    const activityButtons = document.querySelectorAll('.activity-btn');
    activityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const activityName = this.querySelector('h4').textContent;
            const duration = this.querySelector('p').textContent;
            
            alert(`Bắt đầu hoạt động: ${activityName} trong ${duration}`);
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    // Add smooth animations to cards
    const cards = document.querySelectorAll('.card, .stat-card');
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Initially hide cards for animation
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Add hover effects to interactive elements
    const interactiveElements = document.querySelectorAll('.stat-card, .card, .workout-item, .schedule-item, .achievement');
    
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Progress bar animations
    const progressBars = document.querySelectorAll('.progress-fill');
    
    const animateProgressBars = () => {
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    };
    
    // Animate progress bars when dashboard is active
    const dashboardNav = document.querySelector('[data-section="dashboard"]');
    if (dashboardNav) {
        dashboardNav.addEventListener('click', () => {
            setTimeout(animateProgressBars, 300);
        });
    }
    
    // Initial animation for dashboard
    setTimeout(animateProgressBars, 500);
    
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn-primary, .btn-start, .meal-btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add CSS for ripple effect
    const style = document.createElement('style');
    style.textContent = `
        .btn-primary, .btn-start, .meal-btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Mobile menu toggle (for smaller screens)
    const createMobileMenu = () => {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            // Create mobile menu button
            if (!document.querySelector('.mobile-menu-btn')) {
                const menuBtn = document.createElement('button');
                menuBtn.className = 'mobile-menu-btn';
                menuBtn.innerHTML = '☰';
                menuBtn.style.cssText = `
                    position: fixed;
                    top: 1rem;
                    left: 1rem;
                    z-index: 1001;
                    background: #3b82f6;
                    color: white;
                    border: none;
                    width: 48px;
                    height: 48px;
                    border-radius: 8px;
                    font-size: 1.5rem;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
                `;
                
                document.body.appendChild(menuBtn);
                
                menuBtn.addEventListener('click', () => {
                    sidebar.style.transform = sidebar.style.transform === 'translateX(0px)' ? 'translateX(-100%)' : 'translateX(0px)';
                });
                
                // Initially hide sidebar on mobile
                sidebar.style.transform = 'translateX(-100%)';
                sidebar.style.transition = 'transform 0.3s ease';
            }
        }
    };
    
    // Initialize mobile menu
    createMobileMenu();
    
    // Handle window resize
    window.addEventListener('resize', createMobileMenu);
    
    console.log('UTH Health App initialized successfully! 🎉');
});

// Utility functions
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('vi-VN');
};

const calculateBMI = (weight, height) => {
    return (weight / Math.pow(height / 100, 2)).toFixed(1);
};

const getHealthStatus = (bmi) => {
    if (bmi < 18.5) return { status: 'Thiếu cân', color: '#f59e0b' };
    if (bmi < 25) return { status: 'Bình thường', color: '#10b981' };
    if (bmi < 30) return { status: 'Thừa cân', color: '#f59e0b' };
    return { status: 'Béo phì', color: '#ef4444' };
};

// Export functions for potential future use
window.UTHHealthApp = {
    formatDate,
    calculateBMI,
    getHealthStatus
};