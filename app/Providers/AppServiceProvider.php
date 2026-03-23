<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // @bytes(1234567) → "1.2 MB"
        Blade::directive('bytes', function (string $expression) {
            return "<?php echo (function(\$b) {
                if (\$b >= 1e12) return round(\$b/1e12, 1).' TB';
                if (\$b >= 1e9)  return round(\$b/1e9, 1).' GB';
                if (\$b >= 1e6)  return round(\$b/1e6, 1).' MB';
                if (\$b >= 1e3)  return round(\$b/1e3, 1).' KB';
                return \$b.' B';
            })({$expression}); ?>";
        });
    }
}
