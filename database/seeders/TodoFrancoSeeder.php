<?php
namespace Database\Seeders;

use App\Models\TodoFranco;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TodoFrancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $user = TodoFranco::create(
        [
            'name' => 'Franco',
            'email' => 'onidesk@outlook.com',
            'age'  => '44',
        ]
       );
    }
}
