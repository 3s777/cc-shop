<?php

namespace App\MoonShine\Resources;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

use Leeto\MoonShine\Actions\ExportAction;
use Leeto\MoonShine\Decorations\Tab;
use Leeto\MoonShine\Decorations\Tabs;
use Leeto\MoonShine\Fields\BelongsTo;
use Leeto\MoonShine\Fields\BelongsToMany;
use Leeto\MoonShine\Fields\Image;
use Leeto\MoonShine\Fields\Text;
use Leeto\MoonShine\Filters\BelongsToFilter;
use Leeto\MoonShine\Filters\BelongsToManyFilter;
use Leeto\MoonShine\Resources\Resource;
use Leeto\MoonShine\Fields\ID;
use Leeto\MoonShine\Decorations\Block;
use Leeto\MoonShine\Actions\FiltersAction;


class ProductResource extends Resource
{
	public static string $model = Product::class;

	public static string $title = 'Product';

    public static array $with = [
        'brand','categories','properties','optionValues'
    ];

	public function fields(): array
	{
        return [
            Block::make('Основное', [
                Tabs::make([
                    Tab::make('Основное', [
                        ID::make()->sortable(),
                        Text::make('Заголовок', 'title')
                            ->showOnExport(),
                        BelongsTo::make('Brand'),
                        Text::make('Цена', 'price', resource: function($item) {
                            return $item->price->raw();
                        }),
                        Image::make('Изображение', 'thumbnail')
                            ->dir('images/products')
                            ->withPrefix('/storage/'),
                    ]),
                    Tab::make('Categories', [
                        BelongsToMany::make('Категории', 'categories', resource: 'title')
                            ->hideOnIndex()
                    ]),
                    Tab::make('Характеристики', [
                        BelongsToMany::make('Properties', resource: 'title')
                            ->fields([
                                Text::make('Value')
                            ])
                            ->hideOnIndex()
                    ]),
                    Tab::make('Опции', [
                        BelongsToMany::make('OptionValues', resource: 'title')
                            ->fields([
                                Text::make('Value')
                            ])
                            ->hideOnIndex()
                    ]),
                ])
            ]),
        ];

	}

	public function rules(Model $item): array
	{
	    return [];
    }

    public function search(): array
    {
        return ['id'];
    }

    public function filters(): array
    {
        return [
            BelongsToFilter::make('brand')->searchable(),
            BelongsToManyFilter::make('categories')
        ];
    }

    public function actions(): array
    {
        return [
            ExportAction::make('Export'),
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}
