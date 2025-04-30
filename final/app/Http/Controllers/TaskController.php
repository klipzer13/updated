<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Priority;
use App\Models\Task;
use App\Models\Status;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssignedMail;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $statuses = Status::all();
        $priorities = Priority::all();
        $users = User::where('id', '!=', Auth::id())->get();
        $attachments = Attachment::all();

        $query = Task::with(['priority', 'users', 'creator'])
            ->withCount('users')
            ->latest();

        if ($request->has('status_id') && $request->status_id) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('status_id', $request->status_id);
            });
        }

        if ($request->has('priority_id') && $request->priority_id) {
            $query->where('priority_id', $request->priority_id);
        }

        if ($request->has('assignee') && $request->assignee) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', $request->assignee);
            });
        }

        if ($request->has('due_date') && $request->due_date) {
            switch ($request->due_date) {
                case 'today':
                    $query->whereDate('due_date', today());
                    break;
                case 'week':
                    $query->whereBetween('due_date', [now(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('due_date', [now(), now()->endOfMonth()]);
                    break;
                case 'overdue':
                    $query->whereDate('due_date', '<', today());
                    break;
            }
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $tasks = $query->paginate(10);
        return view('admin.alltask', compact('tasks', 'statuses', 'priorities', 'users'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $priorities = Priority::all();
        $departments = Department::with('users')->get();
        $recentTasks = Task::where('created_by', Auth::id())
                          ->with(['assignees', 'priority'])
                          ->latest()
                          ->take(5)
                          ->get();

        return view('admin.assigntask', compact('users', 'priorities', 'departments', 'recentTasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'priority_id' => 'required|exists:priorities,id',
            'assignees' => 'required|array',
            'assignees.*' => 'required|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'priority_id' => $validated['priority_id'],
            'created_by' => Auth::id(),
        ]);

        $defaultStatus = Status::where('name', 'pending')->first();

        foreach ($validated['assignees'] as $userId) {
            $task->assignees()->attach($userId, ['status_id' => $defaultStatus->id]);
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $mimeType = $file->getMimeType();
                $folder = 'others';

                if (str_starts_with($mimeType, 'image')) {
                    $folder = 'images';
                } elseif (str_starts_with($mimeType, 'application/pdf')) {
                    $folder = 'documents';
                } elseif (str_starts_with($mimeType, 'video')) {
                    $folder = 'videos';
                } elseif (str_starts_with($mimeType, 'audio')) {
                    $folder = 'audios';
                } elseif (str_starts_with($mimeType, 'application/vnd.ms-excel') || 
                         str_contains($mimeType, 'spreadsheetml')) {
                    $folder = 'spreadsheets';
                }

                $path = $file->store("{$folder}", 'public');
                Attachment::create([
                    'task_id' => $task->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $mimeType,
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        foreach ($validated['assignees'] as $assigneeId) {
            $assignee = User::find($assigneeId);
            Mail::to($assignee->email)->send(new TaskAssignedMail($task, $assignee));
        }

        Log::info('Task created', [
            'task_title' => $task->title,
            'created_by' => 'name ' . Auth::user()->name . ' ID ' . Auth::id(),
            
            'assignees' => User::whereIn('id', $validated['assignees'])->pluck('name')->toArray(),
        ]);

        return back()->with('success', 'Task has been assigned successfully!');
    }

    public function dash()
    {
        $totalTasks = Task::count();
        $totalMembers = User::count();

        $inProgressTasks = Task::whereHas('users', function ($query) {
            $query->whereIn('status_id', [1, 2]);
        })->count();

        $completedTasks = Task::whereHas('users', function ($query) {
            $query->where('status_id', 3);
        })->count();

        $recentTasks = Task::with(['priority', 'users'])
                         ->latest()
                         ->take(5)
                         ->get();

        $teamMembers = User::withCount('tasks')
                          ->orderBy('tasks_count', 'desc')
                          ->take(4)
                          ->get();

        $statuses = Status::all();

        return view('admin.dashboard', compact(
            'totalTasks',
            'totalMembers',
            'inProgressTasks',
            'completedTasks',
            'recentTasks',
            'teamMembers',
            'statuses'
        ));
    }

    public function documents()
    {
        $documents = Attachment::where('type', 'application/pdf')->get();
        return view('admin.documents', compact('documents'));
    }
    
    public function addComment(Request $request, $taskId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);
        
        $comment = TaskComment::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        
        return response()->json([
            'success' => true,
            'comment' => $comment->load('user')
        ]);
    }
    
    public function addAttachment(Request $request, $taskId)
    {
        $request->validate([
            'attachments.*' => 'required|file|max:10240'
        ]);
        
        $uploadedFiles = [];
        
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task_attachments', 'public');
                
                $attachment = Attachment::create([
                    'task_id' => $taskId,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::id()
                ]);
                
                $uploadedFiles[] = $attachment;
            }
        }
        
        return response()->json([
            'success' => true,
            'attachments' => $uploadedFiles
        ]);
    }
    
}