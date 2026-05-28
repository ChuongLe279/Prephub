// điều khiển dashboard quản trị
let allTests = [];
let usersState = { page: 1, limit: 10, total: 0, search: '' };
let attemptsState = { page: 1, limit: 10, total: 0 };
let transactionsState = { page: 1, limit: 10, total: 0 };
let loadedSections = new Set();
let revenueChartInstance = null;

// định dạng tiền tệ sang VND
function formatVND(value) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
}

// định dạng ngày tháng sang DD/MM/YYYY
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// định dạng giây sang MM:SS
function formatTimeSpent(seconds) {
    if (!seconds) return '00:00';
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

// tải dữ liệu khi tab được kích hoạt
async function loadSectionData(section) {
    if (loadedSections.has(section)) return;

    if (section === 'overview') {
        await loadOverviewStats();
    } else if (section === 'tests') {
        const hasForm = document.getElementById('questions-container') !== null;
        if (!hasForm) {
            await loadTestsList();
        }
    } else if (section === 'users') {
        await loadUsersList(1);
    } else if (section === 'attempts') {
        await loadAttemptsList(1);
    } else if (section === 'revenue') {
        await loadRevenueData();
        await loadTransactionsList(1);
    }

    loadedSections.add(section);
}

// tải thống kê tổng quan
async function loadOverviewStats() {
    try {
        const response = await fetch('/api/admin/stats');
        const result = await response.json();
        if (result.success) {
            const data = result.data;
            document.getElementById('stat-total-users').textContent = data.total_users;
            document.getElementById('stat-total-tests').textContent = data.total_tests;
            document.getElementById('stat-total-revenue').textContent = formatVND(data.total_revenue);
            document.getElementById('stat-total-purchased').textContent = data.total_purchased_users;
        }
    } catch (error) {
        console.error('error loading stats:', error);
    }
}

// tải danh sách đề thi
async function loadTestsList() {
    try {
        const response = await fetch('/api/tests');
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data && Array.isArray(result.data)) {
            allTests = result.data;
            filterTests();
        }
    } catch (error) {
        console.error('error loading tests:', error);
    }
}

// lọc danh sách đề thi tại client
function filterTests() {
    const searchInput = document.getElementById('search-tests');
    const premiumFilter = document.getElementById('filter-tests-premium');
    const statusFilter = document.getElementById('filter-tests-status');
    if (!searchInput) return;

    const searchText = searchInput.value.toLowerCase().trim();
    const premiumValue = premiumFilter.value;
    const statusValue = statusFilter.value;

    const filtered = allTests.filter(test => {
        const titleMatch = test.title.toLowerCase().includes(searchText);
        
        let premiumMatch = true;
        if (premiumValue === 'Premium') {
            premiumMatch = test.is_premium === true || parseInt(test.is_premium) === 1;
        } else if (premiumValue === 'Thường') {
            premiumMatch = test.is_premium === false || parseInt(test.is_premium) === 0;
        }

        let statusMatch = true;
        if (statusValue === 'Hoạt động') {
            statusMatch = parseInt(test.is_active) === 1;
        } else if (statusValue === 'Tạm ẩn') {
            statusMatch = parseInt(test.is_active) === 0;
        }

        return titleMatch && premiumMatch && statusMatch;
    });

    renderTestsTable(filtered);
}

