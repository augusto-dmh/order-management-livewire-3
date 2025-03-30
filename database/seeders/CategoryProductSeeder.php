<?php
// filepath: database/seeders/CategoryProductSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    public function run()
    {
        $productsIds = Product::pluck('id')->toArray();
        $categoriesIds = Category::pluck('id')->toArray();

        foreach ($productsIds as $productId) {
            $categoriesOffset = rand(0, count($categoriesIds) - 1);
            $numberOfCategoriesToAssociateToProduct = rand($categoriesOffset, count($categoriesIds) - 1);
            shuffle($categoriesIds);

            $selectedCategories = array_slice($categoriesIds, $categoriesOffset, $numberOfCategoriesToAssociateToProduct);

            foreach ($selectedCategories as $categoryId) {
                $categoryProductRecords[] = [
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                ];
            }
        }

        DB::table('category_product')->insertOrIgnore($categoryProductRecords);
    }
}
