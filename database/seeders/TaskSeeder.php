<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $statuses = ['open', 'in_progress', 'completed'];

        // Get all user IDs
        $userIds = User::pluck('id')->toArray();

        foreach ($statuses as $status) {
            for ($i = 0; $i < 5; $i++) {
                Task::create([
                    'created_by'  => $faker->randomElement($userIds), // random user ID
                    'title'       => $faker->sentence(),   // random title
                    'description' => $faker->paragraph(),   // random text paragraph
                    'status'      => $status,
                ]);
            }
        }
    }
}