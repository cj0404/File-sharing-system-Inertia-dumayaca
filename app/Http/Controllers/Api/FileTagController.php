<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileTagController extends Controller
{
    // Get all tags for current user
    public function index()
    {
        return response()->json(Auth::user()->tags()->get());
    }

    // Attach tag to file
    public function attach(Request $request, Tag $tag, File $file)
    {
        $this->authorize('update', $file);
        
        if ($file->user_id !== Auth::id() || $tag->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $file->tags()->syncWithoutDetaching([$tag->id]);
        
        return response()->json(['message' => 'Tag attached']);
    }

    // Detach tag from file
    public function detach(Tag $tag, File $file)
    {
        $this->authorize('update', $file);
        
        if ($file->user_id !== Auth::id() || $tag->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $file->tags()->detach($tag->id);
        
        return response()->json(['message' => 'Tag detached']);
    }

    // Create new tag
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|regex:/^[a-z]+$/',
        ]);

        $tag = Auth::user()->tags()->create($validated);
        
        return response()->json($tag, 201);
    }
}
