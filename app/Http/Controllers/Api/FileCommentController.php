<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileCommentController extends Controller
{
    // Get file comments
    public function index(File $file)
    {
        $this->authorize('view', $file);
        
        return response()->json(
            $file->comments()->with('user')->latest()->get()
        );
    }

    // Store comment
    public function store(Request $request, File $file)
    {
        $this->authorize('update', $file);
        
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $file->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return response()->json($comment->load('user'), 201);
    }

    // Delete comment
    public function destroy(File $file, Comment $comment)
    {
        $this->authorize('update', $file);
        
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();
        
        return response()->json(['message' => 'Comment deleted']);
    }
}
