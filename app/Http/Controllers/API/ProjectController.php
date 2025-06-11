<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index() {
        return Project::with('owner')->where('owner_id', auth()->id())->get();
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        return Project::create([
            'name' => $request->name,
            'owner_id' => auth()->id()
        ]);
    }

    public function show(Project $project) {
        $this->authorize('view', $project);
        return $project->load('tasks');
    }

    public function update(Request $request, Project $project) {
        $this->authorize('update', $project);
        $project->update($request->only('name'));
        return $project;
    }

    public function destroy(Project $project) {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->json(['message' => 'Deleted']);
    }

}
