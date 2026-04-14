<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\File;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $alice = User::firstOrCreate(
            ['email' => 'alice@demo.com'],
            [
                'name' => 'Alice Santos',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $bob = User::firstOrCreate(
            ['email' => 'bob@demo.com'],
            [
                'name' => 'Bob Reyes',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create tags for Alice
        $aliceTags = [
            Tag::firstOrCreate(['user_id' => $alice->id, 'name' => 'Work'], ['color' => 'blue']),
            Tag::firstOrCreate(['user_id' => $alice->id, 'name' => 'Personal'], ['color' => 'green']),
            Tag::firstOrCreate(['user_id' => $alice->id, 'name' => 'Projects'], ['color' => 'purple']),
            Tag::firstOrCreate(['user_id' => $alice->id, 'name' => 'Important'], ['color' => 'red']),
        ];

        // Create tags for Bob
        $bobTags = [
            Tag::firstOrCreate(['user_id' => $bob->id, 'name' => 'Design'], ['color' => 'pink']),
            Tag::firstOrCreate(['user_id' => $bob->id, 'name' => 'Development'], ['color' => 'indigo']),
            Tag::firstOrCreate(['user_id' => $bob->id, 'name' => 'Review'], ['color' => 'yellow']),
        ];

        // Mock file data for Alice
        $aliceFiles = [
            [
                'original_name' => 'Q1-project-proposal.pdf',
                'stored_name'   => 'files/' . Str::uuid() . '.pdf',
                'mime_type'     => 'application/pdf',
                'size'          => 204800,
                'is_public'     => true,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 42,
                'starred'       => true,
            ],
            [
                'original_name' => 'budget-2024.xlsx',
                'stored_name'   => 'files/' . Str::uuid() . '.xlsx',
                'mime_type'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'size'          => 512000,
                'is_public'     => false,
                'password'      => bcrypt('secret123'),
                'share_token'   => Str::random(32),
                'download_count'=> 8,
                'starred'       => true,
            ],
            [
                'original_name' => 'team-meeting-photo.jpg',
                'stored_name'   => 'files/' . Str::uuid() . '.jpg',
                'mime_type'     => 'image/jpeg',
                'size'          => 3145728,
                'is_public'     => true,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 125,
                'starred'       => false,
            ],
            [
                'original_name' => 'marketing-strategy.docx',
                'stored_name'   => 'files/' . Str::uuid() . '.docx',
                'mime_type'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'size'          => 256000,
                'is_public'     => false,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 15,
                'starred'       => true,
            ],
            [
                'original_name' => 'presentation-slides.pptx',
                'stored_name'   => 'files/' . Str::uuid() . '.pptx',
                'mime_type'     => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'size'          => 1024000,
                'is_public'     => true,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 67,
                'starred'       => false,
            ],
            [
                'original_name' => 'data-report-2024.csv',
                'stored_name'   => 'files/' . Str::uuid() . '.csv',
                'mime_type'     => 'text/csv',
                'size'          => 102400,
                'is_public'     => false,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 23,
                'starred'       => true,
            ],
        ];

        foreach ($aliceFiles as $f) {
            $file = File::create(array_merge($f, ['user_id' => $alice->id]));
            // Attach random tags
            $tagIds = collect($aliceTags)->random(2)->pluck('id')->toArray();
            $file->tags()->attach($tagIds);
            // Add comments
            Comment::create([
                'file_id' => $file->id,
                'user_id' => $alice->id,
                'content' => 'This is important - reviewed and approved',
            ]);
        }

        // Mock file data for Bob
        $bobFiles = [
            [
                'original_name' => 'ui-design-mockup.png',
                'stored_name'   => 'files/' . Str::uuid() . '.png',
                'mime_type'     => 'image/png',
                'size'          => 4194304,
                'is_public'     => true,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 34,
                'starred'       => true,
            ],
            [
                'original_name' => 'API-documentation.md',
                'stored_name'   => 'files/' . Str::uuid() . '.md',
                'mime_type'     => 'text/markdown',
                'size'          => 51200,
                'is_public'     => true,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 89,
                'starred'       => false,
            ],
            [
                'original_name' => 'source-code-backup.zip',
                'stored_name'   => 'files/' . Str::uuid() . '.zip',
                'mime_type'     => 'application/zip',
                'size'          => 10485760,
                'is_public'     => false,
                'password'      => bcrypt('backup2024'),
                'share_token'   => Str::random(32),
                'download_count'=> 5,
                'starred'       => true,
            ],
            [
                'original_name' => 'screenshot-error-log.png',
                'stored_name'   => 'files/' . Str::uuid() . '.png',
                'mime_type'     => 'image/png',
                'size'          => 614400,
                'is_public'     => false,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 12,
                'starred'       => false,
            ],
            [
                'original_name' => 'wireframes-website.pdf',
                'stored_name'   => 'files/' . Str::uuid() . '.pdf',
                'mime_type'     => 'application/pdf',
                'size'          => 819200,
                'is_public'     => true,
                'password'      => null,
                'share_token'   => Str::random(32),
                'download_count'=> 56,
                'starred'       => false,
            ],
        ];

        foreach ($bobFiles as $f) {
            $file = File::create(array_merge($f, ['user_id' => $bob->id]));
            // Attach random tags
            $tagIds = collect($bobTags)->random(2)->pluck('id')->toArray();
            $file->tags()->attach($tagIds);
            // Add comments
            Comment::create([
                'file_id' => $file->id,
                'user_id' => $bob->id,
                'content' => 'Ready for review - please check and provide feedback',
            ]);
        }
    }
}
