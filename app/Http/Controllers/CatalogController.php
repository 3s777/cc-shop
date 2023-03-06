<?php

namespace App\Http\Controllers;

use App\View\ViewModels\CatalogViewModel;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): CatalogViewModel
    {

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




//        $products->each(function ($product) use ($brands) {
//            $product->setRelation('rel_brand', $brands->find($product->brand_id));
//        });

//        return view('catalog.index', new CatalogViewModel($category));


        return (new CatalogViewModel($category))
            ->view('catalog.index');
    }
}
