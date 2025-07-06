// Authentication JavaScript
document.addEventListener('DOMContentLoaded', function() {
    setupAuthForms();
});

function setupAuthForms() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
}

// Toast notification
function showToast(message, type = 'info') {
    // Tạo container nếu chưa có
    let toast = document.getElementById('toastNotification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toastNotification';
        document.body.appendChild(toast);
    }
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <span class="message">${message}</span>
        <button class="close-btn" onclick="this.parentElement.classList.remove('show')">&times;</button>
        <div class="progress-bar"></div>
    `;
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3500);
}

async function handleLogin(e) {
    e.preventDefault();
    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        remember: document.getElementById('remember').checked
    };
    if (!formData.email || !formData.password) {
        showToast('Vui lòng điền đầy đủ thông tin', 'error');
        return;
    }
    try {
        showLoading();
        const response = await fetch('api/auth/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();
        if (response.ok) {
            showToast('Đăng nhập thành công!', 'success');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
        } else {
            showToast(result.message || 'Đăng nhập thất bại', 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showToast('Có lỗi xảy ra khi đăng nhập', 'error');
    } finally {
        hideLoading();
    }
}

async function handleRegister(e) {
    e.preventDefault();
    const formData = {
        fullName: document.getElementById('fullName').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        confirmPassword: document.getElementById('confirmPassword').value
    };
    if (!formData.fullName || !formData.email || !formData.password || !formData.confirmPassword) {
        showToast('Vui lòng điền đầy đủ thông tin', 'error');
        return;
    }
    if (formData.password.length < 6) {
        showToast('Mật khẩu phải có ít nhất 6 ký tự', 'error');
        return;
    }
    if (formData.password !== formData.confirmPassword) {
        showToast('Mật khẩu xác nhận không khớp', 'error');
        return;
    }
    if (!document.getElementById('terms').checked) {
        showToast('Vui lòng đồng ý với điều khoản sử dụng', 'error');
        return;
    }
    try {
        showLoading();
        const response = await fetch('api/auth/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();
        if (response.ok) {
            showToast('Đăng ký thành công! Chuyển hướng đến trang đăng nhập...', 'success');
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 2000);
        } else {
            showToast(result.message || 'Đăng ký thất bại', 'error');
        }
    } catch (error) {
        console.error('Register error:', error);
        showToast('Có lỗi xảy ra khi đăng ký', 'error');
    } finally {
        hideLoading();
    }
}

function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('show');
    }
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
}