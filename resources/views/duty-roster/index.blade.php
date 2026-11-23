<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duty Roster | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/duty-roster.css') }}">
</head>
<body>
@php
    $currentRole = auth()->user()->role;

    $can = fn ($permission) =>
        $currentRole && $currentRole->hasPermission($permission);

    $calendarStart = $currentMonth->copy()
        ->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);

    $calendarEnd = $currentMonth->copy()
        ->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);

    $calendarDays = collect();

    for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
        $calendarDays->push($date->copy());
    }

    $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
@endphp

<div class="app">

    @include('partials.sidebar')

    {{-- KONTEN UTAMA --}}
    <main class="main-content">

        @include('partials.topbar')
        
        {{-- JUDUL HALAMAN --}}
        <section class="page-heading">
            <div class="heading-icon">▣</div>

            <div>
                <h1>Duty Roster</h1>
                <p>Kelola jadwal piket karyawan</p>
            </div>
        </section>

        {{-- NOTIFIKASI BERHASIL --}}
        @if (session('success'))
            <div class="roster-toast"
                 id="rosterSuccessToast"
                 role="status"
                 aria-live="polite">

                <span class="roster-toast-icon" aria-hidden="true">✓</span>

                <strong>{{ session('success') }}</strong>

                <button type="button"
                        class="roster-toast-close"
                        aria-label="Tutup notifikasi">
                    ×
                </button>
            </div>
        @endif

        {{-- NOTIFIKASI VALIDASI --}}
        @if ($errors->any())
            <div class="roster-alert error-alert">
                <strong>Jadwal belum berhasil disimpan:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- KALENDER --}}
        <section class="roster-panel">

            <div class="roster-toolbar">
                <div></div>

                @if ($can('duty_roster.add'))
                    <button type="button"
                            class="add-roster-btn"
                            id="openRosterModal">
                        <span>＋</span> Buat Jadwal
                    </button>
                @endif
            </div>

            <div class="calendar-toolbar">
                <a class="month-arrow"
                   href="{{ route('duty-roster.index', ['month' => $previousMonth]) }}"
                   aria-label="Bulan sebelumnya">
                    ‹
                </a>

                <a class="month-arrow"
                   href="{{ route('duty-roster.index', ['month' => $nextMonth]) }}"
                   aria-label="Bulan berikutnya">
                    ›
                </a>

                <h3>
                    {{ $monthNames[$currentMonth->month] }}
                    {{ $currentMonth->year }}
                </h3>
            </div>

            <div class="calendar-scroll">
                <div class="calendar-grid">

                    @foreach ([
                        'Minggu', 'Senin', 'Selasa', 'Rabu',
                        'Kamis', 'Jumat', 'Sabtu'
                    ] as $weekday)
                        <div class="calendar-weekday">
                            {{ $weekday }}
                        </div>
                    @endforeach

                    @foreach ($calendarDays as $day)
                        @php
                            $dayRosters = $dutyRosters->filter(function ($item) use ($day) {
                                $start = \Carbon\Carbon::parse(
                                    $item->start_date ?? $item->period
                                )->startOfDay();

                                $end = \Carbon\Carbon::parse(
                                    $item->end_date ?? $item->period
                                )->startOfDay();

                                return $day->copy()->startOfDay()
                                    ->betweenIncluded($start, $end);
                            });

                            $isCurrentMonth = $day->month === $currentMonth->month;
                            $isToday = $day->isToday();
                        @endphp

                        <div class="calendar-day
                            {{ $isCurrentMonth ? '' : 'other-month' }}
                            {{ $isToday ? 'is-today' : '' }}">

                            <div class="day-number">
                                {{ $day->day }}
                            </div>

                            @foreach ($dayRosters as $roster)
                                <div class="roster-event">

                                    <div class="event-title">
                                        <span class="event-dot"></span>
                                        {{ $roster->status }}
                                    </div>

                                    @foreach ([
                                        $roster->member1,
                                        $roster->member2,
                                        $roster->member3,
                                        $roster->member4
                                    ] as $member)
                                        @if ($member)
                                            <div class="member-name">
                                                {{ $member->name }}
                                            </div>
                                        @endif
                                    @endforeach

                                    @if ($can('duty_roster.delete'))
                                        <form method="POST"
      action="{{ route('duty-roster.destroy', $roster) }}"
      data-confirm-delete>
    @csrf
    @method('DELETE')

    <button type="submit"
            class="table-delete-btn"
            title="Hapus jadwal"
            aria-label="Hapus jadwal">
        ♜
    </button>
