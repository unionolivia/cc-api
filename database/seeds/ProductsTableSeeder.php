<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Product::create([
            'name'          =>  'Root',
            'category_id' => null,
            'price'   =>  200.00,
            'img' => null
        ]);

        factory('App\Product', 50)->create();
    }
}
