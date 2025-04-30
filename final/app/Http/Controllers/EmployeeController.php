<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status;
use App\Models\Attachment;
use App\Models\TaskComment;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function myTasks(Request $request)
    {
        $statuses = Status::all();

        $tasks = Task::with(['priority', 'creator', 'attachments'])
            ->whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('employee.mytask2', compact('tasks', 'statuses'));
    }

    public function taskDetails($id)
    {
        $task = Task::with([
            'priority',
            'creator',
            'attachments',
            'comments.user',
            'users'
        ])
            ->whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $userStatus = $task->users()->where('user_id', Auth::id())->first()->pivot->status_id;
        $status = Status::find($userStatus);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $status->name,
                'status_id' => $status->id,
                'priority' => [
                    'id' => $task->priority->id,
                    'name' => $task->priority->name
                ],
                'due_date' => $task->due_date->format('Y-m-d'),
                'due_date_formatted' => $task->due_date->format('M d, Y'),
                'creator' => [
                    'id' => $task->creator->id,
                    'name' => $task->creator->name,
                    'avatar_url' => $task->creator->avatar_url ?? asset('storage/profile/avatars/profile.png')
                ],
                'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                'created_at_formatted' => $task->created_at->format('M d, Y h:i A'),
                'attachments' => $task->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'original_name' => $attachment->filename,
                        'path' => $attachment->path,
                        'mime_type' => $attachment->type,
                        'size' => $attachment->size,
                        'url' => $attachment->path,
                        'uploaded_at' => $attachment->created_at->format('M d, Y h:i A')
                    ];
                }),
                'comments' => $task->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->comment,
                        'created_at' => $comment->created_at->format('M d, Y h:i A'),
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'avatar_url' => $comment->user->avatar_url ?? asset('storage/profile/avatars/profile.png')
                        ]
                    ];
                })
            ]
        ]);
    }

    // In your TaskController.php
    public function submitTask(Request $request)
    {


        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:2048',
        ]);
        // dd($request->all());
        $task = Task::findOrFail($request->task_id);
        $user = Auth::user();
        $status = Status::where('name', 'pending_approval')->first();
        $task->users()->updateExistingPivot($user->id, [
            'status_id' => $status->id,
            'updated_at' => now()
        ]);
        if (!empty($request->comment)) {
            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'comment' => $request->comment
            ]);
        }
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task_attachments');
                Attachment::create([
                    'task_id' => $task->id,
                    'uploaded_by' => $user->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }
        return back()->with('success', 'Task submitted successfully');
    }

    public function markComplete($id)
    {
        $task = Task::whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->findOrFail($id);

        $completedStatus = Status::where('name', 'completed')->first();

        $task->users()->updateExistingPivot(Auth::id(), [
            'status_id' => $completedStatus->id,
            'completed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task marked as complete'
        ]);
    }
    public function show($id)
    {
        $task = Task::with([
            'priority',
            'creator',
            'attachments',
            'comments.user',
            'users'
        ])
            ->whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $userStatus = $task->users()->where('user_id', Auth::id())->first()->pivot->status_id;
        $status = Status::findOrFail($userStatus);

        return view('chairperson.task-details', [
            'task' => $task,
            'status' => $status
        ]);
    }
}