<?php

namespace App\View\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class CatalogViewModel extends ViewModel
{
    public function __construct(
        public Category $category
    )
    {
    }

    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->select(['id', 'title', 'slug', 'price', 'thumbnail', 'brand_id', 'json_properties'])
            ->search()
            ->withCategory($this->category)
            ->filtered()
            ->sorted()
            ->paginate(6);
    }

    public function categories(): array|Collection
    {
        return Category::query()
            ->select(['id', 'title', 'slug'])
            ->has('products')
            ->get();
    }
}
