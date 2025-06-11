<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->get('project_id');
        $tasks = Task::with('user')->where('project_id', $projectId)->get();
        return response()->json($tasks);
    }

    public function getTaskByAssignedTo()
    {
        $assignedTo = auth()->id();
        $tasks = Task::with('project', 'user')->where('assigned_to', $assignedTo)->get();
        return response()->json($tasks);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        return Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
        ]);
    }

    public function update(Request $request, Task $task) {
        if ($task->project->owner_id == auth()->id()) {
            $task->update($request->only('title', 'description', 'is_completed', 'assigned_to'));
        }
        else if ($task->assigned_to == auth()->id()) {
            $task->update($request->only('is_completed'));
        } else {
            abort(403, 'You do not have permission to update this task');
        }
        
        return $task;
    }

    public function destroy(Task $task) {
        $this->authorize('delete', $task->project);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
