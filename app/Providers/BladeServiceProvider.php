<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Providers\BladeServiceProvider;

use Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    /**
     * @date blade directive 
     * use as @date($object->datefield) 
     * or with a format @date($object->datefield,'m/d/Y')
     */
    public function boot(): void
    {
        Blade::directive('date', function ($expression) {
            $default = "'d-m-Y H:i'";           //set default format if not present in $expression

            $parts = str_getcsv($expression);
            $parts[1] = (isset($parts[1])) ? $parts[1] : $default;
            return '<?php if(' . $parts[0] . '){ echo e(' . $parts[0] . '->format(' . $parts[1] . ')); } ?>';
        });
    }
}
