<?php

namespace App\Http\Controllers;

use App\Models\CleaningTask;
use Illuminate\Http\Request;

class CleaningTaskController extends Controller
{
    public function index()
    {
        $tasks = CleaningTask::latest()->get();

        return view('cleaning_tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('cleaning_tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'task_date' => 'required|date',
            'status' => 'required|in:pending,completed',
        ]);

        CleaningTask::create($validated);

        return redirect()
            ->route('cleaning-tasks.index')
            ->with('success', 'Tugas kebersihan berhasil ditambahkan!');
    }
}
