document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // PENCARIAN AREA
    // ==========================================
    const areaSearch = document.getElementById('searchAreaInput') || document.getElementById('areaSearch');

    if (areaSearch) {
        areaSearch.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();

            document.querySelectorAll('.area-card').forEach(function (card) {
                const name = (card.dataset.areaName || '').toLowerCase();
                card.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    }

    // ==========================================
    // BUKA DAN TUTUP MODAL
    // ==========================================
    function openAreaModal(modal) {
        if (!modal) {
            console.error('Modal area tidak ditemukan.');
            return;
        }

        if (modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeAreaModal(modal) {
        if (!modal) return;

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');

        if (!document.querySelector('.area-modal-overlay.is-open')) {
            document.body.style.overflow = '';
        }
    }

    // Event Delegation (Satu pintu untuk semua klik tombol modal)
    document.addEventListener('click', function (event) {

        // 1. Tombol Edit Area
        const editButton = event.target.closest('[data-edit-area]');
        if (editButton) {
            event.preventDefault();
            const areaId = editButton.dataset.editArea;
            const editModal = document.getElementById('edit-area-' + areaId);
            openAreaModal(editModal);
            return;
        }

        // 2. Tombol Delete Area
        const deleteButton = event.target.closest('[data-delete-area]');
        if (deleteButton) {
            event.preventDefault();
            const areaId = deleteButton.dataset.deleteArea;
            const deleteModal = document.getElementById('delete-area-' + areaId);
            openAreaModal(deleteModal);
            return;
        }

        // 3. Tombol Buka Modal Umum (Tambah / Detail)
        const openButton = event.target.closest('[data-open-modal]');
        if (openButton) {
            event.preventDefault();
            const modalId = openButton.dataset.openModal;
            const modal = document.getElementById(modalId);
            openAreaModal(modal);
            return;
        }

        // 4. Tombol Tutup Modal (Close button atau Batal)
        const closeButton = event.target.closest('[data-close-modal], .kelola-modal-close, .area-modal-close');
        if (closeButton) {
            event.preventDefault();
            const modalOverlay = closeButton.closest('.area-modal-overlay');
            closeAreaModal(modalOverlay);
            return;
        }

        // 5. Tutup modal jika area luar (overlay gelap) diklik
        if (
            event.target.classList.contains('area-modal-overlay') &&
            event.target.classList.contains('is-open')
        ) {
            closeAreaModal(event.target);
        }
    });

    // Tutup modal menggunakan tombol Escape pada keyboard
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.area-modal-overlay.is-open')
                .forEach(closeAreaModal);
        }
    });

    // ==========================================
    // VALIDASI FOTO
    // ==========================================
    function isValidPhoto(file) {
        if (!file) return false;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!allowedTypes.includes(file.type)) {
            alert('Format foto harus JPG, PNG, atau WEBP.');
            return false;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            return false;
        }

        return true;
    }

    document.querySelectorAll('input[type="file"][name="photo"]').forEach(function (photoInput) {
        const inputId = photoInput.id;
        const uploadBox = document.querySelector('label.area-upload-box[for="' + inputId + '"]');
        const fileName = photoInput.dataset.filename
            ? document.getElementById(photoInput.dataset.filename)
            : document.getElementById(
                inputId === 'add-area-photo'
                    ? 'add-area-file-name'
                    : 'edit-area-file-name-' + inputId.replace('edit-area-photo-', '')
            );

        function displayFileName(file) {
            if (fileName) {
                fileName.textContent = file ? file.name : 'Belum ada foto dipilih';
            }
        }

        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
                displayFileName(null);
                return;
            }
            if (!isValidPhoto(file)) {
                this.value = '';
                displayFileName(null);
                return;
            }
            displayFileName(file);
        });
    });

    // Reopen modal jika validasi tambah area gagal
    const addAreaModal = document.getElementById('add-area-modal');
    if (addAreaModal && addAreaModal.dataset.reopen === 'true') {
        openAreaModal(addAreaModal);
    }

    // Notifikasi Sukses Toast
    const toast = document.getElementById('areaSuccessToast');
    const closeToast = document.getElementById('closeAreaToast');
    if (toast && closeToast) {
        closeToast.addEventListener('click', function () {
            toast.remove();
        });
    }
});