<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create main categories
        $mainCategories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Clothing',
                'description' => 'Men\'s, women\'s, and children\'s apparel',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Furniture, appliances, and home decor',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Books',
                'description' => 'Fiction, non-fiction, and educational books',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment and outdoor gear',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        // Create main categories
        foreach ($mainCategories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'is_active' => $category['is_active'],
                'sort_order' => $category['sort_order'],
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }

        // Create subcategories
        $subcategories = [
            // Electronics subcategories
            [
                'name' => 'Smartphones',
                'description' => 'Mobile phones and accessories',
                'parent_name' => 'Electronics',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Laptops',
                'description' => 'Laptops and notebook computers',
                'parent_name' => 'Electronics',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Tablets',
                'description' => 'Tablet computers and accessories',
                'parent_name' => 'Electronics',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Clothing subcategories
            [
                'name' => 'Men\'s',
                'description' => 'Men\'s clothing and accessories',
                'parent_name' => 'Clothing',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Women\'s',
                'description' => 'Women\'s clothing and accessories',
                'parent_name' => 'Clothing',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Children\'s',
                'description' => 'Children\'s clothing and accessories',
                'parent_name' => 'Clothing',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Home & Kitchen subcategories
            [
                'name' => 'Furniture',
                'description' => 'Home furniture for all rooms',
                'parent_name' => 'Home & Kitchen',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Appliances',
                'description' => 'Home and kitchen appliances',
                'parent_name' => 'Home & Kitchen',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Decor',
                'description' => 'Home decoration items',
                'parent_name' => 'Home & Kitchen',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Books subcategories
            [
                'name' => 'Fiction',
                'description' => 'Novels, short stories, and literature',
                'parent_name' => 'Books',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Biographies, history, and educational books',
                'parent_name' => 'Books',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Children\'s Books',
                'description' => 'Books for children and young adults',
                'parent_name' => 'Books',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Sports & Outdoors subcategories
            [
                'name' => 'Fitness',
                'description' => 'Fitness equipment and accessories',
                'parent_name' => 'Sports & Outdoors',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Camping & Hiking',
                'description' => 'Camping and hiking gear',
                'parent_name' => 'Sports & Outdoors',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Team Sports',
                'description' => 'Equipment for team sports',
                'parent_name' => 'Sports & Outdoors',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        // Create subcategories with parent references
        foreach ($subcategories as $subcategory) {
            $parent = Category::where('name', $subcategory['parent_name'])->first();
            
            if ($parent) {
                Category::create([
                    'name' => $subcategory['name'],
                    'description' => $subcategory['description'],
                    'parent_id' => $parent->id,
                    'is_active' => $subcategory['is_active'],
                    'sort_order' => $subcategory['sort_order'],
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}