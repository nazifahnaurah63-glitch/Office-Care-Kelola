<?php

namespace App\Http\Controllers;

use App\Models\CleaningTask;
use App\Models\CleaningActivity;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil tugas yang dijadwalkan hari ini
        $todayTasks = CleaningTask::whereDate(
            'task_date',
            Carbon::today()
        )->get();
        
        $completedTasks = CleaningTask::where(
            'status',
            'completed'
        )->count();

        $pendingTasks = CleaningTask::where(
            'status',
            'pending'
        )->count();

        // Menghitung aktivitas kebersihan secara akurat
        $totalActivities = CleaningActivity::count();

        $completedActivities = CleaningActivity::whereRaw('LOWER(status) = ?', ['selesai'])->count();

        $pendingActivities = CleaningActivity::whereRaw('LOWER(status) = ?', ['pending'])->count();

        // Menghitung persentase progres kebersihan
        $progress = $totalActivities > 0
            ? round(($completedActivities / $totalActivities) * 100)
            : 0;

        // Mengambil aktivitas terbaru
        $recentActivities = CleaningActivity::latest('tanggal')
            ->take(10)
            ->get()
            ->map(function ($activity) {
                return (object) [
                    'name' => $activity->employee_name ?? $activity->pelaksana ?? 'Administrator',
                    'description' => ($activity->jenis_aktivitas ?? '-') . ' - ' . ($activity->area ?? '-'),
                    'time' => $activity->tanggal ? Carbon::parse($activity->tanggal) : null,
                ];
            });

        $recentTasks = CleaningTask::whereDoesntHave('activities')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($task) {
                return (object) [
                    'name' => 'Tugas Baru',
                    'description' => $task->task_name . ' - ' . $task->area,
                    'time' => $task->created_at,
                ];
            });

        $recentItems = $recentActivities
            ->concat($recentTasks)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        // Mengambil pengumuman aktif terbaru
        $announcements = Announcement::where(
            'is_active',
            true
        )->latest()->take(5)->get();

        return view('dashboard', compact(
            'todayTasks',
            'completedTasks',
            'pendingTasks',
            'totalActivities',
            'completedActivities',
            'pendingActivities',
            'progress',
            'recentItems',
            'announcements'
        ));
    }
}