<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Venues & Spaces',
                'slug' => 'venues',
                'icon' => 'Home',
                'color' => 'text-red-500',
                'sort_order' => 1,
            ],
            [
                'name' => 'Professionals',
                'slug' => 'professionals',
                'icon' => 'Camera',
                'color' => 'text-green-500',
                'sort_order' => 2,
            ],
            [
                'name' => 'Transport & Travel',
                'slug' => 'transport',
                'icon' => 'Bike',
                'color' => 'text-purple-500',
                'sort_order' => 3,
            ],
            [
                'name' => 'Food & Catering',
                'slug' => 'catering',
                'icon' => 'Soup',
                'color' => 'text-orange-500',
                'sort_order' => 4,
            ],
            [
                'name' => 'Rental & Equip',
                'slug' => 'rental',
                'icon' => 'Wrench',
                'color' => 'text-blue-500',
                'sort_order' => 5,
            ],
            [
                'name' => 'Coaching & Skills',
                'slug' => 'coaching',
                'icon' => 'GraduationCap',
                'color' => 'text-pink-500',
                'sort_order' => 6,
            ],
            [
                'name' => 'Cleaning Services',
                'slug' => 'cleaning',
                'icon' => 'Wrench',
                'color' => 'text-teal-500',
                'sort_order' => 7,
            ],
            [
                'name' => 'Security',
                'slug' => 'security',
                'icon' => 'GraduationCap',
                'color' => 'text-lime-500',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']], 
                [
                    'name'       => $category['name'],
                    'icon'       => $category['icon'],
                    'color'      => $category['color'],
                    'sort_order' => $category['sort_order'],
                    'is_active'  => true,
                ]
            );
        }
    }
}
