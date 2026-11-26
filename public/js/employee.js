// Modal Add User
function openUserModal() { document.getElementById('userModal').style.display = 'flex'; }
function closeUserModal() { document.getElementById('userModal').style.display = 'none'; }

// Modal Edit User
function openEditUserModal(button) {
    const modal = document.getElementById('editUserModal');
    const form = document.getElementById('editUserForm');
    form.action = button.dataset.updateUrl;
    document.getElementById('edit_update_url').value = button.dataset.updateUrl;
    document.getElementById('edit_name').value = button.dataset.userName;
    document.getElementById('edit_email').value = button.dataset.userEmail;
    document.getElementById('edit_role_id').value = button.dataset.userRole;
    document.getElementById('edit_password').value = '';
    modal.style.display = 'flex';
}
function closeEditUserModal() { document.getElementById('editUserModal').style.display = 'none'; }

// Modal Delete Confirmation
let pendingDeleteForm = null;
function openDeleteModal(form, message) {
    pendingDeleteForm = form;
    document.getElementById('deleteConfirmMessage').textContent = message;
    document.getElementById('deleteConfirmModal').style.display = 'flex';
}
function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
    pendingDeleteForm = null;
}
function confirmDelete() {
    if (pendingDeleteForm) {
        const form = pendingDeleteForm;
        closeDeleteModal();
        HTMLFormElement.prototype.submit.call(form);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Tutup modal saat klik luar
    ['editUserModal', 'userModal', 'deleteConfirmModal', 'roleModal', 'editRoleModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal) modal.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });
    });

    // Handle delete form submit
    document.querySelectorAll('.delete-form').forEach(form => {
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', e => {
            e.preventDefault();
            const isUser = form.action.includes('/users/');
            const msg = isUser ? 'Apakah Anda yakin ingin menghapus akun pengguna ini?' : 'Apakah Anda yakin ingin menghapus role ini?';
            openDeleteModal(form, msg);
        });
    });

    // Auto hide flash notification
    const notification = document.getElementById('flashNotification');
    if (notification) {
        setTimeout(() => {
            notification.style.transition = 'opacity 0.5s ease';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 4000);
    }

    // Table features (Pagination & Search)
    document.querySelectorAll('[data-table-container]').forEach(container => {
        const table = container.querySelector('.feature-table');
        const tbody = table.querySelector('tbody');
        const selectAll = container.querySelector('.select-all');
        const deleteButton = container.querySelector('.delete-selected');
        const pageLength = container.querySelector('.page-length');
        const info = container.querySelector('.table-info');
        const pagination = container.querySelector('.pagination');
        const searchInputs = container.querySelectorAll('.search-row input');
        const originalRows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.querySelector('.row-checkbox'));
        let filteredRows = [...originalRows];
        let currentPage = 1;

        function updateDeleteButton() {
            if (deleteButton) deleteButton.hidden = tbody.querySelectorAll('.row-checkbox:checked').length === 0;
        }

        function filterData() {
            filteredRows = originalRows.filter(row => {
                const cells = row.querySelectorAll('td');
                return Array.from(searchInputs).every((input, index) => {
                    const keyword = input.value.toLowerCase().trim();
                    const cell = cells[index + 1];
                    return !cell || cell.textContent.toLowerCase().includes(keyword);
                });
            });
            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            const perPage = parseInt(pageLength.value);
            const total = filteredRows.length;
            const totalPages = Math.ceil(total / perPage);
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, total);
            const visibleRows = filteredRows.slice(start, end);

            originalRows.forEach(row => row.style.display = 'none');
            visibleRows.forEach(row => row.style.display = '');

            info.textContent = total === 0 ? 'Showing 0 to 0 of 0 entries' : `Showing ${start + 1} to ${end} of ${total} entries`;

            if (selectAll) {
                const visibleCheckboxes = visibleRows.map(row => row.querySelector('.row-checkbox')).filter(Boolean);
                selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
                selectAll.indeterminate = visibleCheckboxes.some(cb => cb.checked) && !selectAll.checked;
            }

            pagination.innerHTML = '';
            function createBtn(label, page, disabled, active) {
                const btn = document.createElement('button');
                btn.type = 'button'; btn.textContent = label; btn.disabled = disabled;
                if (active) btn.classList.add('active');
                btn.addEventListener('click', () => { currentPage = page; renderTable(); });
                pagination.appendChild(btn);
            }

            createBtn('«', 1, currentPage === 1 || totalPages === 0, false);
            createBtn('‹', Math.max(1, currentPage - 1), currentPage === 1 || totalPages === 0, false);
            for (let i = 1; i <= totalPages; i++) createBtn(i, i, false, currentPage === i);
            createBtn('›', Math.min(totalPages, currentPage + 1), currentPage === totalPages || totalPages === 0, false);
            createBtn('»', totalPages, currentPage === totalPages || totalPages === 0, false);
            updateDeleteButton();
        }

        searchInputs.forEach(input => input.addEventListener('input', filterData));
        pageLength.addEventListener('change', () => { currentPage = 1; renderTable(); });

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                const perPage = parseInt(pageLength.value);
                const start = (currentPage - 1) * perPage;
                filteredRows.slice(start, start + perPage).forEach(row => {
                    const cb = row.querySelector('.row-checkbox');
                    if (cb) cb.checked = selectAll.checked;
                });
                updateDeleteButton();
                renderTable();
            });
        }

        tbody.addEventListener('change', e => {
            if (e.target.classList.contains('row-checkbox')) { updateDeleteButton(); renderTable(); }
        });

        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const checked = tbody.querySelectorAll('.row-checkbox:checked');
                if (checked.length === 0) return;
                const isUser = container.querySelector('h2').textContent.trim() === 'User List';
                const form = document.getElementById(isUser ? 'deleteMultipleUsers' : 'deleteMultipleRoles');
                const msg = `Apakah Anda yakin ingin menghapus ${checked.length} data terpilih?`;
                form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                    form.appendChild(input);
                });
                openDeleteModal(form, msg);
            });
        }
        renderTable();
    });
});

