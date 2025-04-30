<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    public function index()
    {
        $attachments = Attachment::with('uploader')->paginate(perPage: 12);
        $users = User::all(); // For share modal
        return view('admin.documents', compact('attachments', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
            'task_id' => 'required|exists:tasks,id'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $path = $file->store('attachments');

            $uploadedFiles[] = Attachment::create([
                'task_id' => $request->task_id,
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        return response()->json(['success' => true, 'files' => $uploadedFiles]);
    }

    public function download(Attachment $attachment)
    {
        if (Storage::disk('public')->exists($attachment->path)) {
            $filePath = Storage::disk('public')->path($attachment->path);
            return response()->download($filePath, $attachment->filename);
        }
        
        return Storage::download($attachment->path);
    }

    public function destroy(Attachment $attachment)
    {
        Storage::delete($attachment->path);
        $attachment->delete();
        return back()->with('success', 'File deleted successfully');
    }
}
