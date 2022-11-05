<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sellers')->truncate();
        \App\Models\Seller::factory(10)->create();
        DB::table('sellers')->insert([
            'name' => 'محمد حنيف',
            'email' => 'hanif@ecommerce.com',
            'logo' => 'star_tech.jpg',
            'company_name' => "GenexMart",
            'shop_slug' => "genexmart",
            'owner_address' => 'Dona, Kalikabari, Morrelgonj, Bagerhat',
            'company_address' => 'Dona, Kalikabari, Morrelgonj, Bagerhat',
            'url' => 'https://hanifsheikh.github.io',
            'contact_no' => '01725467151',
            'email_verified_at' => now(),
            'documents_submitted_at' => now(),
            'documents_approved_at' => now(),
            'selling_products' => 'shoes, panties, bags, honey, ac, fridge, tv, mobile',
            'avatar' => 'hanif.jpg',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'active' => true
        ]);
        DB::table('sellers')->insert([
            'name' =>  'Muhaymen Hridoy',
            'email' => 'muhaymen@ecommerce.com',
            'logo' => 'star_tech.jpg',
            'company_name' => "Friend's E-commerce",
            'shop_slug' => "friends-e-commerce",
            'owner_address' => 'Donia, Jatrabari, Dhaka',
            'company_address' => 'Donia, Jatrabari, Dhaka',
            'url' => 'https://web.facebook.com/muheymen.hridoy',
            'contact_no' => '01515200545',
            'email_verified_at' => now(),
            'documents_submitted_at' => now(),
            'documents_approved_at' => now(),
            'selling_products' => 'shoes, panties, bags, honey, ac, fridge, tv, mobile',
            'avatar' => 'hridoy.jpg',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'active' => true
        ]);
    }
}