</form>
                                    @endif

                                </div>
                            @endforeach

                            @if ($dayRosters->isEmpty() && $isCurrentMonth)
                                <span class="empty-day">
                                    Belum ada piket
                                </span>
                            @endif

                        </div>
                    @endforeach

                </div>
            </div>

            <div class="calendar-legend">
                <span><i></i> Jadwal piket aktif</span>
                <span class="legend-muted">
                    Maksimal 4 anggota per jadwal
                </span>
            </div>

        </section>

        {{-- TABEL JADWAL --}}
        <section class="roster-table-panel">

            <div class="table-topbar">
                <label class="entries-control">
                    <select id="entriesSelect"
                            aria-label="Jumlah data per halaman">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </label>
            </div>

            <div class="roster-table-scroll">
                <table class="roster-table" id="rosterTable">

                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>PERIOD</th>
                            <th>MEMBER</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>

                        <tr class="filter-row">
                            <th>
                                <input type="search"
                                       data-column="0"
                                       placeholder="Search No">
                            </th>

                            <th>
                                <input type="search"
                                       data-column="1"
                                       placeholder="Search Period">
                            </th>

                            <th>
                                <input type="search"
                                       data-column="2"
                                       placeholder="Search Member">
                            </th>

                            <th>
                                <input type="search"
                                       data-column="3"
                                       placeholder="Search Status">
                            </th>

                            <th></th>
                        </tr>
                    </thead>

                    <tbody id="rosterTableBody">
                        @forelse ($allDutyRosters as $roster)
                            @php
                                $startDate = \Carbon\Carbon::parse(
                                    $roster->start_date ?? $roster->period
                                );

                                $endDate = \Carbon\Carbon::parse(
                                    $roster->end_date ?? $roster->period
                                );

                                $members = collect([
                                    $roster->member1,
                                    $roster->member2,
                                    $roster->member3,
                                    $roster->member4,
                                ])->filter();
                            @endphp

                            <tr class="roster-table-row">

                                <td class="row-number">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $startDate->format('d-m-Y') }}

                                    @if (!$startDate->isSameDay($endDate))
                                        – {{ $endDate->format('d-m-Y') }}
                                    @endif
                                </td>

                                <td>
                                    <div class="table-members">
                                        @foreach ($members as $member)
                                            <span>{{ $member->name }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge
                                        {{ $roster->status === 'Active' ? 'status-active' : 'status-inactive' }}">
                                        {{ in_array($roster->status, ['Inactive', 'Non-Active'], true)
                                            ? 'Non-Active'
                                            : $roster->status }}
                                    </span>
                                </td>

                                <td>
                                    <div class="table-actions">

                                        @if ($can('duty_roster.edit'))
                                            <button type="button"
                                                    class="edit-roster-btn"
                                                    data-open-edit="editRosterModal-{{ $roster->id }}"
                                                    title="Edit jadwal">
                                                ✎
                                            </button>
                                        @endif

                                        @if ($can('duty_roster.delete'))
                                            <form method="POST"
                                                  action="{{ route('duty-roster.destroy', $roster) }}"
                                                  data-confirm-delete>
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="table-delete-btn"
                                                        title="Hapus jadwal"
                                                        aria-label="Hapus jadwal">
                                                    ♜
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr class="no-data-row">
                                <td colspan="5" class="table-empty">
                                    Belum ada jadwal piket.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="table-bottom">
                <span id="tableInfo">Menampilkan data jadwal</span>
                <div class="table-pagination" id="tablePagination"></div>
            </div>

        </section>

        {{-- FOOTER --}}
        <footer>
            <span>
                © Copyright 2026, PT Kelola Bisnis Indonesia. All Rights Reserved.
            </span>

            <div>
                <a href="#">Whatsapp</a>
                <a href="#">Email</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Legal Cookie</a>
            </div>
        </footer>

    </main>
</div>

{{-- MODAL TAMBAH JADWAL --}}
@if ($can('duty_roster.add'))
    <div class="roster-modal"
         id="rosterModal"
         aria-hidden="true">

        <div class="roster-modal-backdrop"
             data-close-modal="rosterModal"></div>

        <section class="roster-modal-card"
                 role="dialog"
                 aria-modal="true"
                 aria-labelledby="addModalTitle">

            <div class="modal-heading">
                <h2 id="addModalTitle">Add Duty Roster</h2>

                <button type="button"
                        class="modal-close"
                        data-close-modal="rosterModal"
                        aria-label="Tutup">
                    ×
                </button>
            </div>

            <form method="POST" action="{{ route('duty-roster.store') }}">
                @csrf

                <div class="modal-body">

                    <div class="date-status-grid">

                        <div class="form-field">
                            <label for="add_start_date">
                                Tanggal Mulai <span>*</span>
                            </label>

                            <input type="date"
                                   id="add_start_date"
                                   name="start_date"
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                   required>
                        </div>

                        <div class="form-field">
                            <label for="add_end_date">
                                Tanggal Selesai <span>*</span>
                            </label>

                            <input type="date"
                                   id="add_end_date"
                                   name="end_date"
                                   value="{{ old('end_date', now()->format('Y-m-d')) }}"
                                   required>
                        </div>

                        <div class="form-field">
                            <label for="add_status">
                                Status <span>*</span>
                            </label>

                            <select id="add_status" name="status" required>
                                <option value="Active"
                                    @selected(old('status', 'Active') === 'Active')>
                                    Active
                                </option>

                                <option value="Non-Active"
                                    @selected(old('status') === 'Non-Active')>
                                    Non-Active
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="checkbox-member-card"
                         style="display:flex!important;flex-direction:column!important;align-items:stretch!important;width:100%!important;box-sizing:border-box!important;margin-top:16px!important;padding:18px 14px!important;border:1px solid #eeeeF2!important;border-radius:8px!important;background:#fff!important;">

                        <div class="checkbox-member-title"
                             style="display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:space-between!important;width:100%!important;margin:0 0 14px!important;padding-bottom:14px!important;border-bottom:1px solid #eeeeF2!important;box-sizing:border-box!important;">

                            <span>Anggota Piket</span>
                            <span class="selected-count">0/4</span>
                        </div>

                        <div class="checkbox-member-grid"
                             style="display:grid!important;grid-template-columns:repeat(5,minmax(0,1fr))!important;gap:12px 10px!important;width:100%!important;min-width:0!important;">

                            @foreach ($users as $user)
                                <label class="member-checkbox">
                                    <input type="checkbox"
                                           name="members[]"
                                           value="{{ $user->id }}"
                                           @checked(in_array(
                                               (string) $user->id,
                                               array_map('strval', old('members', [])),
                                               true
                                           ))>

                                    <span>{{ $user->name }}</span>
                                </label>
                            @endforeach

                        </div>
                    </div>

                </div>

                <div class="modal-actions">
                    <button type="button"
                            class="cancel-btn"
                            data-close-modal="rosterModal">
                        Cancel
                    </button>

                    <button type="submit" class="save-roster-btn">
                        Submit
                    </button>
                </div>

            </form>
        </section>
    </div>
@endif

{{-- MODAL EDIT JADWAL --}}
@if ($can('duty_roster.edit'))
    @foreach ($allDutyRosters as $roster)

        @php
            $savedMemberIds = collect([
                $roster->member1_id,
                $roster->member2_id,
                $roster->member3_id,
                $roster->member4_id,
            ])->filter()->map(fn ($id) => (string) $id)->all();

            $isOldEdit = (string) old('roster_id') === (string) $roster->id;
            $oldEditMembers = $isOldEdit
                ? array_map('strval', old('members', []))
                : $savedMemberIds;
        @endphp

        <div class="roster-modal"
             id="editRosterModal-{{ $roster->id }}"
             aria-hidden="true">

            <div class="roster-modal-backdrop"
                 data-close-modal="editRosterModal-{{ $roster->id }}"></div>

            <section class="roster-modal-card"
                     role="dialog"
                     aria-modal="true"
                     aria-labelledby="editModalTitle-{{ $roster->id }}">

                <div class="modal-heading">
                    <h2 id="editModalTitle-{{ $roster->id }}">
                        Edit Duty Roster
                    </h2>

                    <button type="button"
                            class="modal-close"
                            data-close-modal="editRosterModal-{{ $roster->id }}"
                            aria-label="Tutup">
                        ×
                    </button>
                </div>

                <form method="POST"
                      action="{{ route('duty-roster.update', $roster) }}">

                    @csrf
                    @method('PUT')

                    <input type="hidden"
                           name="roster_id"
                           value="{{ $roster->id }}">

                    <div class="modal-body">

                        <div class="date-status-grid">

                            <div class="form-field">
                                <label for="edit_start_{{ $roster->id }}">
                                    Tanggal Mulai <span>*</span>
                                </label>

                                <input type="date"
                                       id="edit_start_{{ $roster->id }}"
                                       name="start_date"
                                       value="{{ $isOldEdit
                                           ? old('start_date')
                                           : \Carbon\Carbon::parse($roster->start_date ?? $roster->period)->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="form-field">
                                <label for="edit_end_{{ $roster->id }}">
                                    Tanggal Selesai <span>*</span>
                                </label>

                                <input type="date"
                                       id="edit_end_{{ $roster->id }}"
                                       name="end_date"
                                       value="{{ $isOldEdit
                                           ? old('end_date')
                                           : \Carbon\Carbon::parse($roster->end_date ?? $roster->period)->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="form-field">
                                <label for="edit_status_{{ $roster->id }}">
                                    Status <span>*</span>
                                </label>

                                <select id="edit_status_{{ $roster->id }}"
                                        name="status"
                                        required>

                                    <option value="Active"
                                        @selected(
                                            $isOldEdit
                                                ? old('status') === 'Active'
                                                : $roster->status === 'Active'
                                        )>
                                        Active
                                    </option>

                                    <option value="Non-Active"
                                        @selected(
                                            $isOldEdit
                                                ? old('status') === 'Non-Active'
                                                : in_array($roster->status, ['Non-Active', 'Inactive'], true)
                                        )>
                                        Non-Active
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="checkbox-member-card"
                             style="display:flex!important;flex-direction:column!important;align-items:stretch!important;width:100%!important;box-sizing:border-box!important;margin-top:16px!important;padding:18px 14px!important;border:1px solid #eeeeF2!important;border-radius:8px!important;background:#fff!important;">

                            <div class="checkbox-member-title"
                                 style="display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:space-between!important;width:100%!important;margin:0 0 14px!important;padding-bottom:14px!important;border-bottom:1px solid #eeeeF2!important;box-sizing:border-box!important;">

                                <span>Anggota Piket</span>
                                <span class="selected-count">0/4</span>
                            </div>

                            <div class="checkbox-member-grid"
                                 style="display:grid!important;grid-template-columns:repeat(5,minmax(0,1fr))!important;gap:12px 10px!important;width:100%!important;min-width:0!important;">

                                @foreach ($users as $user)
                                    <label class="member-checkbox">
                                        <input type="checkbox"
                                               name="members[]"
                                               value="{{ $user->id }}"
                                               @checked(in_array(
                                                   (string) $user->id,
                                                   $oldEditMembers,
                                                   true
                                               ))>

                                        <span>{{ $user->name }}</span>
                                    </label>
                                @endforeach

                            </div>
                        </div>

                    </div>

                    <div class="modal-actions">
                        <button type="button"
                                class="cancel-btn"
                                data-close-modal="editRosterModal-{{ $roster->id }}">
                            Cancel
                        </button>

                        <button type="submit" class="save-roster-btn">
                            Submit
                        </button>
                    </div>

                </form>
            </section>
        </div>

    @endforeach
@endif

{{-- MODAL KONFIRMASI HAPUS --}}
@if ($can('duty_roster.delete'))
    <div class="delete-confirm-overlay"
         id="deleteConfirmModal"
         aria-hidden="true">

        <div class="delete-confirm-backdrop"
             data-close-delete></div>

        <section class="delete-confirm-card"
         role="dialog"
         aria-modal="true"
         aria-labelledby="deleteConfirmTitle"
         style="width: 690px !important; max-width: calc(100vw - 80px) !important; margin: 0 !important;">

            <div class="delete-confirm-heading"
     style="min-height: 110px !important; padding: 28px 40px !important; box-sizing: border-box !important;">
                <h2 id="deleteConfirmTitle">
                    <span aria-hidden="true">⚠️</span>
                    Hapus Data?
                </h2>

                <button type="button"
                        class="delete-confirm-close"
                        data-close-delete
                        aria-label="Tutup">×</button>
            </div>

            <div class="delete-confirm-body">
                <p>Apakah Anda yakin ingin menghapus jadwal piket ini?</p>
                <p>Data jadwal yang telah dihapus akan dikeluarkan dari sistem dan tidak dapat dikembalikan.</p>
            </div>

            <div class="delete-confirm-actions">
                <button type="button"
                        class="delete-cancel-btn"
                        data-close-delete>
                    Cancel
                </button>

                <button type="button"
                        class="delete-continue-btn"
                        id="confirmDeleteRoster">
                    Ya, Lanjutkan
                </button>
            </div>
        </section>
    </div>

    <form id="actualDeleteRosterForm"
          method="POST"
          action=""
          hidden>
        @csrf
        @method('DELETE')
    </form>
@endif

<script>
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

    // ==========================================
    // BUKA KEMBALI MODAL JIKA VALIDASI GAGAL
    // ==========================================

    @if ($errors->any() && !old('roster_id'))
        showRosterModal('rosterModal');
    @elseif ($errors->any() && old('roster_id'))
        showRosterModal('editRosterModal-{{ old('roster_id') }}');
    @endif
</script>

</body>
</html>