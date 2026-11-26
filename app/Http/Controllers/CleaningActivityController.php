<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\CleaningActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CleaningActivityController extends Controller
{
    public function index()
{
    $totalActivities = CleaningActivity::count();
    
    // Hitung hanya yang berstatus 'Selesai' (pastikan hurufnya sesuai dengan database, misal 'Selesai' atau 'selesai')
    $completedActivities = CleaningActivity::where('status', 'Selesai')->count();

    // Hitung persentase (jika total 0, set 0 untuk menghindari Division by Zero)
    $completionRate = $totalActivities > 0 
        ? round(($completedActivities / $totalActivities) * 100) 
        : 0;

    $todayActivities = CleaningActivity::whereDate('tanggal', today())->count();
    $areas = Area::all();
    $activities = CleaningActivity::latest()->get();

    return view('cleaning_activities.index', compact(
        'totalActivities', 
        'todayActivities', 
        'completionRate', 
        'areas', 
        'activities'
    ));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis_aktivitas' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = $request->file('foto')->store(
            'cleaning-activities',
            'public'
        );

        CleaningActivity::create([
            'activity_date' => $validated['tanggal'],
            'activity_name' => $validated['jenis_aktivitas'],
            'jenis_aktivitas' => $validated['jenis_aktivitas'],
            'area' => $validated['area'],
            'foto' => $fotoPath,
            'employee_name' => auth()->user()->name,
            'status' => 'completed',
        ]);

        return redirect()
            ->route('cleaning-activities.index')
            ->with(
                'success',
                'Aktivitas kebersihan berhasil ditambahkan.'
            );
    }

public function update(Request $request, CleaningActivity $cleaningActivity)
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'jenis_aktivitas' => 'required|string|max:255',
        'area' => 'required|string|max:255',
        'status' => 'required|string', // Dibuat fleksibel menerima 'Selesai' atau 'Pending'
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $fotoPath = $cleaningActivity->foto;

    if ($request->hasFile('foto')) {
        if ($fotoPath) {
            Storage::disk('public')->delete($fotoPath);
        }

        $fotoPath = $request->file('foto')->store(
            'cleaning-activities',
            'public'
        );
    }

    $cleaningActivity->update([
        'tanggal' => $validated['tanggal'],
        'jenis_aktivitas' => $validated['jenis_aktivitas'],
        'area' => $validated['area'],
        'status' => ucfirst(strtolower($validated['status'])), // Memastikan format teks rapi (Selesai / Pending)
        'foto' => $fotoPath,
    ]);

    return redirect()
        ->route('cleaning-activities.index')
        ->with('success', 'Aktivitas kebersihan berhasil diperbarui.');
}

    public function destroy(CleaningActivity $cleaningActivity)
    {
        if ($cleaningActivity->foto) {
            Storage::disk('public')->delete($cleaningActivity->foto);
        }

        $cleaningActivity->delete();

        return redirect()
            ->route('cleaning-activities.index')
            ->with('success', 'Aktivitas kebersihan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'activity_ids' => 'required|array|min:1',
            'activity_ids.*' => 'required|integer|distinct|exists:cleaning_activities,id',
        ]);

        $activities = CleaningActivity::whereIn(
            'id',
            $validated['activity_ids']
        )->get();

        foreach ($activities as $activity) {
            if ($activity->foto) {
                Storage::disk('public')->delete($activity->foto);
            }

            $activity->delete();
        }

        return redirect()
            ->route('cleaning-activities.index')
            ->with(
                'success',
                'Aktivitas yang dipilih berhasil dihapus.'
            );
    }
}
