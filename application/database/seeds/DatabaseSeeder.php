<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        for ($i = 0 ; $i <= 8; $i++ ) {
            factory(\App\User::class)->create();
        }

        for ($i = 0 ; $i <= 10; $i++ ) {


            factory(\App\Brand::class)->create()->each(function ($Brand){

                factory(\App\Product::class, 5)->create(['brand_id' => $Brand->id])->each(function ($product) {
                    if($product->type == 1){
                        factory(\App\Lens_detail::class)->create(['product_id' => $product->id]);
                    }else if($product->type == 2){
                        factory(\App\Optical_glass_detail::class)->create(['product_id' => $product->id]);
                    }
                });

            });


        }

    }
}