// Modal Add Role & Edit Role
function openRoleModal() { document.getElementById('roleModal').style.display = 'flex'; }
function closeRoleModal() { document.getElementById('roleModal').style.display = 'none'; }

function openEditRoleModal(button) {
    const modal = document.getElementById('editRoleModal');
    const form = document.getElementById('editRoleForm');
    form.action = button.dataset.updateUrl;
    document.getElementById('editRoleName').value = button.dataset.roleName;
    document.getElementById('editRoleDescription').value = button.dataset.roleDescription || '';
    const status = button.dataset.roleStatus === 'Active';
    document.getElementById('editRoleStatus').checked = status;
    document.getElementById('editRoleStatusText').textContent = status ? 'Active' : 'Inactive';

    const permissions = JSON.parse(button.dataset.rolePermissions || '{}');
    document.querySelectorAll('.edit-permission-checkbox').forEach(cb => {
        cb.checked = !!permissions[cb.dataset.permission];
    });
    updateEditSelectAll();
    modal.style.display = 'flex';
}
function closeEditRoleModal() { document.getElementById('editRoleModal').style.display = 'none'; }

const editRoleStatus = document.getElementById('editRoleStatus');
if (editRoleStatus) {
    editRoleStatus.addEventListener('change', function() {
        document.getElementById('editRoleStatusText').textContent = this.checked ? 'Active' : 'Inactive';
    });
}

function updateEditSelectAll() {
    const cbs = document.querySelectorAll('.edit-permission-checkbox');
    const all = document.getElementById('editSelectAllPermissions');
    if (all) all.checked = cbs.length > 0 && [...cbs].every(i => i.checked);
}

const editSelectAll = document.getElementById('editSelectAllPermissions');
if (editSelectAll) {
    editSelectAll.addEventListener('change', function() {
        document.querySelectorAll('.edit-permission-checkbox').forEach(cb => cb.checked = this.checked);
    });
}
document.querySelectorAll('.edit-permission-checkbox').forEach(cb => cb.addEventListener('change', updateEditSelectAll));

const selectAllPermissions = document.getElementById('selectAllPermissions');
if (selectAllPermissions) {
    const cbs = document.querySelectorAll('.permission-checkbox');
    selectAllPermissions.addEventListener('change', function() {
        cbs.forEach(cb => cb.checked = this.checked);
    });
    cbs.forEach(cb => {
        cb.addEventListener('change', () => { selectAllPermissions.checked = [...cbs].every(i => i.checked); });
    });
}