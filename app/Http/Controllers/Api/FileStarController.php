<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileStarController extends Controller
{
    public function toggle(File $file)
    {
        if ($file->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $file->update(['starred' => !$file->starred]);
        
        return response()->json([
            'starred' => $file->starred,
            'message' => $file->starred ? 'File favorited' : 'File unfavorited'
        ]);
    }
}
