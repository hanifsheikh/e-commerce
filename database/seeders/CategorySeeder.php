<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        $images = [
            "EVBr3e7UwAAFMRN.0_1.png",
            "image 1.png",
            "Sony-PlayStation-4-500GB-Console-Black 1.png",
            "levant-fhd-t5300-ua43t5300auxtw-frontblack-229857917 1.png",
        ];
        $categories =  [
            "Electronic Devices " => [
                "Smartphones" => [
                    'realme Phones',
                    'Samsung Phones',
                    'OPPO Phones',
                    'Vivo Phones',
                    'Infinix Phones',
                    'Motorola Phones',
                    'Tecno Phones',
                    'Walton Phones',
                    'Nokia Smartphone',
                ],
                "Feature Phone" => [
                    'Walton Feature Phone',
                    'Nokia Feature Phone',
                    'Samsung Feature Phone',
                    'iMAX Feature Phone',
                    '5 Star Feature Phone',
                    'Tinmo Feature Phone',
                ],
                "Tablets" => [],
                "Laptops" => [
                    'Laptops & Notebooks',
                    'Gaming Laptops',
                    'HP Laptops',
                    'Asus Laptops',
                    'Dell Laptops',
                    'Lenovo Laptops',
                    'Walton Laptops',
                    'Acer Laptops',
                    'MSI Laptops'
                ],
                "Desktops" => [
                    'All-In-One',
                    'Gaming Desktops'
                ],
                "Gaming Consoles" => [
                    'PlayStation Consoles',
                    'PlayStation Games',
                    'PlayStation Controllers',
                    'Nintendo Games',
                    'Xbox Games',
                    'Other Gaming Consoles'
                ],
                "Cameras" => [
                    'DSLR',
                    'Mirrorless',
                    'Point & Shoot',
                    'Bridge',
                    'Car Cameras',
                    'Action/Video Cameras'
                ],
                "Security Cameras " => [
                    'IP Security Cameras',
                    'IP Security Systems',
                    'CCTV Security Cameras',
                    'CCTV Security Systems'
                ],
            ],
            "Electronic Accessories" => [
                'Mobile Accessories' => [
                    'Phone Cases',
                    'Power Banks',
                    'Cables & Converters',
                    'Wall Chargers',
                    'Wireless Chargers'
                ],
                'Audio' => [
                    'Headphones & Headset',
                    'Home Entertainment',
                    'Bluetooth Speakers',
                    'Live sound & Stage Equipments'
                ],
                'Wearable' => [
                    'Smartwatches',
                    'Virtual Reality'
                ],
                'Console Accessories' => ['Playstation Controllers', ' Other Gaming Accessories'],
                'Camera Accessories' => [
                    'Memory Cards',
                    'DSLR Lens',
                    'Mirrorless Lens',
                    'Special Camera Lens',
                    'Tripods & Monopods',
                    'Camera Cases Covers and Bags',
                    'Lighting & Studio Equipment',
                    'Dry Box',
                    'Batteries'
                ],
                'Computer Accessories' => [
                    'Monitors',
                    'Mice',
                    'PC Audio',
                    'Keyboards',
                    'Mice & Keyboard Combos',
                    'Power Cord & Adapters'
                ],
                'Storage' => [
                    'External Hard Drives',
                    'Internal Hard Drives',
                    'Flash Drives',
                    'OTG Drives'
                ],
                'Printer' => [
                    'Printers',
                    'Ink & Toners',
                    'Printer Stands',
                    'Fax Machines'
                ],
                'Computer Components' => [
                    'Graphic Cards',
                    'Desktop Casings',
                    'Motherboards',
                    'Fans & Heat-sinks',
                    'RAM',
                    'Processors'
                ],
                'Network Components' => [
                    'Access Points',
                    'Modems',
                    'Network Interface Cards',
                    'Network Switches',
                    'Routers',
                    'Wireless USB Adapters'
                ],
                'Software' => [
                    'Educational Media',
                    'Operating System',
                    'PC Games',
                    'Security Software'
                ],
            ],
            "TV & Home Appliances" => [
                'Televisions' => [
                    'Smart Televisions',
                    'LED Televisions',
                    'OLED Televisions',
                    'Other Televisions',
                    'Walton Televisions',
                    'Samsung Televisions',
                    'Vision Televisions',
                    'Sony Televisions',
                    'Singer Televisions'
                ],
                'Home Audio' => [
                    'Soundbars',
                    'Home Entertainment',
                    'Portable Players',
                    'Live Sound & Stage Equipments'
                ],
                'TV Accessories & Video Devices' => [
                    'TV Receivers',
                    'Projectors',
                    'TV Remote Controllers',
                    'Cables',
                    'Wall Mounts & Protectors',
                    'Blu-Ray/DVD Players'
                ],
                'Large Appliances' => [
                    'Refrigerators',
                    'Freezers',
                    'Washing Machines',
                    'Microwave Oven',
                    'Electric Oven',
                    'Hoods',
                    'Cook-top & Range'
                ],
                'Small Kitchen Appliances' => [
                    'Rice Cooker',
                    'Blender, Mixer & Grinder',
                    'Electric Kettle',
                    'Juicer & Fruit Extraction',
                    'Fryer',
                    'Coffee Machines',
                    'Pressure Cookers',
                    'Sandwich Makers',
                    'Specialty Cookware',
                    'Toasters'
                ],
                'Cooling & Heating' => [
                    'Air Conditioner',
                    'Air Cooler',
                    'Air Purifiers',
                    'Dehumidifiers',
                    'Water Heater'
                ],
                'Fan' => [
                    'Ceiling Fan',
                    'Table Fan',
                    'Stand Fan',
                    'Mini Fan',
                    'Exhaust Fan'
                ],
                'Vacuums & Floor Care' => [
                    'Vacuum Cleaners',
                    'Steam Mops',
                    'Vacuum Cleaner Parts'
                ],
                'Irons & Garment Steamers' => [
                    'Irons',
                    'Sewing Machines'
                ],
                'Water Purifiers & Filters' => [
                    'Pureit Filters',
                    'Kent Filters',
                    'Panasonic Filters',
                    'Eva Pure Filters'
                ]
            ],
            "Health & Beauty" => [
                'Bath & Body' => [
                    'Body & Massage Oils',
                    'Body Moisturizers',
                    'Body Scrubs',
                    'Body Soaps & Shower Gels',
                    'Foot Care',
                    'Hair Removal',
                    'Hand Care',
                    'Sun Care for Body',
                    'Bath & Body Accessories'
                ],
                'Beauty Tools' => [
                    'Curling Irons & Wands',
                    'Flat Irons',
                    'Multi-stylers',
                    'Hair Dryers',
                    'Face Skin Care Tools',
                    'Foot Relief Tools',
                    'Hair Removal Accessories',
                    'Body Slimming & Electric Massagers'
                ],
                'Fragrances' => [
                    'Women Fragrances',
                    'Men Fragrances',
                    'Unisex Fragrances',
                    'Attar'
                ],
                'Hair Care' => [
                    'Shampoo',
                    'Hair Treatments',
                    'Hair Care Accessories',
                    'Hair Brushes & Combs',
                    'Hair Coloring',
                    'Hair Conditioner',
                    'Wig & Hair Extensions & Pads'
                ],
                'Makeup' => [
                    'Face',
                    'Lips',
                    'Eyes',
                    'Nails',
                    'Palettes & Sets',
                    'Brushes & Sets',
                    'Makeup Accessories',
                    'Makeup Removers'
                ],
                'Men’s Care' => [
                    'Deodorants',
                    'Hair Care',
                    'Shaving & Grooming',
                    'Skin Care'
                ],
                'Personal Care' => [
                    'Deodorants',
                    'Feminine Care',
                    'Oral Care',
                    'Personal Safety & Security'
                ],
                'Skin Care' => [
                    'Moisturizers & Creams',
                    'Serum & Essence',
                    'Face Mask & Packs',
                    'Face Scrubs & Exfoliators',
                    'Facial Cleansers',
                    'Lip Balm & Treatments',
                    'Toner & Mists'
                ],
                'Food Supplements' => [
                    'Beauty Supplements',
                    'Multivitamins',
                    'Sports Nutrition',
                    'Well Being',
                    'Sexual Health Vitamins'
                ],
                'Medical Supplies' => [
                    'First Aid Supplies',
                    'Health Accessories',
                    'Health Monitors & Tests',
                    'Injury Support & Braces',
                    'Medical Tests',
                    'Nebulizers & Aspirators',
                    'Ointments & Creams',
                    'Scales & Body Fat Analyzers',
                    'Wheelchairs'
                ],
                'Sexual Wellness' => []
            ],
            "Babies & Toys" => [
                'Mother & Baby' => [],
                'Feeding' => [
                    'Baby & Toddler Foods',
                    'Milk Formula'
                ],
                'Diapering & Potty' => [
                    'Cloth Diapers & Accessories',
                    'Diaper Bags',
                    'Wipes & Holders',
                    'Disposable Diapers'
                ],
                'Baby Gear' => [
                    'Baby Walkers',
                    'Backpacks & Carriers',
                    'Strollers',
                    'Swings, Jumpers & Bouncers'
                ],
                'Baby Personal Care' => [
                    'Baby Bath',
                    'Bathing Tubs &n Seats',
                    'Shampoo & Conditioners',
                    'Soaps, Cleansers & Bodywash'
                ],
                'Clothing & Accessories' => [
                    'Girls Clothing',
                    'Girls Shoes',
                    'Boys Clothing',
                    'Maternity Wear',
                    'New Born Unisex(0-6 months)',
                    'New Born Body Suits',
                    'New Born Sets & Packs'
                ],
                'Nursery' => [
                    'Bathroom Safety',
                    'Mattresses & Bedding',
                    'Sanitizers'
                ],
                'Toys & Games' => [
                    'Action Figures & Collectibles',
                    'Arts & Crafts for Kids',
                    'Ball Pits & Accessories',
                    'Block & Building Toys',
                    'Doll & Accessories',
                    'Dress Up & Pretend Play',
                    'Electronic Toys',
                    'Learning & Education',
                    'Party Supplies',
                    'Puzzle',
                    'Slime & Squishy Toys',
                    'Stuffed Toys'
                ],
                'Baby & Toddler Toys' => [
                    'Activity Gym & Playmates',
                    'Ball',
                    'Bath Toys',
                    'Crib Toys & Attachments',
                    'Early Learning',
                    'Indoor Climbers & Structures',
                    'Push & Pull Toys',
                    'Rocking  & Spring Ride-ons'
                ],
                'Remote Control & Vehicles' => [
                    'Die-Cast Vehicles',
                    'RC Vehicles & Batteries',
                    'Play Trains & Railway Sets',
                    'Play Vehicles',
                    'Drones & Accessories'
                ],
                'Sports & Outdoor Play' => [
                    'Fidget Spinner & Cubes',
                    'Kids Bikes & Accessories',
                    'Swimming Pool & Water Toys',
                    'Outdoor Toys',
                    'Play Tents & Tunnels',
                    'Boxing',
                    'Play Sets & Playground Equipment',
                    'Sports Playground',
                    'Kids Tricycles',
                    'Toys Sports'
                ],
                'Traditional Games' => [
                    'Board Games',
                    'Card Games',
                    'Game Room Games'
                ],
            ],
            "Groceries & Pets" => [
                'Beverages' => [
                    'Coffee',
                    'Hot Chocolate Drinks',
                    'Powdered Drink Mixes'
                ],
                'Breakfast, Choco & Snacks' => [
                    'Biscuit',
                    'Breakfast Cereals',
                    'Chocolate',
                    'Oatmeal'
                ],
                'Foot Staples' => [
                    'Canned Food',
                    'Condiment Dressing',
                    'Grains, Beans & Pulses',
                    'Home Baking & Sugar',
                    'Instant & Ready-to-Eat',
                    'Jarred Food',
                    'Noodles',
                    'Rice'
                ],
                'Cooking Ingredients' => [
                    'Oil',
                    'Herbs & Spices',
                    'Sauce',
                    'Soybean Oil'
                ],
                'Laundry & Household' => [
                    'Air Care',
                    'Cleaning',
                    'Dish washing',
                    'Laundry',
                    'Pest Control'
                ],
                'Cat' => [
                    'Cat Food',
                    'Grooming',
                    'Toys & Accessories',
                    'Litter & Toilet',
                ],
                'Dog' => [
                    'Dog Treats',
                    'Dog Grooming',
                    'Toys & Accessories',
                    'Carriers & Travel',
                    'Dog Food',
                    'Leashes, Collars & Muzzles'
                ],
                'Fish' => [
                    'Aquariums & Accessories',
                    'Fish Food',
                    'Starter Kits'
                ],
                'Bird' => [
                    'Bird Food',
                ],
                'Small Pet' => [
                    'Food & Accessories'
                ],
                'Lifestyle Accessories' => [
                    'Lighters'
                ],
            ],
            "Home & Lifestyle" => [
                'Bath' => [
                    'Bathroom Scales',
                    'Shower Caddies & Hangers',
                    'Shower Curtains',
                    'Towel Rails & Warmers'
                ],
                'Bedding' => [
                    'Blankets & Throws',
                    'Comforters, Quilts & Duvets',
                    'Mattress Pads',
                    'Mattress Protectors',
                    'Pillows & Bolsters'
                ],
                'Decor' => [
                    'Artificial Flowers & Plants',
                    'Candles & Candle Holders',
                    'Clocks',
                    'Curtains',
                    'Cushions & Covers',
                    'Picture Frames',
                    'Rugs & Carpets',
                    'Vases & Vessels'
                ],
                'Furniture' => [
                    'Bedroom',
                    'Living Room',
                    'Home Office'
                ],
                'Kitchen & Dining' => [
                    'Storage & Organization',
                    'Coffee & Tea',
                    'Cookware',
                    'Dinnerware',
                    'Kitchen & Table Linen',
                    'Kitchen Storage & Accessories',
                    'Kitchen Utensils',
                    'Serveware'
                ],
                'Lighting' => [
                    'Ceiling Lights',
                    'Floor Lamps',
                    'Lamp Shades',
                    'Light Bulbs',
                    'Lighting Fixtures & Components',
                    'Outdoor Lighting',
                    'Rechargeable & Flashlights',
                    'Specialty Lighting',
                    'Table Lamps',
                    'Wall Lights & Sconces'
                ],
                'Laundry & Cleaning' => [
                    'Brushes, Sponges & Wipers',
                    'Brooms, Mops & Sweepers',
                    'Laundry Baskets & Hampers',
                    'Clothes Line & Drying Racks',
                    'Ironing Boards'
                ],
                'Tools, DIY & Outdoor' => [
                    'Outdoor',
                    'Fixtures & Plumbing',
                    'Electrical',
                    'Hand Tools',
                    'Power Tools',
                    'Security'
                ],
                'Stationery & Crafts' => [
                    'Gifts & Wrapping',
                    'Packaging & Cartons',
                    'Paper Products',
                    'School & Office Equipment',
                    'Writing & Correction',
                    'Art Supplies',
                    'Craft Supplies',
                    'Sewing',
                    'Religious Items'
                ],
                'Media, Music & Books' => [
                    'eBooks',
                    'Books',
                    'Literature Books',
                    'Education Books',
                    'Religious Books',
                    'Self Help & Meditation Books',
                    'Musical Instruments',
                    'Guitar',
                    'Guitar & Bass Accessories',
                    'Keyboards & Piano',
                    'Ukulele',
                    'Drums & Percussion'
                ],
            ],
            "Women’s Fashion" => [
                'Sweaters & Cardigans' => [],
                'Jackets & Coats' => [],
                'Sarees' => [],
                'Shalwar Kameez' => [],
                'Unstitched Fabric' => [],
                'Kurtis' => [],
                'Clothing' => ["Girl's Fashion"],
                'Women’s Bag' => [
                    'Cross Body & Shoulder Bags',
                    'Coin Purses & Pouches',
                    'Top-Handle Bags'
                ],
                'Shoes' => [
                    'Flat Sandals',
                    'Heels',
                    'Flat Shoes',
                    'Wedges',
                    'Flip Flops',
                ],
                'Accessories' => [
                    'Jewelries',
                    'Belts',
                    'Hair Accessories',
                    'Scarves',
                    'Umbrellas'
                ],
                'Lingerie, Sleep & Lounge' => [
                    'Bras',
                    'Panties',
                    'Lingerie Sets',
                    'Sleep & Lounge wear',
                    'Thermal & Shape wear',
                    'Tank Tops & Slips'
                ],
                'Travel & Luggage' => [
                    'Weekender Bags',
                    'Laptop Backpacks',
                    'Suitcases',
                    'Travel Accessories'
                ],
            ],
            "Men’s Fashion" => [
                'Jackets & Coats' => [
                    'Bomber Jackets',
                    'Denim Jackets',
                    'Leather Jackets',
                    'Rain Coats & Trenches'
                ],
                'Hoodies & Sweatshirts' => [],
                'Sweaters' => [],
                'T-Shirts' => [],
                'Shirts' => [],
                'Polo Shirts' => [],
                'Jeans' => [],
                'Pant' => [
                    'Cargo',
                    'Joggers & Sweatpants',
                    'Shorts & Bermudas'
                ],
                'Men’s Bags' => [
                    'Backpack',
                    'Suitcases',
                    'Weekender Bags',
                    'Business Bags',
                    'Messenger Bags',
                    'Cross-body bags',
                    'Travel bags'
                ],
                'Shoes' => [
                    'Sneakers',
                    'Sandals',
                    'Formal Shoes',
                    'Boots',
                    'Flip Flops',
                    'Slip-Ons & Loafers',
                    'House Slippers',
                    'Boy’s Shoes'
                ],
                'Accessories' => [
                    'Belt',
                    'Wallet',
                    'Hats & Caps',
                    'Ties & Bow Ties',
                    'Gloves',
                    'Umbrellas'
                ],
                'Clothing' => [
                    'Panjabi & Fatua',
                    'Suits',
                    'Underwear',
                    'Boy’s Clothing'
                ],
            ],
            "Watches & Accessories" => [
                'Men’s Watch' => [
                    'Casual',
                    'Business',
                    'Fashion',
                    'Sport'
                ],
                'Women’s Watch' => [
                    'Casual',
                    'Business',
                    'Fashion',
                ],
                'Women’s Jewelries' => [
                    'Rings',
                    'Necklaces',
                    'Pendants',
                    'Earrings',
                    'Jewelry Sets',
                    'Bracelets'
                ],
                'Men’s Jewelries' => [
                    'Rings',
                    'Necklaces & Pendants',
                    'Bracelets'
                ],
                'Fashion Mask' => [],
                'Men’s Belt' => [],
                'Men’s Wallet' => [],
                'Sunglasses' => [
                    'Men Sunglasses',
                    'Women Sunglasses',
                    'Kids Sunglasses'
                ],
                'Eyeglasses' => [
                    'Men Eyeglasses',
                    'Women Eyeglasses'
                ],
                'Kid’s Watch' => []
            ],
            "Sports & Outdoor" => [
                'Treadmills' => [],
                'Fitness Accessories' => [],
                'Dumbbells' => [],
                'Cycling' => [
                    'Bicycle',
                    'Bicycle Accessories'
                ],
                'Boxing, Martial Arts & MMA' => [
                    'Boxing Gloves',
                    'Boxing Protective gear',
                    'Martial Art Equipment',
                    'Punching Bags & Accessories'
                ],
                'Shoes & Clothing' => [
                    'Clothing',
                    'Shoes',
                    'Accessories',
                    'Bags'
                ],
                'Outdoor Recreation' => [
                    'Golf',
                    'Fishing',
                    'Skateboards',
                    'Water Sports'
                ],
                'Exercise & Fitness' => [
                    'Exercise Bikes',
                    'Elliptical Trainers',
                    'Strength Training Equipment'
                ],
                'Racket Sports' => [
                    'Badminton',
                    'Squash'
                ],
                'Team Sports' => [
                    'Football',
                    'Cricket',
                    'Basketball',
                    'Volleyball'
                ],
                'Camping & Hiking' => [
                    'Tents',
                    'Sleeping Bags',
                    'Cooking Essentials',
                    'Hiking Backpacks'
                ],
                'Fanshop' => []
            ],
            "Automotive & Motorbike" => [
                'Automobile' => [
                    'Imported Cars',
                    'Sedans',
                    'SUVs',
                    'Trucks'
                ],
                'Auto Oils & Fluids' => [
                    'Additives',
                    'Transmission Fluids'
                ],
                'Interior Accessories' => [
                    'Seat Covers & Accessories',
                    'Floor Mats & Cargo Liners',
                    'Air Fresheners'
                ],
                'Exterior Accessories' => [
                    'Covers'
                ],
                'Exterior Vehicle Care' => [
                    'Car Polishes & Waxes'
                ],
                'Interior Vehicle Care' => [
                    'Vacuums'
                ],
                'Car Electronics Accessories' => [],
                'Car Audio' => [
                    'Speakers'
                ],
                'Motorcycle' => [
                    'Scooters',
                    'Standard Bikes'
                ],
                'Motorcycle Parts & Accessories' => [
                    'Driver-train & Transmission',
                    'Oils & Fluids',
                    'Tools & Maintenance'
                ],
                'Motorcycle Riding Gear' => [
                    'Helmet',
                    'Gloves',
                    'Eyewear'
                ]
            ],

        ];
        foreach ($categories as $parent => $childrens) {
            $inserted_parent = Category::create([
                'category_name' => $parent,
                'category_url' => Str::slug($parent),
                'category_image' => $images[rand(0, 3)],
                'category_thumbnail' => $images[rand(0, 3)],
            ]);
            foreach ($childrens as $second_level_parent => $last_level_childrens) {
                $inserted_second_level_parent = Category::create([
                    'category_name' => $second_level_parent,
                    'category_url' => Str::slug($parent) . '/' .  Str::slug($second_level_parent),
                    'category_image' => "no_image.png",
                    'category_thumbnail' => "no_image.png",
                    'parent_id' => $inserted_parent->id
                ]);
                foreach ($last_level_childrens as $last_level_child) {
                    Category::create([
                        'category_name' => $last_level_child,
                        'category_url' => Str::slug($parent) . '/' .  Str::slug($second_level_parent) . '/' . Str::slug($last_level_child),
                        'category_image' => "no_image.png",
                        'category_thumbnail' => "no_image.png",
                        'parent_id' => $inserted_second_level_parent->id
                    ]);
                }
            }
        }
        $this->refreshCache();
    }
    public function refreshCache()
    {
        // Fetch All categories with their childrens
        $categories = Category::with(['childrens' => function ($query) {
            return $query->with(['childrens' => function ($q) {
                return $q->with('parent')->get();
            }, 'parent'])->get();
        }])->where('parent_id', null)->get();

        // Parent Array 
        $parents = [];

        // Loop through each categories and put in the $parents[] Array by key => value pair.
        foreach ($categories as $category) {
            $parents[$category->category_name] = [
                'id' => $category->id,
                'category_image' => $category->category_image,
                'category_url' => $category->category_url,
                'category_thumbnail' => $category->category_thumbnail,
                'childrens' => $category->childrens,
            ];
        }
        //Sort Parents Array by Key.
        ksort($parents);

        // Put the Array elements into 'category_caches' table this will load every time user requests. 
        DB::table('category_caches')->truncate();
        foreach ($parents as $parent => $childrens) {
            CategoryCache::create([
                'category_name' => $parent,
                'category_url' => $childrens['category_url'],
                'id' => $childrens['id'],
                'category_image' => $childrens['category_image'],
                'category_thumbnail' => $childrens['category_thumbnail'],
                'childrens' => $childrens['childrens']
            ]);
        }
    }
}
