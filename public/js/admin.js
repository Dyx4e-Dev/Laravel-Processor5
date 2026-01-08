// ============================================
// ADMIN DASHBOARD OPTIMIZED
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    // 1. Loading Screen & Element Reveal
    const loader = document.getElementById('loader');
    if (loader) {
        setTimeout(() => {
            loader.style.display = 'none';
            revealElements();
        }, 1500);
    } else {
        revealElements(); // In case loader doesn't exist
    }

    // 2. Reveal Animation (Staggered)
    function revealElements() {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('active');
            }, index * 200);
        });

        // Trigger Progress Ring
        const progressRing = document.querySelector('.circular-progress');
        if (progressRing) {
            animateProgress(78);
        }

        // Initialize Charts
        initializeCharts();
    }

    // 3. Circular Progress Animation
    function animateProgress(target) {
        const progressEl = document.querySelector('.circular-progress');
        const valueEl = document.getElementById('progress-value');

        if (!progressEl || !valueEl) return;

        let current = 0;
        const speed = 20;
        const interval = setInterval(() => {
            current++;
            valueEl.textContent = `${current}%`;
            progressEl.style.background = `conic-gradient(var(--primary) ${current * 3.6}deg, var(--glass) 0deg)`;

            if (current >= target) clearInterval(interval);
        }, speed);
    }

    // 4. Initialize Charts (Consolidated - only runs once)
    function initializeCharts() {
        // Revenue Chart
        const revenueChartEl = document.getElementById('revenueChart');
        if (revenueChartEl && typeof Chart !== 'undefined') {
            const ctx = revenueChartEl.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [1200, 1900, 1500, 2500, 2200, 3000],
                        backgroundColor: 'rgba(44, 232, 185, 0.6)',
                        borderColor: '#2ce8b9',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: '#666' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#666' }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Quiz Chart (Line Chart)
        const quizChartEl = document.getElementById('quizChart');
        if (quizChartEl && typeof Chart !== 'undefined') {
            const ctx = quizChartEl.getContext('2d');

            // Get data from data attributes if available
            const labels = quizChartEl.dataset.labels ? JSON.parse(quizChartEl.dataset.labels) : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const data = quizChartEl.dataset.data ? JSON.parse(quizChartEl.dataset.data) : [12, 19, 15, 25, 22, 30, 45];

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(44, 232, 185, 0.4)');
            gradient.addColorStop(1, 'rgba(44, 232, 185, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Peserta',
                        data: data,
                        borderColor: '#2ce8b9',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#2ce8b9'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            ticks: { color: '#888' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#888' }
                        }
                    }
                }
            });
        }
    }

    // 5. Modal Functions
    window.toggleModal = function() {
        const modal = document.getElementById('addModal');
        if (modal) {
            modal.style.display = modal.style.display === 'none' ? 'grid' : 'none';
        }
    };

    window.openEditModal = function(data) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');

        if (!modal || !form) {
            console.error('Modal atau Form Edit tidak ditemukan di DOM');
            return;
        }

        form.action = `/admin/team/${data.id}`;

        // Set field values with null check
        if (document.getElementById('edit_name')) {
            document.getElementById('edit_name').value = data.name || '';
        }
        if (document.getElementById('edit_role')) {
            document.getElementById('edit_role').value = data.role || '';
        }
        if (document.getElementById('edit_email')) {
            document.getElementById('edit_email').value = data.email || '';
        }
        if (document.getElementById('edit_alamat')) {
            document.getElementById('edit_alamat').value = data.alamat || '';
        }

        modal.style.display = 'grid';
    };

    window.closeEditModal = function() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    // 6. Modal Backdrop Clicks - Event Delegation
    document.addEventListener('click', (event) => {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');

        if (event.target === addModal) {
            window.toggleModal();
        }
        if (event.target === editModal) {
            window.closeEditModal();
        }
    }, false);

    // 7. Notification System
    window.notify = function(type, message) {
        const area = document.getElementById('notification-area');
        if (!area) return;

        const toast = document.createElement('div');
        toast.className = `notif ${type}`;
        const icon = type === 'success' ? 'bx-check-circle' : 'bx-info-circle';

        toast.innerHTML = `<div style="display:flex; align-items:center; gap:12px;">
            <i class='bx ${icon}' style="color: var(--primary); font-size:20px;"></i>
            <span>${message}</span>
        </div>`;

        area.appendChild(toast);

        // Auto-remove after 4 seconds
        setTimeout(() => toast.remove(), 4000);
    };

    // 8. Keyboard Shortcuts (Optional Enhancement)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.closeEditModal();
            const addModal = document.getElementById('addModal');
            if (addModal) addModal.style.display = 'none';
        }
    });
});