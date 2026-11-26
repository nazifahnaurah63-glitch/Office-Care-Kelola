<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaning Task Management - Office Care Kelola</title>

    <link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cleaning-tasks.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="app">

        @include('partials.sidebar')

        <main class="main-content">

            @include('partials.topbar')

            {{-- NOTIFIKASI FLASH / TOAST SUKSES (SESUAI GAMBAR) --}}
            @if (session('success'))
                <div class="kelola-flash kelola-flash-success" id="flashNotification" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 10000; background: #dcfce7; color: #047857; border: 1px solid #86efac; padding: 16px 24px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <span style="font-size: 18px; font-weight: bold;">✓</span>
                    <span style="font-size: 14px; font-weight: 600;">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="kelola-flash kelola-flash-error" id="flashNotification" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 10000; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 16px 24px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <span style="font-size: 18px; font-weight: bold;">!</span>
                    <span style="font-size: 14px; font-weight: 600;">{{ session('error') }}</span>
                </div>
            @endif

            <div class="container">
                <header class="page-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                    <section class="kelola-page-heading">
            <div class="kelola-heading-icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 6h12M9 12h12M9 18h12"/>
                            <path d="M3 5h2v2H3zM3 11h2v2H3zM3 17h2v2H3z"/>
                        </svg>
                        </div>
                        <div>
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #262626;">Cleaning Tasks</h1>
                            <p style="margin: 4px 0 0 0; color: #595959; font-size: 14px;">Kelola daftar tugas kebersihan</p>
                        </div>
                    </div>
                </header>

                <section class="stats-grid">
                    <article class="stat-card card-total">
                        <div class="stat-icon icon-total">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 6h12M9 12h12M9 18h12"/>
                                <path d="M3 5h2v2H3zM3 11h2v2H3zM3 17h2v2H3z"/>
                            </svg>
                        </div>
                        <div>
                            <h2>{{ $totalTasks }}</h2>
                            <p>Total Tugas</p>
                        </div>
                    </article>

                    <article class="stat-card card-completed">
                        <div class="stat-icon icon-completed">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <h2>{{ $completedTasks }}</h2>
                            <p>Tugas Selesai</p>
                        </div>
                    </article>

                    <article class="stat-card card-pending">
                        <div class="stat-icon icon-pending">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div>
                            <h2>{{ $pendingTasks }}</h2>
                            <p>Tugas Pending</p>
                        </div>
                    </article>

                    <article class="stat-card card-overdue">
                        <div class="stat-icon icon-overdue">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.3 3.9 2.5 17.5a2 2 0 0 0 1.7 3h15.6a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                  <path d="M12 9v4"/>
                  <path d="M12 17h.01"/>
                 </svg>
                        </div>
                        <div>
                            <h2>{{ $overdueTasks }}</h2>
                            <p>Terlambat</p>
                        </div>
                    </article>
                </section>
        
                <section class="table-card">
                    <div class="table-toolbar">
                        <div class="toolbar-actions">
                            <button type="button" class="btn-add-task" id="openCreateModalBtn">
                                <span>+</span> Tambah Task
                            </button>

                            <form id="bulkDeleteForm"
                                  action="{{ route('cleaning-tasks.bulk-destroy') }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus tugas yang dipilih?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        id="deleteSelectedBtn"
                                        class="btn-delete-task"
                                        hidden>
                                    <span></span> Delete
                                </button>
                            </form>
                        </div>

                        <span class="task-count" id="taskCount">
                            {{ $tasks->count() }} tugas
                        </span>
                    </div>

                    <div class="table-wrap">
                        <table class="cleaning-task-table">
                            <thead>
                                <tr>
                                    <th class="checkbox-column">
                                        <input type="checkbox" id="selectAllTasks" aria-label="Pilih semua tugas">
                                    </th>
                                    <th>
                                        <div class="column-heading">Tugas</div>
                                        <input type="search" class="column-search" id="searchTask" placeholder="Cari tugas...">
                                    </th>
                                    <th>
                                        <div class="column-heading">Area</div>
                                        <input type="search" class="column-search" id="searchArea" placeholder="Cari area...">
                                    </th>
                                    <th>
                                        <div class="column-heading">Prioritas</div>
                                        <input type="search" class="column-search" id="searchPriority" placeholder="Cari prioritas...">
                                    </th>
                                    <th>
                                        <div class="column-heading">Status</div>
                                        <input type="search" class="column-search" id="searchStatus" placeholder="Cari status...">
                                    </th>
                                    <th class="action-column">Action</th>
                                </tr>
                            </thead>

                            <tbody id="taskTableBody">
                                @forelse ($tasks as $task)
                                    <tr class="task-row"
                                        data-task="{{ strtolower($task->task_name) }}"
                                        data-area="{{ strtolower($task->area) }}"
                                        data-priority="{{ strtolower($task->priority ?? '') }}"
                                        data-status="{{ strtolower($task->status) }}">

                                        <td class="checkbox-column">
                                            <input type="checkbox"
                                                   class="task-checkbox"
                                                   name="task_ids[]"
                                                   value="{{ $task->id }}"
                                                   form="bulkDeleteForm"
                                                   aria-label="Pilih tugas {{ $task->task_name }}">
                                        </td>

                                        <td class="task-name">{{ $task->task_name }}</td>
                                        <td>{{ $task->area }}</td>

                                        <td>
                                            <span class="priority-badge priority-{{ $task->priority }}">
                                                {{ ucfirst($task->priority ?? '-') }}
                                            </span>
                                        </td>

                                        <td>
                                            @if ($task->status === 'completed')
                                                <span class="status-badge status-completed">Completed</span>
                                            @else
                                                <span class="status-badge status-pending">Pending</span>
                                            @endif
                                        </td>

                                       <td class="action-column">
                                            <div class="row-actions">
                                                {{-- Tombol Edit --}}
                                                <button type="button"
                                                        class="action-icon edit-task-btn"
                                                        title="Edit tugas"
                                                        aria-label="Edit tugas"
                                                        data-id="{{ $task->id }}"
                                                        data-name="{{ $task->task_name }}"
                                                        data-area="{{ $task->area }}"
                                                        data-priority="{{ $task->priority }}"
                                                        data-status="{{ $task->status }}">
                                                    <i class="fas fa-pen"></i>
                                                </button>

                                                {{-- Tombol Hapus Satuan (Memicu Modal Konfirmasi) --}}
                                                <form action="{{ route('cleaning-tasks.destroy', $task->id) }}"
                                                    method="POST"
                                                    class="single-delete-form"
                                                    style="display: inline; margin: 0;">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="action-icon delete-task-action"
                                                            title="Hapus tugas"
                                                            aria-label="Hapus tugas">
                                                         <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyTasks">
                                        <td colspan="6" class="empty">
                                            Belum ada tugas kebersihan. Klik Tambah Tugas untuk menambahkan tugas.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="noResults" hidden>
                                    <td colspan="6" class="empty">
                                        Tidak ada tugas yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <footer class="table-footer">
                        <p id="showingCount">Menampilkan {{ $tasks->count() }} tugas</p>
                        <div class="pagination" id="pagination"></div>
                    </footer>
                </section>
            </div>

            <footer class="page-footer" style="text-align: center; padding: 20px 0;">
                © {{ date('Y') }} Office Care Kelola. All rights reserved.
            </footer>
        </main>
    </div>

    <!-- MODAL CREATE (TAMBAH TUGAS) -->
    <div class="edit-task-overlay" id="createTaskModal" hidden>
        <div class="edit-task-modal" role="dialog" aria-modal="true" aria-labelledby="createTaskTitle">
            <div class="edit-task-header">
                <h2 id="createTaskTitle">Tambah Tugas Kebersihan</h2>
                <button type="button" class="close-edit-modal" id="closeCreateTask" aria-label="Tutup modal">&times;</button>
            </div>

            <form action="{{ route('cleaning-tasks.store') }}" method="POST">
                @csrf
                <div class="edit-task-body">
                    <div class="edit-form-grid">
                        <div class="edit-form-group">
                            <label for="create_task_name">Tugas</label>
                            <input type="text" id="create_task_name" name="task_name" value="{{ old('task_name') }}" placeholder="Contoh: Membersihkan meja kerja" required maxlength="255">
                        </div>

                        <div class="edit-form-group">
                            <label for="create_priority">Prioritas</label>
                            <select id="create_priority" name="priority" required>
                                <option value="">Pilih Prioritas</option>
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="edit-form-group">
                            <label for="create_area">Area</label>
                            <select id="create_area" name="area" required>
                                <option value="">Pilih Area</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->name }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="edit-form-group">
                            <label for="create_status">Status</label>
                            <select id="create_status" name="status" required>
                                <option value="pending" selected>Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="edit-task-footer">
                    <button type="button" class="edit-cancel-btn" id="cancelCreateTask">Cancel</button>
                    <button type="submit" class="edit-save-btn">Simpan Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT TUGAS -->
    <div class="edit-task-overlay" id="editTaskModal" hidden>
        <div class="edit-task-modal" role="dialog" aria-modal="true" aria-labelledby="editTaskTitle">
            <div class="edit-task-header">
                <h2 id="editTaskTitle">Edit Cleaning Tasks</h2>
                <button type="button" class="close-edit-modal" id="closeEditTask" aria-label="Tutup modal">&times;</button>
            </div>

            <form id="editTaskForm" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="edit-task-body">
                    <div class="edit-form-grid">
                        <div class="edit-form-group">
                            <label for="edit_task_name">Tugas</label>
                            <input type="text" id="edit_task_name" name="task_name" required maxlength="255">
                        </div>

                        <div class="edit-form-group">
                            <label for="edit_priority">Prioritas</label>
                            <select id="edit_priority" name="priority" required>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="edit-form-group">
                            <label for="edit_area">Area</label>
                            <select id="edit_area" name="area" required>
                                <option value="">Pilih Area</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->name }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="edit-form-group">
                            <label for="edit_status">Status</label>
                            <select id="edit_status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="edit-task-footer">
                    <button type="button" class="edit-cancel-btn" id="cancelEditTask">Batal</button>
                    <button type="submit" class="edit-save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE KONFIRMASI -->
    <div class="edit-task-overlay" id="deleteTaskModal" hidden>
        <div class="edit-task-modal" role="dialog" aria-modal="true" aria-labelledby="deleteTaskTitle" style="max-width: 450px;">
            <div class="edit-task-header" style="background-color: #d81243; color: white;">
                <h2 id="deleteTaskTitle" style="display: flex; align-items: center; gap: 8px; color: white;">
                    ⚠️ Hapus Data?
                </h2>
                <button type="button" class="close-edit-modal" id="closeDeleteTask" aria-label="Tutup modal">&times;</button>
            </div>

            <div class="edit-task-body" style="padding: 24px; text-align: center;">
                <p style="font-size: 14px; color: #333; line-height: 1.5;">
                    Apakah Anda yakin ingin menghapus tugas kebersihan ini? Data tugas yang telah dihapus akan dikeluarkan dari sistem dan tidak dapat dikembalikan.
                </p>
            </div>

            <div class="edit-task-footer" style="display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px;">
                <button type="button" class="edit-cancel-btn" id="cancelDeleteTask" style="padding: 8px 16px; border: 1px solid #ccc; background: #fff; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="edit-save-btn" style="padding: 8px 16px; background-color: #d81243; color: white; border: none; border-radius: 6px; cursor: pointer;">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Notifikasi Toast Auto Hide ---
        const flashNotif = document.getElementById('flashNotification');
        if (flashNotif) {
            setTimeout(() => {
                flashNotif.style.transition = 'opacity 0.5s ease';
                flashNotif.style.opacity = '0';
                setTimeout(() => flashNotif.remove(), 500);
            }, 4000);
        }

        // --- Modal Delete Logic ---
        const deleteModal = document.getElementById('deleteTaskModal');
        const closeDeleteBtn = document.getElementById('closeDeleteTask');
        const cancelDeleteBtn = document.getElementById('cancelDeleteTask');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        
        let targetDeleteForm = null;

        function closeDeleteModalFunc() {
            if (deleteModal) deleteModal.hidden = true;
            targetDeleteForm = null;
        }

        document.querySelectorAll('.single-delete-form').forEach(function(form) {
            const deleteButton = form.querySelector('.delete-task-action');
            if (deleteButton) {
                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    targetDeleteForm = form;
                    if (deleteModal) deleteModal.hidden = false;
                });
            }
        });

        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                if (targetDeleteForm) {
                    targetDeleteForm.submit();
                }
            });
        }

        if (closeDeleteBtn) closeDeleteBtn.addEventListener('click', closeDeleteModalFunc);
        if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModalFunc);

        if (deleteModal) {
            deleteModal.addEventListener('click', function (event) {
                if (event.target === deleteModal) {
                    closeDeleteModalFunc();
                }
            });
        }

        // --- Search, Filter, & Pagination Setup ---
        const rows = Array.from(document.querySelectorAll('.task-row'));
        const searchTask = document.getElementById('searchTask');
        const searchArea = document.getElementById('searchArea');
        const searchPriority = document.getElementById('searchPriority');
        const searchStatus = document.getElementById('searchStatus');

        const noResults = document.getElementById('noResults');
        const taskCount = document.getElementById('taskCount');
        const showingCount = document.getElementById('showingCount');
        const pagination = document.getElementById('pagination');
        const selectAll = document.getElementById('selectAllTasks');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

        const perPage = 5;
        let currentPage = 1;
        let filteredRows = [];

        // --- Modal Create Logic ---
        const createModal = document.getElementById('createTaskModal');
        const openCreateBtn2 = document.getElementById('openCreateModalBtn');
        const closeCreateBtn = document.getElementById('closeCreateTask');
        const cancelCreateBtn = document.getElementById('cancelCreateTask');

        function openCreateModalFunc() {
            if (createModal) createModal.hidden = false;
        }
        function closeCreateModalFunc() {
            if (createModal) createModal.hidden = true;
        }

        if (openCreateBtn2) openCreateBtn2.addEventListener('click', openCreateModalFunc);
        if (closeCreateBtn) closeCreateBtn.addEventListener('click', closeCreateModalFunc);
        if (cancelCreateBtn) cancelCreateBtn.addEventListener('click', closeCreateModalFunc);

        if (createModal) {
            createModal.addEventListener('click', function (event) {
                if (event.target === createModal) {
                    closeCreateModalFunc();
                }
            });
        }

        // --- Modal Edit Logic ---
        const editModal = document.getElementById('editTaskModal');
        const editForm = document.getElementById('editTaskForm');

        function closeEditModal() {
            if (editModal) editModal.hidden = true;
        }

        document.querySelectorAll('.edit-task-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                if (editForm) {
                    editForm.action = `/cleaning-tasks/${id}`;
                }

                const nameInput = document.getElementById('edit_task_name');
                const areaInput = document.getElementById('edit_area');
                const priorityInput = document.getElementById('edit_priority');
                const statusInput = document.getElementById('edit_status');

                if (nameInput) nameInput.value = this.dataset.name || '';
                if (areaInput) areaInput.value = this.dataset.area || '';
                if (priorityInput) priorityInput.value = this.dataset.priority || 'medium';
                if (statusInput) statusInput.value = this.dataset.status || 'pending';

                if (editModal) editModal.hidden = false;
            });
        });

        const closeEditTaskBtn = document.getElementById('closeEditTask');
        const cancelEditTaskBtn = document.getElementById('cancelEditTask');

        if (closeEditTaskBtn) closeEditTaskBtn.addEventListener('click', closeEditModal);
        if (cancelEditTaskBtn) cancelEditTaskBtn.addEventListener('click', closeEditModal);

        if (editModal) {
            editModal.addEventListener('click', function (event) {
                if (event.target === editModal) {
                    closeEditModal();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (editModal && !editModal.hidden) closeEditModal();
                if (createModal && !createModal.hidden) closeCreateModalFunc();
                if (deleteModal && !deleteModal.hidden) closeDeleteModalFunc();
            }
        });

        function filterTasks() {
            const taskKeyword = searchTask ? searchTask.value.toLowerCase().trim() : '';
            const areaKeyword = searchArea ? searchArea.value.toLowerCase().trim() : '';
            const priorityKeyword = searchPriority ? searchPriority.value.toLowerCase().trim() : '';
            const statusKeyword = searchStatus ? searchStatus.value.toLowerCase().trim() : '';

            filteredRows = rows.filter(function (row) {
                return row.dataset.task.includes(taskKeyword)
                    && row.dataset.area.includes(areaKeyword)
                    && row.dataset.priority.includes(priorityKeyword)
                    && row.dataset.status.includes(statusKeyword);
            });

            currentPage = 1;
            renderTasks();
        }

        function renderTasks() {
            const total = filteredRows.length;
            const totalPages = Math.ceil(total / perPage);
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;

            rows.forEach(function (row) {
                row.hidden = true;
            });

            filteredRows.slice(start, end).forEach(function (row) {
                row.hidden = false;
            });

            if (noResults) {
                noResults.hidden = total !== 0;
            }

            if (taskCount) taskCount.textContent = total + ' tugas';

            if (showingCount) {
                showingCount.textContent = total === 0
                    ? 'Tidak ada tugas yang ditampilkan'
                    : `Menampilkan ${start + 1}–${Math.min(end, total)} dari ${total} tugas`;
            }

            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }

            if (pagination) {
                pagination.innerHTML = '';

                if (totalPages > 1) {
                    function createPageButton(label, page, active, disabled) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = label;
                        button.className = 'page-btn' + (active ? ' active' : '');
                        button.disabled = disabled;

                        button.addEventListener('click', function () {
                            currentPage = page;
                            renderTasks();
                            updateDeleteButton();
                        });

                        pagination.appendChild(button);
                    }

                    createPageButton('‹', currentPage - 1, false, currentPage === 1);

                    for (let page = 1; page <= totalPages; page++) {
                        createPageButton(String(page), page, page === currentPage, false);
                    }

                    createPageButton('›', currentPage + 1, false, currentPage === totalPages);
                }
            }
        }

        [searchTask, searchArea, searchPriority, searchStatus].forEach(function (input) {
            if (input) input.addEventListener('input', filterTasks);
        });

        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
            if (deleteSelectedBtn) {
                deleteSelectedBtn.hidden = checkedBoxes.length === 0;
            }

            if (selectAll) {
                const visibleBoxes = filteredRows
                    .slice((currentPage - 1) * perPage, currentPage * perPage)
                    .map(row => row.querySelector('.task-checkbox'))
                    .filter(Boolean);

                const checkedVisible = visibleBoxes.filter(box => box.checked);

                selectAll.checked = visibleBoxes.length > 0 && checkedVisible.length === visibleBoxes.length;
                selectAll.indeterminate = checkedVisible.length > 0 && checkedVisible.length < visibleBoxes.length;
            }
        }

        document.querySelectorAll('.task-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', updateDeleteButton);
        });

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                filteredRows
                    .slice((currentPage - 1) * perPage, currentPage * perPage)
                    .forEach(function (row) {
                        const checkbox = row.querySelector('.task-checkbox');
                        if (checkbox) {
                            checkbox.checked = selectAll.checked;
                        }
                    });

                updateDeleteButton();
            });
        }

        filterTasks();
    });
    </script>
</body>
</html>