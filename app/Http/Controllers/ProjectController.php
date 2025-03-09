<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Account;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignedAccounts' => 'sometimes|array',
            'assignedAccounts.*' => 'integer|exists:Account,id',
        ]);


        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'createdBy' => $user->id,
            'createdAt' => $request->has('createdAt') ? $request->createdAt : now(),
        ]);

        $project->assignedAccounts()->sync([$user->id]);

        return response()->json($project->load('assignedAccounts'), 201);
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Проект удален'], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'assignedAccounts' => 'sometimes|array',
            'assignedAccounts.*' => 'integer|exists:account,id',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->only(['name', 'description']));

        if ($request->has('assignedAccounts')) {
            $assignedAccounts = Account::whereIn('id', $request->assignedAccounts)->get();
            $project->assignedAccounts()->sync($assignedAccounts);
        }

        return response()->json($project->load('assignedAccounts'), 200);
    }

    public function getProjectsByAccountId($accountId)
    {
        $account = Account::findOrFail($accountId);

        $projects = $account->assignedProjects()->with('creator')->get();

        return response()->json($projects, 200);
    }
}
