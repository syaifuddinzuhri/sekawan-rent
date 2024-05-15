<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->app->isLocal()) {
            URL::forceScheme('https');
        }

        Builder::macro('whereLike', function ($attributes, $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            // [$relationName, $relationAttribute] = explode('.', $attribute);
                            $explode = explode('.', $attribute);
                            $relationName1 = $explode[0];
                            unset($explode[0]);
                            $attributes2 = implode('.', $explode);

                            foreach (Arr::wrap($attributes2) as $attr) {
                                $query->when(
                                    str_contains($attr, '.'),
                                    function (Builder $query) use ($attr, $searchTerm, $relationName1) {
                                        [$relationName2, $relationAttribute1] = explode('.', $attr);
                                        $query->orWhereHas($relationName1 . '.' . $relationName2, function (Builder $query) use ($relationAttribute1, $searchTerm) {
                                            $query->where($relationAttribute1, 'LIKE', "%{$searchTerm}%");
                                        });
                                    },
                                    function (Builder $query) use ($attr, $searchTerm, $relationName1) {
                                        $query->orWhereHas($relationName1, function (Builder $query) use ($attr, $searchTerm) {
                                            $query->where($attr, 'LIKE', "%{$searchTerm}%");
                                        });
                                    },
                                );
                            }
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }
}
