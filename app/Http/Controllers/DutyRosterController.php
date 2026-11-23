<?php

namespace App\Http\Controllers;

use App\Models\DutyRoster;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DutyRosterController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $currentMonth = Carbon::createFromFormat(
            'Y-m',
            $request->query('month', now()->format('Y-m'))
        )->startOfMonth();

        $monthStart = $currentMonth->copy()->startOfMonth()->toDateString();
        $monthEnd = $currentMonth->copy()->endOfMonth()->toDateString();

        $users = User::orderBy('name')->get();

        // Jadwal yang beririsan dengan bulan yang ditampilkan.
        $dutyRosters = DutyRoster::with([
            'member1',
            'member2',
            'member3',
            'member4',
        ])
            ->whereDate('start_date', '<=', $monthEnd)
            ->whereDate('end_date', '>=', $monthStart)
            ->orderBy('start_date')
            ->get();

        // Semua jadwal untuk tabel.
        $allDutyRosters = DutyRoster::with([
            'member1',
            'member2',
            'member3',
            'member4',
        ])
            ->orderByDesc('start_date')
            ->get();

        return view('duty-roster.index', compact(
            'users',
            'dutyRosters',
            'allDutyRosters',
            'currentMonth'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                Rule::in(['Active', 'Non-Active']),
            ],
            'members' => ['required', 'array', 'size:4'],
            'members.*' => [
                'required',
                'distinct',
                'exists:users,id',
            ],
        ], [
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' =>
                'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'members.required' => 'Pilih tepat 4 anggota piket.',
            'members.size' => 'Anggota piket harus tepat 4 orang.',
            'members.*.distinct' =>
                'Anggota piket tidak boleh dipilih lebih dari sekali.',
            'members.*.exists' =>
                'Anggota yang dipilih tidak ditemukan.',
        ]);

        // Period tetap mengikuti tanggal mulai.
        $data = [
            'period' => $validated['start_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ];

        $members = array_values($validated['members']);

        for ($i = 1; $i <= 4; $i++) {
            $data["member_{$i}_id"] = $members[$i - 1];
        }

        DutyRoster::create($data);

        return redirect()
            ->route('duty-roster.index')
            ->with(
                'success',
                'Jadwal piket baru berhasil dibuat. Data telah tersimpan di dalam sistem.'
            );
    }

    public function update(Request $request, DutyRoster $dutyRoster)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                Rule::in(['Active', 'Non-Active']),
            ],
            'members' => ['required', 'array', 'size:4'],
            'members.*' => [
                'required',
                'distinct',
                'exists:users,id',
            ],
        ], [
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' =>
                'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'members.required' => 'Pilih tepat 4 anggota piket.',
            'members.size' => 'Anggota piket harus tepat 4 orang.',
            'members.*.distinct' =>
                'Anggota piket tidak boleh dipilih lebih dari sekali.',
            'members.*.exists' =>
                'Anggota yang dipilih tidak ditemukan.',
        ]);

        // Cegah tanggal mulai yang sama dengan jadwal lain.
        $request->validate([
            'start_date' => [
                Rule::unique('duty_rosters', 'period')
                    ->ignore($dutyRoster->id),
            ],
        ], [
            'start_date.unique' =>
                'Sudah ada jadwal yang dimulai pada tanggal tersebut.',
        ]);

        $data = [
            'period' => $validated['start_date'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ];

        $members = array_values($validated['members']);

        for ($i = 1; $i <= 4; $i++) {
            $data["member_{$i}_id"] = $members[$i - 1];
        }

        $dutyRoster->update($data);

        return redirect()
            ->route('duty-roster.index')
            ->with(
                'success',
                'Jadwal piket berhasil diperbarui. Perubahan telah tersimpan di dalam sistem.'
            );
    }

    public function destroy(DutyRoster $dutyRoster)
    {
        $dutyRoster->delete();

        return redirect()
            ->route('duty-roster.index')
            ->with(
                'success',
                'Jadwal piket berhasil dihapus. Data telah dihapus dari sistem.'
            );
    }
}