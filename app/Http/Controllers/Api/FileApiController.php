<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class FileApiController extends Controller
{
    /**
     * GET /api/files — public files listing
     */
    public function index(Request $request)
    {
        $files = File::with('user:id,name')
            ->where('is_public', true)
            ->whereNull('password')
            ->when($request->search, fn($q) =>
                $q->where('original_name', 'like', '%' . $request->search . '%')
            )
            ->when($request->type, function ($q) use ($request) {
                match ($request->type) {
                    'image'    => $q->where('mime_type', 'like', 'image/%'),
                    'document' => $q->whereIn('mime_type', [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]),
                    'text'     => $q->where('mime_type', 'like', 'text/%'),
                    default    => null,
                };
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return response()->json([
            'data'  => $files->map(fn($f) => [
                'id'             => $f->id,
                'name'           => $f->original_name,
                'size'           => $f->formatted_size,
                'mime_type'      => $f->mime_type,
                'download_count' => $f->download_count,
                'owner'          => $f->user->name,
                'share_url'      => url('/share/' . $f->share_token),
                'created_at'     => $f->created_at->diffForHumans(),
            ]),
            'meta'  => [
                'total'        => $files->total(),
                'current_page' => $files->currentPage(),
                'last_page'    => $files->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/files/{token} — single public file info
     */
    public function show(string $token)
    {
        $file = File::where('share_token', $token)
            ->where('is_public', true)
            ->with('user:id,name')
            ->firstOrFail();

        return response()->json([
            'id'             => $file->id,
            'name'           => $file->original_name,
            'size'           => $file->formatted_size,
            'mime_type'      => $file->mime_type,
            'download_count' => $file->download_count,
            'owner'          => $file->user->name,
            'share_url'      => url('/share/' . $file->share_token),
            'created_at'     => $file->created_at->diffForHumans(),
            'expires_at'     => $file->expires_at?->toISOString(),
        ]);
    }
}
