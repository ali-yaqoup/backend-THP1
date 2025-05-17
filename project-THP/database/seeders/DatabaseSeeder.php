<?php




namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // قم بإضافة بيانات المستخدم
        User::factory(10)->create();
    }
}
