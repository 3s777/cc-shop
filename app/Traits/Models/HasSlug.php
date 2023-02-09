<?php


namespace App\Traits\Models;


use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    // TODO Delete
//    public static int $slugCounter = 0;
//
//    public static string $slugPrefix = '';
//
//    protected static function bootHasSlug()
//    {
//        static::creating(function (Model $item) {
//            $item->slug = $item->slug
//                ?? str($item->{self::slugFrom()})->slug();
//            $item->slug = str($item->slug)->append(self::isSlugUnique($item->slug));
//        });
//    }
//
//    public static function slugFrom(): string
//    {
//        return 'title';
//    }
//
//    public static function isSlugUnique(string $slug, string $newSlug = null): string
//    {
//        $finalSlugPrefix = '';
//        $checkingSlug = ($newSlug) ? $newSlug : $slug;
//        $notUniqueSlug = self::query()->where('slug', $checkingSlug)->first();
//
//        if($notUniqueSlug) {
//            self::$slugCounter++;
//            self::$slugPrefix = '_'.self::$slugCounter;
//            self::isSlugUnique($slug, $slug . '_'.self::$slugCounter);
//
//            $finalSlugPrefix = self::$slugPrefix;
//        }
//
//        self::$slugCounter = 0;
//        return $finalSlugPrefix;
//    }

    protected static function bootHasSlug()
    {
        static::creating(function (Model $item) {
            $item->makeSlug();
        });
    }

    protected function makeSlug()
    {
        if(!$this->{$this->slugColumn()}) {
            $slug = $this->slugUnique(
                str($this->{$this->slugFrom()})
                    ->slug()
                    ->value()
            );
        } else {
            $slug = $this->slugUnique(
                $this->{$this->slugColumn()}
            );
        }

        $this->{$this->slugColumn()} = $slug;
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }

    protected function slugFrom(): string
    {
        return 'title';
    }

    private function slugUnique(string $slug): string
    {
        $orginalSlug = $slug;
        $i = 0;

        while ($this->isSlugExists($slug)) {
            $i++;

            $slug = $orginalSlug.'-'.$i;
        }

        return $slug;
    }

    private function isSlugExists(string $slug): bool
    {
        $query = $this->newQuery()
            ->where(self::slugColumn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();

        return $query->exists();
    }



}
