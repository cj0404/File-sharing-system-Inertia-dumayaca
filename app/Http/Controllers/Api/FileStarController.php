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
        $this->authorize('update', $file);
        
        $file->update(['starred' => !$file->starred]);
        
        return response()->json([
            'starred' => $file->starred,
            'message' => $file->starred ? 'File starred' : 'File unstarred'
        ]);
    }
}
