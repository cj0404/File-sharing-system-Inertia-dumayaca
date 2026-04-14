<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FileController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    // ─── INDEX ────────────────────────────────────────────────────────────────

    public function index()
    {
        $files = Auth::user()
            ->files()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($f) => [
                'id'             => $f->id,
                'original_name'  => $f->original_name,
                'mime_type'      => $f->mime_type,
                'size'           => $f->formatted_size,
                'is_public'      => $f->is_public,
                'has_password'   => (bool) $f->password,
'download_count' => $f->download_count,
                'share_token'    => $f->share_token,
                'starred'        => $f->starred,
                'expires_at'     => $f->expires_at?->toISOString(),
                'created_at'     => $f->created_at->diffForHumans(),
            ]);

        return Inertia::render('Files/Index', [
            'files' => $files,
        ]);
    }

    // ─── CREATE ───────────────────────────────────────────────────────────────

    public function create()
    {
        return Inertia::render('Files/Create');
    }

    // ─── STORE ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'file'       => ['required', 'file', 'max:102400'], // 100MB
            'is_public'  => ['boolean'],
            'password'   => ['nullable', 'string', 'min:4'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $uploadedFile = $request->file('file');
        $storedName   = 'files/' . Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();

        Storage::disk('local')->putFileAs('', $uploadedFile, $storedName);

        $file = Auth::user()->files()->create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name'   => $storedName,
            'mime_type'     => $uploadedFile->getMimeType(),
            'size'          => $uploadedFile->getSize(),
            'is_public'     => $request->boolean('is_public'),
            'password'      => $request->password ? Hash::make($request->password) : null,
            'expires_at'    => $request->expires_at,
            'share_token'   => Str::random(32),
        ]);

        return redirect()->route('files.index')
            ->with('success', 'File uploaded successfully!');
    }

    // ─── SHOW ─────────────────────────────────────────────────────────────────



    public function show(File $file)
    {
        $this->authorize('view', $file);

        return Inertia::render('Files/Show', [

            'file' => [
                'id'             => $file->id,
                'original_name'  => $file->original_name,
                'mime_type'      => $file->mime_type,
                'size'           => $file->formatted_size,
                'is_public'      => $file->is_public,
                'has_password'   => (bool) $file->password,
'download_count' => $file->download_count,
                'starred'        => $file->starred,
                'share_token'    => $file->share_token,
                'share_url'      => url('/share/' . $file->share_token),
                'expires_at'     => $file->expires_at?->toISOString(),
                'created_at'     => $file->created_at->toISOString(),
            ],
        ]);
    }

    // ─── EDIT ─────────────────────────────────────────────────────────────────



    public function edit(File $file)
    {
        $this->authorize('update', $file);

        return Inertia::render('Files/Edit', [
            'file' => [
                'id'          => $file->id,
                'original_name' => $file->original_name,
                'is_public'   => $file->is_public,
                'has_password'=> (bool) $file->password,
                'expires_at'  => $file->expires_at?->format('Y-m-d\TH:i'),
            ],
        ]);
    }


    // ─── UPDATE ───────────────────────────────────────────────────────────────



    public function update(Request $request, File $file)
    {
        $this->authorize('update', $file);

        $request->validate([

            'is_public'       => ['boolean'],
            'password'        => ['nullable', 'string', 'min:4'],
            'remove_password' => ['boolean'],
            'expires_at'      => ['nullable', 'date'],
        ]);

        $data = [
            'is_public'  => $request->boolean('is_public'),
            'expires_at' => $request->expires_at ?: null,
        ];

        if ($request->boolean('remove_password')) {
            $data['password'] = null;
        } elseif ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $file->update($data);

        return redirect()->route('files.index')
            ->with('success', 'File updated successfully!');
    }

    // ─── DESTROY ──────────────────────────────────────────────────────────────



    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        Storage::disk('local')->delete($file->stored_name);

        $file->delete();

        return redirect()->route('files.index')
            ->with('success', 'File deleted.');
    }

    // ─── DOWNLOAD ─────────────────────────────────────────────────────────────



    public function download(File $file)
    {
        $this->authorize('view', $file);

        if (! Storage::disk('local')->exists($file->stored_name)) {

            abort(404, 'File not found on disk.');
        }

        $file->increment('download_count');

        return Storage::disk('local')->download(
            $file->stored_name,
            $file->original_name
        );
    }

    // ─── SHARE (public link) ──────────────────────────────────────────────────

    public function share(string $token, Request $request)
    {
        $file = File::where('share_token', $token)->firstOrFail();

        if ($file->isExpired()) {
            return Inertia::render('Files/Expired');
        }

        // Private file — check if owner
        if (! $file->is_public && Auth::id() !== $file->user_id) {
            abort(403);
        }

        // Password check
        if ($file->password) {
            if (! $request->filled('pwd') || ! Hash::check($request->pwd, $file->password)) {
                return Inertia::render('Files/PasswordPrompt', [
                    'token'  => $token,
                    'error'  => $request->filled('pwd') ? 'Incorrect password.' : null,
                ]);
            }
        }

        // Log access
        FileShare::create([
            'file_id'           => $file->id,
            'accessed_by_ip'    => $request->ip(),
            'accessed_by_agent' => substr($request->userAgent() ?? '', 0, 255),
        ]);

        $file->increment('download_count');

        return Storage::disk('local')->download(
            $file->stored_name,
            $file->original_name
        );
    }
}
