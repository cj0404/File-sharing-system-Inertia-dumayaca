<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

class FileStatsController extends Controller
{
    public function index()
    {
        $files = Auth::user()->files()->with('tags', 'comments')->get();
        
        return response()->json([
            'total_files' => $files->count(),
            'total_size' => $files->sum('size'),
            'total_downloads' => $files->sum('download_count'),
'favorites' => $files->where('starred', true)->count(),
            'public_files' => $files->where('is_public', true)->count(),
            'private_files' => $files->where('is_public', false)->count(),
            'files_with_tags' => $files->filter(fn($f) => $f->tags->count() > 0)->count(),
            'files_with_comments' => $files->filter(fn($f) => $f->comments->count() > 0)->count(),
        ]);
    }
}
