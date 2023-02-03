<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Generator::class, function() {
            $faker = Factory::create();
            $faker->addProvider(new FakerImageProvider($faker));
            return $faker;
        });
    }


    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        if(app()->isProduction()) {
            DB::listen(function ($query) {
                if($query->time > 1000) {
                    logger()->channel('telegram')
                        ->debug('query longer than 10s:' . $query->sql, $query->bindings);
                }
            });


            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::second(10),
                function () {
                    logger()->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan:' . request()->url());
                }
            );
        }

    }
}
