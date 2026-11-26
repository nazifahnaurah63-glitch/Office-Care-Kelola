// ==========================================
// MODAL TAMBAH DAN EDIT
// ==========================================

function showRosterModal(modalId) {
    const modal = document.getElementById(modalId);

    if (!modal) return;

    document.querySelectorAll('.roster-modal.show').forEach(item => {
        item.classList.remove('show');
        item.setAttribute('aria-hidden', 'true');
    });

    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');

    updateAllMemberCheckboxes(modal);
}

function hideRosterModal(modalId) {
    const modal = document.getElementById(modalId);

    if (!modal) return;

    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
}

document.getElementById('openRosterModal')?.addEventListener('click', () => {
    showRosterModal('rosterModal');
});

document.querySelectorAll('[data-open-edit]').forEach(button => {
    button.addEventListener('click', () => {
        showRosterModal(button.dataset.openEdit);
    });
});

document.querySelectorAll('[data-close-modal]').forEach(button => {
    button.addEventListener('click', () => {
        hideRosterModal(button.dataset.closeModal);
    });
});

// ==========================================
// CHECKBOX ANGGOTA: MAKSIMAL 4 ORANG
// ==========================================

function updateAllMemberCheckboxes(scope = document) {
    scope.querySelectorAll('.checkbox-member-card').forEach(card => {
        const checkboxes = [
            ...card.querySelectorAll('input[type="checkbox"]')
        ];

        const selected = checkboxes.filter(input => input.checked);
        const count = card.querySelector('.selected-count');

        if (count) {
            count.textContent = `${selected.length}/4`;
        }

        checkboxes.forEach(input => {
            input.disabled = !input.checked && selected.length >= 4;
        });
    });
}

document.querySelectorAll(
    '.checkbox-member-card input[type="checkbox"]'
).forEach(input => {
    input.addEventListener('change', () => {
        const card = input.closest('.checkbox-member-card');

        const checked = card.querySelectorAll(
            'input[type="checkbox"]:checked'
        );

        if (checked.length > 4) {
            input.checked = false;
            alert('Maksimal hanya 4 anggota piket.');
        }

        updateAllMemberCheckboxes(card);
    });
});

updateAllMemberCheckboxes();


// ==========================================
// MODAL KONFIRMASI HAPUS
// ==========================================

const deleteConfirmModal = document.getElementById('deleteConfirmModal');
const actualDeleteForm = document.getElementById('actualDeleteRosterForm');
const confirmDeleteButton = document.getElementById('confirmDeleteRoster');

let deleteFormTarget = null;

function showDeleteConfirm(form) {
    if (!deleteConfirmModal || !form) return;

    deleteFormTarget = form;

    deleteConfirmModal.classList.add('show');
    deleteConfirmModal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('delete-modal-open');
}

function hideDeleteConfirm() {
    if (!deleteConfirmModal) return;

    deleteConfirmModal.classList.remove('show');
    deleteConfirmModal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('delete-modal-open');

    deleteFormTarget = null;
}

document.querySelectorAll('form[data-confirm-delete]').forEach(form => {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        showDeleteConfirm(this);
    });
});

document.querySelectorAll('[data-close-delete]').forEach(button => {
    button.addEventListener('click', hideDeleteConfirm);
});

confirmDeleteButton?.addEventListener('click', () => {
    if (!deleteFormTarget || !actualDeleteForm) return;

    actualDeleteForm.action = deleteFormTarget.action;
    actualDeleteForm.submit();
});

// Tutup modal dengan tombol Escape.
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
        hideDeleteConfirm();

        document.querySelectorAll('.roster-modal.show').forEach(modal => {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        });
    }
});

// ==========================================
// NOTIFIKASI BERHASIL
// ==========================================

const rosterToast = document.getElementById('rosterSuccessToast');
const rosterToastClose = document.querySelector('.roster-toast-close');

rosterToastClose?.addEventListener('click', () => {
    rosterToast?.classList.add('hide');
});

if (rosterToast) {
    window.setTimeout(() => {
        rosterToast.classList.add('hide');
    }, 5000);
}

// ==========================================
// PENCARIAN DAN PAGINATION TABEL
// ==========================================

const tableBody = document.getElementById('rosterTableBody');
const entriesSelect = document.getElementById('entriesSelect');
const tableInfo = document.getElementById('tableInfo');
const tablePagination = document.getElementById('tablePagination');

const filters = [
    ...document.querySelectorAll('.filter-row input[data-column]')
];

let currentPage = 1;

function renderRosterTable() {
    if (!tableBody) return;

    const allRows = [
        ...tableBody.querySelectorAll('.roster-table-row')
    ];

    const filteredRows = allRows.filter(row => {
        return filters.every(input => {
            const column = Number(input.dataset.column);
            const cell = row.cells[column];

            return cell &&
                cell.innerText.toLowerCase().includes(
                    input.value.toLowerCase()
                );
        });
    });

    const perPage = Number(entriesSelect?.value || 10);

    const totalPages = Math.max(
        1,
        Math.ceil(filteredRows.length / perPage)
    );

    currentPage = Math.min(currentPage, totalPages);

    allRows.forEach(row => {
        row.hidden = true;
    });

    const start = (currentPage - 1) * perPage;

    const visibleRows = filteredRows.slice(
        start,
        start + perPage
    );

    visibleRows.forEach(row => {
        row.hidden = false;
    });

    filteredRows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');

        if (numberCell) {
            numberCell.textContent = start + index + 1;
        }
    });

    if (tableInfo) {
        tableInfo.textContent = filteredRows.length
            ? `Showing ${start + 1} to ${Math.min(
                start + perPage,
                filteredRows.length
            )} of ${filteredRows.length} entries`
            : 'Showing 0 to 0 of 0 entries';
    }

    if (tablePagination) {
        tablePagination.innerHTML = '';

        const addButton = (
            label,
            page,
            disabled = false,
            current = false
        ) => {
            const button = document.createElement('button');

            button.type = 'button';
            button.textContent = label;
            button.disabled = disabled;

            if (current) {
                button.classList.add('current');
            }

            button.addEventListener('click', () => {
                currentPage = page;
                renderRosterTable();
            });

            tablePagination.appendChild(button);
        };

        addButton('«', 1, currentPage === 1);
        addButton(
            '‹',
            Math.max(1, currentPage - 1),
            currentPage === 1
        );

        for (let page = 1; page <= totalPages; page++) {
            addButton(
                String(page),
                page,
                false,
                page === currentPage
            );
        }

        addButton(
            '›',
            Math.min(totalPages, currentPage + 1),
            currentPage === totalPages
        );

        addButton(
            '»',
            totalPages,
            currentPage === totalPages
        );
    }
}

filters.forEach(input => {
    input.addEventListener('input', () => {
        currentPage = 1;
        renderRosterTable();
    });
});

entriesSelect?.addEventListener('change', () => {
    currentPage = 1;
    renderRosterTable();
});

renderRosterTable();