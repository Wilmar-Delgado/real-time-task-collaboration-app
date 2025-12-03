<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userIds = User::pluck('id')->toArray();
        $taskIds = Task::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            Comment::create([
                'user_id' => $faker->randomElement($userIds),
                'task_id' => $faker->randomElement($taskIds),
                'body'    => $faker->paragraph(),
            ]);
        }
    }
}
