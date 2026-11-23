document.addEventListener('DOMContentLoaded', function () {
    // Pencarian kartu area
    const areaSearch = document.getElementById('areaSearch');

    if (areaSearch) {
        areaSearch.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();

            document.querySelectorAll('.area-card').forEach(function (card) {
                const name = card.dataset.areaName || '';
                card.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    }

    // Buka modal
    function openAreaModal(modal) {
        if (!modal) return;

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        const firstInput = modal.querySelector(
            'input:not([type="hidden"]), textarea'
        );

        if (firstInput) {
            firstInput.focus();
        }
    }

    // Tutup modal
    function closeAreaModal(modal) {
        if (!modal) return;

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');

        if (!document.querySelector('.area-modal-overlay.is-open')) {
            document.body.style.overflow = '';
        }
    }

    document.querySelectorAll('[data-open-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            const modal = document.getElementById(this.dataset.openModal);
            openAreaModal(modal);
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            closeAreaModal(this.closest('.area-modal-overlay'));
        });
    });

    document.querySelectorAll('.area-modal-overlay').forEach(function (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeAreaModal(modal);
            }
        });
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.area-modal-overlay.is-open')
                .forEach(closeAreaModal);
        }
    });

    // Nama file dan fitur drag-and-drop foto
    const photoInput = document.getElementById('add-area-photo');
    const uploadBox = document.querySelector(
        'label.area-upload-box[for="add-area-photo"]'
    );
    const fileName = document.getElementById('add-area-file-name');

    function displayFileName(file) {
        if (fileName) {
            fileName.textContent = file
                ? file.name
                : 'Belum ada foto dipilih';
        }
    }

    function isValidPhoto(file) {
        if (!file) return false;

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

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

    if (photoInput) {
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
    }

    if (uploadBox && photoInput) {
        ['dragenter', 'dragover'].forEach(function (eventName) {
            uploadBox.addEventListener(eventName, function (event) {
                event.preventDefault();
                uploadBox.classList.add('is-dragging');
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            uploadBox.addEventListener(eventName, function (event) {
                event.preventDefault();
                uploadBox.classList.remove('is-dragging');
            });
        });

        uploadBox.addEventListener('drop', function (event) {
            const file = event.dataTransfer.files[0];

            if (!file || !isValidPhoto(file)) return;

            const transfer = new DataTransfer();
            transfer.items.add(file);
            photoInput.files = transfer.files;

            displayFileName(file);
        });
    }

    // Buka kembali modal Add Area jika validasi backend gagal
    const addAreaModal = document.getElementById('add-area-modal');

    if (addAreaModal && addAreaModal.dataset.reopen === 'true') {
        openAreaModal(addAreaModal);
    }

    // Tutup notifikasi berhasil
    const toast = document.getElementById('areaSuccessToast');
    const closeToast = document.getElementById('closeAreaToast');

    if (toast && closeToast) {
        closeToast.addEventListener('click', function () {
            toast.remove();
        });
    }
});