<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Category;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $baseServices = [
            'venues' => [
                'Elegant Hall',
                'Grand Ballroom',
                'Open Garden',
                'Luxury Venue',
                'Event Space',
            ],
            'professionals' => [
                'Wedding Photographer',
                'Event Planner',
                'DJ Services',
                'Videographer',
                'Makeup Artist',
            ],
            'transport' => [
                'Luxury Van',
                'Mini Bus',
                'Limousine',
                'Airport Shuttle',
                'Motorbike Rental',
            ],
            'catering' => [
                'Filipino Buffet',
                'International Cuisine',
                'Dessert Table',
                'Beverage Service',
                'Snack Platters',
            ],
            'rental' => [
                'Audio Equipment',
                'Lighting Setup',
                'Projector & Screen',
                'Chair & Table Set',
                'Stage Decor',
            ],
            'coaching' => [
                'Leadership Training',
                'Photography Workshop',
                'Cooking Class',
                'Fitness Coaching',
                'Public Speaking',
            ],
            'cleaning' => [
                'Event Cleanup',
                'Venue Sanitation',
                'Trash Management',
                'Carpet Cleaning',
                'Table & Chair Cleaning',
            ],
            'security' => [
                'Event Security Guard',
                'Crowd Control',
                'VIP Protection',
                'Parking Assistance',
                'Overnight Security',
            ],
        ];

        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) { 
                $baseName = $baseServices[$category->slug][array_rand($baseServices[$category->slug])];
                $name = $baseName . " #" . $i; 

                Service::create([
                    'category_id'   => $category->id,
                    'provider_id'   => rand(1, 40),
                    'name'          => $name,
                    'description'   => "This is a description for $name. Lorem ipsum dolor sit amet.",
                    'price'         => rand(1000, 10000),
                    'duration'      => rand(1, 8),
                    'status'        => ['available','unavailable','inactive'][rand(0,2)],
                    'rating'        => rand(30, 50)/10,    
                    'reviews_count' => rand(0, 100),
                    'address'       => "123 Example St, " . ucfirst($category->slug),
                    'latitude'      => rand(10000000, 19999999)/1000000,
                    'longitude'     => rand(10000000, 19999999)/1000000,
                ]);
            }
        }

        $totalServices = $categories->count() * 25;
        $this->command->info("Seeding completed: $totalServices services created (25 per category).");
    }
}
