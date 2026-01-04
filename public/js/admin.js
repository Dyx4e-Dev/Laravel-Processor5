document.addEventListener('DOMContentLoaded', () => {
    // 1. Loading Screen
    const loader = document.getElementById('loader');
    if (loader) { // Cek apakah elemen ada
        setTimeout(() => {
            loader.style.display = 'none';
            revealElements();
        }, 1500);
    }

    // 2. Reveal Animation
    function revealElements() {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('active');
            }, index * 200);
        });
        
        // Trigger Progress Ring hanya jika elemennya ada
        if (document.querySelector('.circular-progress')) {
            animateProgress(78);
        }
    }

    // 3. Chart.js Initialization (Bungkus dengan IF)
    const chartElement = document.getElementById('revenueChart');
    if (chartElement) { // Hanya jalankan jika elemen ditemukan
        const ctx = chartElement.getContext('2d');
        const revenueChart = new Chart(ctx, {
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
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#666' } },
                    x: { grid: { display: false }, ticks: { color: '#666' } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // 4. Circular Progress Animation (Bungkus dengan IF)
    function animateProgress(target) {
        const progressEl = document.querySelector('.circular-progress');
        const valueEl = document.getElementById('progress-value');

        if (progressEl && valueEl) { // Pastikan keduanya ada
            let current = 0;
            const speed = 20;
            const interval = setInterval(() => {
                current++;
                valueEl.textContent = `${current}%`;
                progressEl.style.background = `conic-gradient(var(--primary) ${current * 3.6}deg, var(--glass) 0deg)`;
                
                if (current >= target) clearInterval(interval);
            }, speed);
        }
    }

    // Fungsi Modal - Tetap sama
    window.toggleModal = function() {
        const modal = document.getElementById('addModal');
        if(modal) modal.style.display = modal.style.display === 'none' ? 'grid' : 'none';
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        if (event.target == addModal) toggleModal();
        if (event.target == editModal) closeEditModal();
    }
});

// Pastikan ini berada di dalam DOMContentLoaded atau di luar agar bisa diakses HTML
window.openEditModal = function(data) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    
    if(modal && form) {
        // Set Action URL: Pastikan path ini sesuai dengan route Laravel Anda
        form.action = `/admin/team/${data.id}`;
        
        // Isi field input (pastikan ID di HTML sama persis)
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_role').value = data.role;
        document.getElementById('edit_email').value = data.email;
        document.getElementById('edit_alamat').value = data.alamat;

        modal.style.display = 'grid'; // Tampilkan modal
    } else {
        console.error("Modal atau Form Edit tidak ditemukan di DOM");
    }
};

window.closeEditModal = function() {
    const modal = document.getElementById('editModal');
    if(modal) modal.style.display = 'none';
};

// 5. Notification & 6. Navigation - Tetap sama
// Gunakan window.notify agar bisa dipanggil dari mana saja
window.notify = function(type, message) {
    const area = document.getElementById('notification-area');
    if(!area) return;
    const toast = document.createElement('div');
    toast.className = `notif ${type}`;
    let icon = type === 'success' ? 'bx-check-circle' : 'bx-info-circle';
    toast.innerHTML = `<div style="display:flex; align-items:center; gap:12px;"><i class='bx ${icon}' style="color: var(--primary); font-size:20px;"></i><span>${message}</span></div>`;
    area.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}