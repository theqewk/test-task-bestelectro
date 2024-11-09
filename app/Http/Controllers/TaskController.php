<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        $tasks = $user->getVisibleTasks()
            ->with([
                'creator',
                'assignee',
                'department',
                'userWatchers',
                'departmentWatchers'
            ])
            ->latest()
            ->get();

        $tasks = $tasks->map(function ($task) {
            $watchers = [];

            foreach ($task->userWatchers as $user) {
                $watchers[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'type' => 'user'
                ];
            }

            foreach ($task->departmentWatchers as $department) {
                $watchers[] = [
                    'id' => $department->id,
                    'name' => $department->name,
                    'type' => 'department'
                ];
            }

            $task->watchers = $watchers;
            return $task;
        });

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'users' => User::select(['id', 'name', 'position', 'department_id'])->get(),
            'departments' => Department::select('id', 'name')->get(),
            'auth' => [
                'user' => $user->only(['id', 'name', 'position', 'department_id'])
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,normal,high,blocking',
            'assignee_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'watchers' => 'nullable|array',
            'watchers.*.id' => 'required_with:watchers|integer',
            'watchers.*.type' => 'required_with:watchers|in:user,department',
        ], [
            'title.required' => 'Поле "Название" обязательно для заполнения',
            'description.required' => 'Поле "Описание" обязательно для заполнения',
            'priority.required' => 'Необходимо выбрать приоритет',
        ]);

        // Проверяем, что выбран хотя бы один исполнитель (сотрудник или отдел)
        if (empty($validated['assignee_id']) && empty($validated['department_id'])) {
            return back()->withErrors([
                'executor' => 'Необходимо выбрать исполнителя или отдел'
            ])->withInput();
        }

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'new',
            'creator_id' => auth()->id(),
            'assignee_id' => $validated['assignee_id'] ?: null,
            'department_id' => $validated['department_id'] ?: null,
        ]);

        if (!empty($validated['watchers'])) {
            foreach ($validated['watchers'] as $watcher) {
                $watcherType = $watcher['type'] === 'user' ? User::class : Department::class;

                $task->task_watchers()->create([
                    'task_id' => $task->id,
                    'watcher_id' => $watcher['id'],
                    'watcher_type' => $watcherType,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Задача успешно создана');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,completed,rejected',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $task->update($validated);

        return redirect()->back();
    }
}
