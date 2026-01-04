document.addEventListener('DOMContentLoaded', () => {
    const loginCard = document.querySelector('.login-card');
    const loginForm = document.getElementById('loginForm');
    const togglePass = document.getElementById('togglePassword');
    const passInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');
    const btnLoader = document.getElementById('btnLoader');
    const btnText = document.querySelector('.btn-text');

    // 1. Reveal Animation on Load
    setTimeout(() => {
        loginCard.classList.add('reveal');
    }, 100);

    // 2. Toggle Password Visibility
    togglePass.addEventListener('click', () => {
        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passInput.setAttribute('type', type);
        togglePass.classList.toggle('bx-show');
        togglePass.classList.toggle('bx-hide');
    });

    // 3. Form Validation & Submission
    loginForm.addEventListener('submit', async (e) => {

        loginForm.addEventListener('submit', () => {
            const btnBtn = document.getElementById('loginBtn');
            const btnText = document.querySelector('.btn-text');
            const btnLoader = document.getElementById('btnLoader');

            // Menampilkan loader saat tombol ditekan
            btnBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'block';
        });
    });

    function triggerError() {
        loginCard.classList.add('shake');
        setTimeout(() => loginCard.classList.remove('shake'), 4000);
    }

    function setLoading(isLoading) {
        if (isLoading) {
            loginBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'block';
        } else {
            loginBtn.disabled = false;
            btnText.style.display = 'block';
            btnLoader.style.display = 'none';
        }
    }

    function showNotification(type, message) {
        const area = document.getElementById('notification-area');
        const notif = document.createElement('div');
        notif.className = `notif ${type}`;
        
        const icon = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
        const color = type === 'success' ? 'var(--primary)' : '#ff3e1d';

        notif.innerHTML = `
            <i class='bx ${icon}' style="color: ${color}; font-size: 20px;"></i>
            <span>${message}</span>
        `;

        area.appendChild(notif);

        setTimeout(() => {
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 300);
        }, 3000);
    }
});