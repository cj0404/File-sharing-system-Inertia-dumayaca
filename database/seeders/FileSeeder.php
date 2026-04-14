<?php

namespace Database\Seeders;

use App\Models\File;
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

        // Mock file data for Alice
        $aliceFiles = [
            [
                'original_name' => 'project-proposal.pdf',
                'stored_name'   => 'files/' . Str::uuid() . '.pdf',
                'mime_type'     => 'application/pdf',
                'size'          => 204800,
                'is_public'     => true,
                'share_token'   => Str::random(32),
                'download_count'=> 12,
            ],
            [
                'original_name' => 'budget-2024.xlsx',
                'stored_name'   => 'files/' . Str::uuid() . '.xlsx',
                'mime_type'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'size'          => 51200,
                'is_public'     => false,
                'share_token'   => Str::random(32),
                'download_count'=> 3,
            ],
            [
                'original_name' => 'team-photo.jpg',
                'stored_name'   => 'files/' . Str::uuid() . '.jpg',
                'mime_type'     => 'image/jpeg',
                'size'          => 1048576,
                'is_public'     => true,
                'share_token'   => Str::random(32),
                'download_count'=> 45,
            ],
        ];

        foreach ($aliceFiles as $f) {
            File::create(array_merge($f, ['user_id' => $alice->id]));
        }

        // Mock file data for Bob
        $bobFiles = [
            [
                'original_name' => 'design-mockup.png',
                'stored_name'   => 'files/' . Str::uuid() . '.png',
                'mime_type'     => 'image/png',
                'size'          => 2097152,
                'is_public'     => true,
                'share_token'   => Str::random(32),
                'download_count'=> 8,
            ],
            [
                'original_name' => 'readme.txt',
                'stored_name'   => 'files/' . Str::uuid() . '.txt',
                'mime_type'     => 'text/plain',
                'size'          => 1024,
                'is_public'     => true,
                'share_token'   => Str::random(32),
                'download_count'=> 21,
            ],
        ];

        foreach ($bobFiles as $f) {
            File::create(array_merge($f, ['user_id' => $bob->id]));
        }
    }
}
