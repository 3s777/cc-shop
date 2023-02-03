<?php


namespace App\Traits\Models;


use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static int $slugCounter = 0;

    public static string $slugPrefix = '';

    protected static function bootHasSlug()
    {
        static::creating(function (Model $item) {
            $item->slug = $item->slug
                ?? str($item->{self::slugFrom()})->slug();
            $item->slug = str($item->slug)->append(self::isSlugUnique($item->slug));
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    public static function isSlugUnique(string $slug, string $newSlug = null): string
    {
        $finalSlugPrefix = '';
        $checkingSlug = ($newSlug) ? $newSlug : $slug;
        $notUniqueSlug = self::query()->where('slug', $checkingSlug)->first();

        if($notUniqueSlug) {
            self::$slugCounter++;
            self::$slugPrefix = '_'.self::$slugCounter;
            self::isSlugUnique($slug, $slug . '_'.self::$slugCounter);

            $finalSlugPrefix = self::$slugPrefix;
        }

        self::$slugCounter = 0;
        return $finalSlugPrefix;
    }
}
