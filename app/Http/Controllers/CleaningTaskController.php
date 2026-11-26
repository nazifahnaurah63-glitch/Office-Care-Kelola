<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\CleaningTask;
use Illuminate\Http\Request;

class CleaningTaskController extends Controller
{
    public function index()
    {
        $tasks = CleaningTask::latest()->get();
        $areas = Area::orderBy('name')->get();

        $totalTasks = CleaningTask::count();

        $completedTasks = CleaningTask::where(
            'status',
            'completed'
        )->count();

        $pendingTasks = CleaningTask::where(
            'status',
            'pending'
        )->count();

        $overdueTasks = CleaningTask::where('status', 'pending')
            ->whereDate('task_date', '<', today())
            ->count();

        return view('cleaning_tasks.index', compact(
            'tasks',
            'areas',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'overdueTasks'
        ));
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get();

        return view('cleaning_tasks.create', compact('areas'));
    }

    // Menyimpan tugas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'area' => 'required|string|exists:areas,name',
            'assigned_to' => 'nullable|string|max:255',
            'task_date' => 'nullable|date',
            'status' => 'required|in:pending,completed',
            'priority' => 'required|in:high,medium,low',
        ]);

        // Otomatis isi tanggal hari ini jika tidak ada input task_date dari modal
        $validated['task_date'] = $validated['task_date'] ?? now()->toDateString();

        CleaningTask::create($validated);

        return redirect()
            ->route('cleaning-tasks.index')
            ->with('success', 'Tugas kebersihan baru berhasil dibuat. Data telah tersimpan di dalam sistem.');
    }

    // Memperbarui tugas melalui modal Edit
    public function update(Request $request, CleaningTask $cleaningTask)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'area' => 'required|string|exists:areas,name',
            'assigned_to' => 'nullable|string|max:255',
            'task_date' => 'nullable|date',
            'status' => 'required|in:pending,completed',
            'priority' => 'required|in:high,medium,low',
        ]);

        // Pertahankan tanggal lama jika tidak diubah dari modal
        $validated['task_date'] = $validated['task_date'] ?? $cleaningTask->task_date ?? now()->toDateString();

        $cleaningTask->update($validated);

        return redirect()
            ->route('cleaning-tasks.index')
            ->with('success', 'Perubahan tugas kebersihan berhasil disimpan.');
    }

    // Menghapus satu tugas
    public function destroy(CleaningTask $cleaningTask)
    {
        $cleaningTask->delete();

        return redirect()
            ->route('cleaning-tasks.index')
            ->with('success', 'Tugas kebersihan berhasil dihapus dari sistem.');
    }

    // Menghapus beberapa tugas sekaligus
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array|min:1',
            'task_ids.*' => 'required|integer|distinct|exists:cleaning_tasks,id',
        ]);

        CleaningTask::whereIn('id', $validated['task_ids'])->delete();

        return redirect()
            ->route('cleaning-tasks.index')
            ->with('success', 'Tugas yang dipilih berhasil dihapus dari sistem.');
    }
}