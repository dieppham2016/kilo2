<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		if ($this->app->isLocal()) {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
		}
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

		Builder::macro('whereLike', function ($attributes, string $searchTerm) {
			$this->where(function (Builder $query) use ($attributes, $searchTerm) {
				foreach (Arr::wrap($attributes) as $attribute) {
					$query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
				}
			});

			return $this;
		});
    }
}
