<?php


namespace Support\Testing;


use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FakerImageProvider extends Base
{
    public function loremflickr(string $dir ='', int $width = 500, int $height = 500): string
    {
        $name = $dir . '/' . Str::random(6) . '.jpg';

        Storage::put(
            $name,
            file_get_contents("https://loremflickr.com/$width/$height")
        );

        return '/storage/' .$name;
    }

    public function fixturesImage(string $fixturesDir, string $storageDir): string
    {
        $storage = Storage::disk('images');

        if(!$storage->exists($storageDir)) {
            $storage->makeDirectory($storageDir);
        }

       $file = $this->generator->file(
           base_path("tests/Fixtures/images/$fixturesDir"),
           $storage->path($storageDir),
           false
       );

       return '/storage/images/' . trim($storageDir, '/') . '/' . $file;
    }
}
