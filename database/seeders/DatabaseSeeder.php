<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\product;
use App\Models\category;
use App\Models\ingredient;
use App\Models\recipes;
use App\Models\customers;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'user',
            'role'  => 'owner'
        ]);

        category::insert([
            'name'  => 'Minuman Kopi',
            'slug'  => Str::slug('Minuman Kopi'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        product::insert([
            'name'  => 'Kopi Susu',
            'slug'  => Str::slug('Kopi Susu'),
            'sku'   => 'SKU-01',
            'description'=> 'Lorem Ipsum Dolor Simet Amet',
            'price' => 12000,
            'category_id'=> 1,
            'is_available'=> 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        ingredient::insert([
            'name'    => 'Kopi Robusta',
            'slug'     => Str::slug('Kopi Robusta'),
            'stock_quantity' => 100,
            'unit'  => 'gram',
            'low_stock_threshold'=> 10,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        recipes::insert([
            'product_id'=>1,
            'ingredient_id'=>1,
            'quantity_needed'=> 50,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        customers::insert([
            'name'  => 'steven',
            'slug'=> 'steven',
            'phone_number' => '6281999967373',
            'email' => 'steven@mail.com',
            'birth_date'    => Carbon::now()->format('Y-m-d'),
            'loyalty_tier' => 'gold',
            'loyality_points' => 100,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
