<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {

        $categories = Category::query()
            ->select(['id', 'title', 'slug'])
            ->has('products')
            ->get();
        // Scout
//        $products = Product::search()->query(function (Builder $query) use ($category) {
//            $query->select(['id', 'title', 'slug', 'price', 'thumbnail', 'brand_id'])
//                ->when($category->exists, function (Builder $query) use ($category)
//                {
//                    $query->whereRelation(
//                        'categories',
//                        'categories.id',
//                        '=',
//                        $category->id
//                    );
//                })
//                ->filtered()
//                ->sorted();
//        })
//            ->paginate(6);


        $products = Product::query()
            ->select(['id', 'title', 'slug', 'price', 'thumbnail', 'brand_id'])
            ->when(request('s'), function (Builder $query) {
                $query->whereFullText(['title', 'text'], request('s'));
            })
            ->when($category->exists, function (Builder $query) use ($category)
            {
                $query->whereRelation(
                    'categories',
                    'categories.id',
                    '=',
                    $category->id
                );
            })
            ->filtered()
            ->sorted()
            ->paginate(6);

//        $products->each(function ($product) use ($brands) {
//            $product->setRelation('rel_brand', $brands->find($product->brand_id));
//        });

        return view('catalog.index', [
           'products' => $products,
           'categories' => $categories,
           'category' => $category
        ]);
    }
}
