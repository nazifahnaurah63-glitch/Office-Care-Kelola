<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\CleaningTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with('cleaningTasks')
            ->withCount(['cleaningTasks as total_tasks'])
            ->orderBy('id')
            ->get();

        return view('areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:areas,name',
            ],
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('areas', 'public');
        }

        Area::create($validated);

        return redirect()
            ->route('areas.index')
            ->with('success', 'Area baru berhasil dibuat. Data lokasi telah tersimpan di dalam sistem.');
    }

    public function show(string $area)
    {
        $area = Area::where('name', $area)->firstOrFail();

        $tasks = CleaningTask::where('area', $area->name)
            ->latest('task_date')
            ->get();

        return view('areas.show', compact('area', 'tasks'));
    }
}
