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
            'assignedAccounts.*.id' => 'required|integer|exists:Account,id',
            'assignedAccounts.*.login' => 'required|string|max:255',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'createdBy' => $user->id,
            'createdAt' => $request->has('createdAt') ? $request->createdAt : now(),
        ]);

        $assignedAccounts = collect($request->assignedAccounts ?? []);
        $assignedAccounts->push([
            'id' => $user->id,
            'login' => $user->login,
        ]);

        $assignedAccountsIds = $assignedAccounts->pluck('id');
        $project->assignedAccounts()->sync($assignedAccountsIds);

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
            'assignedAccounts.*.id' => 'required|integer|exists:Account,id',
            'assignedAccounts.*.login' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->only(['name', 'description']));

        if ($request->has('assignedAccounts')) {
            $assignedAccountsIds = collect($request->assignedAccounts)->pluck('id');
            $project->assignedAccounts()->sync($assignedAccountsIds);
        }

        return response()->json($project->load('assignedAccounts'), 200);
    }

    public function getProjectsByAccountId($accountId)
    {
        $account = Account::findOrFail($accountId);

        $projects = $account->assignedProjects()->with('creator')->get();

        return response()->json($projects, 200);
    }

    public function getAssignedAccounts($projectId)
    {
        $project = Project::findOrFail($projectId);

        $assignedAccounts = $project->assignedAccounts()->get(['Account.id', 'Account.login']);

        return response()->json($assignedAccounts, 200);
    }
}
