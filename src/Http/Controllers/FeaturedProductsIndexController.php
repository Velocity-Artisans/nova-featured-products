<?php

namespace VelocityArtisans\FeaturedProducts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VelocityArtisans\ApiWrapper\ApiWrapper;
use VelocityArtisans\FeaturedProducts\Models\FeaturedProduct;

class FeaturedProductsIndexController extends Controller
{
    public function handle()
    {
        $api = new ApiWrapper;

        $products = collect($api->call('get', 'products', ['paginate' => false, 'sort_by_field' => 'title', 'sort_by_dir' => 'asc'])['data'] ?? []);
        $featured = FeaturedProduct::all();

        if ($products->isNotEmpty()) {
            $products = $products->map(function ($product) use ($featured) {
                $filtered = $featured->filter(function ($p) use ($product) {
                    return $p->product_id === $product['id'];
                });

                $product['isFeatured'] = $filtered->isNotEmpty();

                return $product;
            });
        }

        return compact('products');
    }

    public function update(Request $request)
    {
        if ($request->products) {
            FeaturedProduct::truncate();

            foreach ($request->products as $product) {
                if ($product['isFeatured']) {
                    FeaturedProduct::create(['product_id' => $product['id']]);
                }
            }

            return response()->json([], 200);
        }

        return response()->json([], 500);
    }
}
