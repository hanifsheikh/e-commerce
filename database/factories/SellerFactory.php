<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SellerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Seller::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $logos = [
            'sony.jpg',
            'star_tech.jpg',
            'ryans.jpg'
        ];
        $avatars = [
            'avatar_default.jpg'
        ];
        $company_name = $this->faker->company();
        $shop_slug = Str::slug($company_name, '-');
        return [
            'name' => $this->faker->name(),
            'company_name' => $company_name,
            'shop_slug' => $shop_slug,
            'email' => $this->faker->unique()->safeEmail(),
            'logo' => $logos[rand(0, 2)],
            'owner_address' => $this->faker->address(),
            'company_address' => $this->faker->address(),
            'url' => $this->faker->url(),
            'contact_no' => $this->faker->tollFreePhoneNumber(),
            'alternative_contact_no' => $this->faker->tollFreePhoneNumber(),
            'email_verified_at' => now(),
            'documents_submitted_at' => now(),
            'documents_approved_at' => now(),
            'selling_products' => 'shoes, panties, bags, honey, ac, fridge, tv, mobile',
            'active' => true,
            'avatar' => $avatars[rand(0, count($avatars) - 1)],
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