// hiển thị danh sách đề thi
function renderTestsTable(tests) {
    const tbody = document.getElementById('testTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    if (tests.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Không tìm thấy bài thi nào</td></tr>';
        return;
    }

    tests.forEach(test => {
        const isPremium = test.is_premium === true || parseInt(test.is_premium) === 1;
        const isActive = parseInt(test.is_active) === 1;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${test.title}</strong></td>
            <td><span class="badge ${isPremium ? 'premium' : 'standard'}">${isPremium ? 'Premium' : 'Thường'}</span></td>
            <td><span class="badge ${isActive ? 'active' : 'inactive'}">${isActive ? 'Hoạt động' : 'Tạm ẩn'}</span></td>
            <td>${formatDate(test.created_at)}</td>
            <td style="text-align: right;">
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <a href="admin.php?section=tests&action=edit&test_id=${test.uuid}" class="btn-primary" style="padding: 6px 12px; font-size: 12px;">
                        <i class="bx bx-edit-alt"></i> Câu hỏi
                    </a>
                    <button class="btn-primary edit-info-btn" style="padding: 6px 12px; font-size: 12px; background-color: var(--warning-color);" 
                            data-uuid="${test.uuid}" data-title="${test.title}" data-premium="${isPremium ? '1' : '0'}" data-active="${isActive ? '1' : '0'}">
                        Thông tin
                    </button>
                    <button class="btn-danger delete-test-btn" style="padding: 6px 12px; font-size: 12px;" data-uuid="${test.uuid}">
                        Xóa
                    </button>
                </div>
            </td>
        `;

        // kích đúp để biên soạn câu hỏi
        row.addEventListener('dblclick', () => {
            window.location.href = `admin.php?section=tests&action=edit&test_id=${test.uuid}`;
        });

        tbody.appendChild(row);
    });

    // gắn sự kiện cho các nút hành động
    document.querySelectorAll('.edit-info-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const data = e.target.dataset;
            openEditModal(data.uuid, data.title, data.premium === '1', data.active === '1');
        });
    });

    document.querySelectorAll('.delete-test-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const uuid = e.target.dataset.uuid;
            deleteTest(uuid);
        });
    });
}

// mở modal chỉnh sửa nhanh
function openEditModal(uuid, title, isPremium, isActive) {
    const modal = document.getElementById('editModal');
    document.getElementById('edit_id').value = uuid;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_premium').checked = isPremium;
    document.getElementById('edit_active').checked = isActive;
    modal.classList.add('show');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.classList.remove('show');
}

// xóa đề thi bằng uuid
async function deleteTest(uuid) {
    if (confirm("Bạn có chắc chắn muốn xóa bài thi này không? Toàn bộ câu hỏi liên quan sẽ bị xóa vĩnh viễn.")) {
        try {
            const response = await fetch(`/api/tests/${uuid}`, { method: 'DELETE' });
            const result = await response.json();
            if (result.success) {
                alert("Đã xóa bài thi thành công");
                await loadTestsList();
            } else {
                alert("Lỗi: " + result.message);
            }
        } catch (error) {
            console.error('error deleting test:', error);
            alert("Lỗi khi gửi yêu cầu xóa bài thi");
        }
    }
}

// tải danh sách người dùng phân trang
async function loadUsersList(page) {
    usersState.page = page;
    const search = usersState.search;
    try {
        const response = await fetch(`/api/admin/users?page=${page}&limit=${usersState.limit}&q=${encodeURIComponent(search)}`);
        const result = await response.json();
        
        if (result.success) {
            renderUsersTable(result.data);
            
            // cập nhật thống kê người dùng
            const stats = result.stats;
            document.getElementById('user-stat-total').textContent = stats.total_users;
            document.getElementById('user-stat-new').textContent = stats.new_users_month;
            document.getElementById('user-stat-inactive').textContent = stats.inactive_users_7d;

            usersState.total = result.pagination.total;
            renderPagination('users-pagination', result.pagination, loadUsersList);
        }
    } catch (error) {
        console.error('error loading users:', error);
    }
}

// hiển thị danh sách người dùng
function renderUsersTable(users) {
    const tbody = document.getElementById('userTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Không tìm thấy người dùng nào</td></tr>';
        return;
    }

    users.forEach(user => {
        const fullName = `${user.first_name} ${user.last_name}`;
        const isPremium = parseInt(user.is_premium) === 1;
        const isBanned = parseInt(user.is_banned) === 1;
        
        // định dạng thông tin gói
        let planBadge = '<span class="badge standard">Thường</span>';
        if (isPremium) {
            planBadge = `<span class="badge premium">${user.premium_plan || 'Premium'}</span>`;
        }

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="align-center">
                    <div style="width: 32px; height: 32px; background-color: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; color: var(--primary-color);">
                        ${user.first_name[0] || 'U'}
                    </div>
                    <strong>${fullName}</strong>
                </div>
            </td>
            <td>${user.email}</td>
            <td>
                <select class="action-select role-select" data-id="${user.id}">
                    <option value="user" ${user.role === 'user' ? 'selected' : ''}>Học viên</option>
                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Quản trị viên</option>
                </select>
            </td>
            <td>${planBadge}</td>
            <td>${formatDate(user.created_at)}</td>
            <td>
                <div class="align-center">
                    <span class="badge ${isBanned ? 'failed' : 'success'}">${isBanned ? 'Bị khóa' : 'Hoạt động'}</span>
                    <button class="btn-primary ban-btn" style="padding: 4px 8px; font-size: 11px; background-color: ${isBanned ? 'var(--success-color)' : 'var(--danger-color)'};" data-id="${user.id}" data-banned="${isBanned ? '0' : '1'}">
                        ${isBanned ? 'Bỏ khóa' : 'Khóa'}
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

    // gắn sự kiện thay đổi trực tuyến
    document.querySelectorAll('.role-select').forEach(select => {
        select.addEventListener('change', async (e) => {
            const userId = e.target.dataset.id;
            const newRole = e.target.value;
            await updateUserField(userId, { role: newRole });
        });
    });

    document.querySelectorAll('.ban-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const userId = e.target.dataset.id;
            const newBanned = parseInt(e.target.dataset.banned);
            const msg = newBanned === 1 ? "Bạn có chắc chắn muốn khóa tài khoản này không?" : "Bạn muốn bỏ khóa tài khoản này?";
            if (confirm(msg)) {
                await updateUserField(userId, { is_banned: newBanned });
            }
        });
    });
}

// cập nhật vai trò hoặc trạng thái khóa qua API
async function updateUserField(userId, data) {
    try {
        const response = await fetch(`/api/admin/users/${userId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            await loadUsersList(usersState.page);
        } else {
            alert("Lỗi: " + result.message);
        }
    } catch (error) {
        console.error('error updating user status:', error);
    }
}

// tải danh sách lượt làm bài phân trang
async function loadAttemptsList(page) {
    attemptsState.page = page;
    try {
        const response = await fetch(`/api/admin/attempts?page=${page}&limit=${attemptsState.limit}`);
        const result = await response.json();
        if (result.success) {
            renderAttemptsTable(result.data);
            attemptsState.total = result.pagination.total;
            renderPagination('attempts-pagination', result.pagination, loadAttemptsList);
        }
    } catch (error) {
        console.error('error loading attempts:', error);
    }
}

// hiển thị danh sách lượt làm bài
function renderAttemptsTable(attempts) {
    const tbody = document.getElementById('attemptTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    if (attempts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Không tìm thấy bài thi thử nào</td></tr>';
        return;
    }

    attempts.forEach(attempt => {
        const isPremium = parseInt(attempt.is_premium) === 1 || attempt.premium_plan !== null;
        const correctText = `L: ${attempt.listening_correct} | R: ${attempt.reading_correct}`;
        const scoreText = `L: ${attempt.listening_score} | R: ${attempt.reading_score} | T: ${attempt.total_score}`;
        
        // tính toán tỷ lệ tiến trình làm bài
        const attempted = parseInt(attempt.user_tests_attempted) || 0;
        const total = parseInt(attempt.total_active_tests) || 1;
        const percentage = Math.min(100, Math.round((attempted / total) * 100));

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div>
                    <strong>${attempt.first_name} ${attempt.last_name}</strong>
                    <div style="font-size: 11px; color: var(--text-muted);">${attempt.email}</div>
                    ${isPremium ? '<span class="badge premium" style="font-size: 9px; padding: 2px 6px;">Pro</span>' : ''}
                </div>
            </td>
            <td><strong>${attempt.title}</strong></td>
            <td>${correctText}</td>
            <td><strong style="color: var(--accent-color);">${attempt.total_score}</strong> <span style="font-size: 11px; color: var(--text-muted);">(${scoreText})</span></td>
            <td>${formatTimeSpent(attempt.time_spent)}</td>
            <td>
                <div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: ${percentage}%;"></div>
                    </div>
                    <span class="progress-text">${attempted}/${total} đề (${percentage}%)</span>
                </div>
            </td>
            <td>${formatDate(attempt.created_at)}</td>
        `;
        tbody.appendChild(row);
    });
}

// tải tóm tắt doanh thu và dữ liệu biểu đồ
async function loadRevenueData() {
    try {
        const response = await fetch('/api/admin/revenue');
        const result = await response.json();
        if (result.success) {
            const data = result.data;
            document.getElementById('revenue-stat-month').textContent = formatVND(data.current_month);
            document.getElementById('revenue-stat-alltime').textContent = formatVND(data.all_time);

            // đợi thư viện chart.js tải xong
            if (window.Chart) {
                renderRevenueChart(data.chart);
            } else {
                setTimeout(() => {
                    if (window.Chart) renderRevenueChart(data.chart);
                }, 1000);
            }
        }
    } catch (error) {
        console.error('error loading revenue data:', error);
    }
}

// vẽ biểu đồ doanh thu
function renderRevenueChart(chartData) {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    if (revenueChartInstance) {
        revenueChartInstance.destroy();
    }

    const labels = chartData.map(item => item.month);
    const totals = chartData.map(item => parseInt(item.total));

    revenueChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: totals,
                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                borderColor: 'rgb(37, 99, 235)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatVND(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return formatVND(context.raw);
                        }
                    }
                }
            }
        }
    });
}

// tải lịch sử giao dịch phân trang
async function loadTransactionsList(page) {
    transactionsState.page = page;
    try {
        const response = await fetch(`/api/admin/transactions?page=${page}&limit=${transactionsState.limit}`);
        const result = await response.json();
        if (result.success) {
            renderTransactionsTable(result.data);
            transactionsState.total = result.pagination.total;
            renderPagination('transactions-pagination', result.pagination, loadTransactionsList);
        }
    } catch (error) {
        console.error('error loading transactions:', error);
    }
}

// hiển thị danh sách giao dịch
function renderTransactionsTable(transactions) {
    const tbody = document.getElementById('transactionTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    if (transactions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Không có giao dịch nào</td></tr>';
        return;
    }

    transactions.forEach(tx => {
        const fullName = `${tx.first_name} ${tx.last_name}`;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><code>${tx.tx_id}</code></td>
            <td>
                <div>
                    <strong>${fullName}</strong>
                    <div style="font-size: 11px; color: var(--text-muted);">${tx.email}</div>
                </div>
            </td>
            <td><span class="badge info">${tx.plan_name}</span></td>
            <td><strong style="color: var(--success-color);">${formatVND(tx.price)}</strong></td>
            <td>Thanh toán theo ${tx.period}</td>
            <td>${formatDate(tx.created_at)}</td>
        `;
        tbody.appendChild(row);
    });
}

// hiển thị cấu phần phân trang
function renderPagination(containerId, pagination, onPageChange) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const { page, limit, total } = pagination;
    const totalPages = Math.ceil(total / limit);

    if (totalPages <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = `
        <div class="pagination-info">
            Hiển thị từ ${(page - 1) * limit + 1} đến ${Math.min(page * limit, total)} trong tổng số ${total} bản ghi
        </div>
        <div class="pagination-buttons">
            <button class="page-btn" ${page === 1 ? 'disabled' : ''} data-page="${page - 1}">
                <i class="bx bx-chevron-left"></i>
            </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= page - 2 && i <= page + 2)) {
            html += `<button class="page-btn ${i === page ? 'active' : ''}" data-page="${i}">${i}</button>`;
        } else if (i === page - 3 || i === page + 3) {
            html += `<span style="padding: 0 4px; display: inline-flex; align-items: center; color: var(--text-muted);">...</span>`;
        }
    }

    html += `
            <button class="page-btn" ${page === totalPages ? 'disabled' : ''} data-page="${page + 1}">
                <i class="bx bx-chevron-right"></i>
            </button>
        </div>
    `;

    container.innerHTML = html;

    // gắn sự kiện chuyển trang
    container.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const btnTarget = e.currentTarget;
            if (btnTarget.hasAttribute('disabled')) return;
            const targetPage = parseInt(btnTarget.dataset.page);
            onPageChange(targetPage);
        });
    });
}

// logic chuyển tab
function setupTabSwitching() {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const sections = document.querySelectorAll('.section-content');

    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            const sectionName = item.dataset.section;
            if (!sectionName) return;

            // không chuyển trang nếu đang soạn đề để tránh mất dữ liệu
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            if (action && sectionName !== 'tests') {
                if (!confirm("Các thay đổi chưa lưu trên đề thi sẽ bị mất, bạn có chắc chắn muốn chuyển trang?")) {
                    return;
                }
            }

            // cập nhật đường dẫn URL
            history.pushState(null, '', `admin.php?section=${sectionName}`);

            // chuyển đổi trạng thái active
            sidebarItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            sections.forEach(s => s.classList.remove('active'));
            const targetSection = document.getElementById(`section-${sectionName}`);
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // tải dữ liệu cho tab
            loadSectionData(sectionName);
        });
    });
}

// khởi tạo trạng thái tài liệu
document.addEventListener("DOMContentLoaded", () => {
    setupTabSwitching();

    // phân tích tab mặc định từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const initialSection = urlParams.get('section') || 'overview';
    
    // tải dữ liệu cho tab mặc định
    loadSectionData(initialSection);

    // thiết lập đóng modal nếu đang ở danh sách đề thi
    const editForm = document.getElementById('editForm');
    if (editForm) {
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelModalBtn = document.getElementById('cancelModalBtn');
        const modal = document.getElementById('editModal');

        const closeModal = () => closeEditModal();
        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
        
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        }

        // gửi form chỉnh sửa đề thi
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const uuid = document.getElementById('edit_id').value;
            const title = document.getElementById('edit_title').value;
            const isPremium = document.getElementById('edit_premium').checked ? 1 : 0;
            const isActive = document.getElementById('edit_active').checked ? 1 : 0;

            try {
                const response = await fetch(`/api/tests/${uuid}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        title: title,
                        is_premium: isPremium,
                        is_active: isActive
                    })
                });
                const result = await response.json();
                if (result.success) {
                    alert("Đã lưu thông tin đề thi thành công");
                    closeEditModal();
                    await loadTestsList();
                } else {
                    alert("Lỗi: " + result.message);
                }
            } catch (error) {
                console.error('error updating test metadata:', error);
            }
        });
    }

    // tìm kiếm và lọc đề thi
    const searchTests = document.getElementById('search-tests');
    if (searchTests) {
        searchTests.addEventListener('input', filterTests);
        document.getElementById('filter-tests-premium').addEventListener('change', filterTests);
        document.getElementById('filter-tests-status').addEventListener('change', filterTests);
    }

    // tìm kiếm người dùng có độ trễ
    const searchUsers = document.getElementById('search-users');
    if (searchUsers) {
        let debounceTimer;
        searchUsers.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                usersState.search = searchUsers.value.trim();
                loadUsersList(1);
            }, 300);
        });
    }
});
