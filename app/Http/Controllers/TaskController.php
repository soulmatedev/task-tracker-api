<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Account;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Метод для создания задачи
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'projectId' => 'required|integer|exists:Project,id',
            'assignedTo' => 'nullable|array',
            'assignedTo.id' => 'required_with:assignedTo|integer|exists:Account,id',
            'assignedTo.login' => 'required_with:assignedTo|string',
            'dueDate' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|integer|exists:Status,id',
        ]);

        $statusId = $request->status ?? 0;

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'projectId' => $request->projectId,
            'createdBy' => $user->id,
            'assignedTo' => $request->assignedTo['id'] ?? null,
            'dueDate' => $request->dueDate,
            'status' => $statusId,
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'assignedTo' => 'nullable|array',
            'assignedTo.id' => 'required_with:assignedTo|integer|exists:Account,id',
            'assignedTo.login' => 'required_with:assignedTo|string',
            'dueDate' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|integer|exists:Status,id',
        ]);

        $task = Task::findOrFail($id);

        if ($task->createdBy != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->only(['title', 'description', 'dueDate', 'status']);

        if (isset($request->assignedTo['id'])) {
            $data['assignedTo'] = $request->assignedTo['id'];
        }

        $task->update($data);

        return response()->json($task, 200);
    }

    public function delete($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $task = Task::findOrFail($id);

        if ($task->createdBy != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    public function getTasksByProjectId($projectId)
    {
        $project = Project::findOrFail($projectId);

        $tasks = $project->tasks()->with(['assignedTo', 'status'])->get();

        return response()->json($tasks, 200);
    }

    public function getTasksByAssignedTo($accountId)
    {
        $account = Account::findOrFail($accountId);

        $tasks = $account->assignedTasks()->with(['project', 'status'])->get();

        $tasks->transform(function ($task) use ($account) {
            $task->assignedTo = (object) [
                'id' => $account->id,
                'login' => $account->login,
            ];
            return $task;
        });

        return response()->json($tasks, 200);
    }

    public function getTasksWithoutCurrentUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $tasks = Task::where('assignedTo', '!=', $user->id)
            ->orWhereNull('assignedTo')
            ->with(['project', 'status', 'assignedToAccount'])
            ->get();

        $tasks->transform(function ($task) {
            if ($task->assignedToAccount) {
                $task->assignedTo = (object) [
                    'id' => $task->assignedToAccount->id,
                    'login' => $task->assignedToAccount->login,
                ];
            } else {
                $task->assignedTo = null;
            }
            unset($task->assignedToAccount);
            return $task;
        });

        return response()->json($tasks, 200);
    }

    public function getStatuses()
    {
        $statuses = Status::all();

        return response()->json($statuses, 200);
    }


    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'status' => 'required|integer|exists:Status,id',
        ]);

        $task = Task::findOrFail($id);

        if ($task->createdBy != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->status = $request->status;
        $task->save();

        return response()->json($task, 200);
    }
}
