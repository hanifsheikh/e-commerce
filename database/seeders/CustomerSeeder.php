<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->truncate();
        \App\Models\Customer::factory(10)->create();
        DB::table('customers')->insert([
            'name' => 'محمد حنيف',
            'email' => 'hanif@ecommerce.com',
            'contact' => '01725467151',
            'email_verified_at' => now(),
            'avatar' =>  '/storage/customer_images/' . 'hanif.jpg',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        DB::table('customers')->insert([
            'name' => 'Muhaymen Hridoy',
            'email' => 'muhaymen@ecommerce.com',
            'contact' => '01515200545',
            'email_verified_at' => now(),
            'avatar' =>  '/storage/customer_images/' . 'hridoy.jpg',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }
}
