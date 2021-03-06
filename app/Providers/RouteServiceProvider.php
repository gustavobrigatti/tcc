<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('user', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('useer', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('inbox', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('instituicao', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('turma', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('album', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('aula', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('aulaTurma', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('arquivo', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('tarefa', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });
        Route::bind('notum', function($value, $route) {
            return Hashids::decode($value)[0] ?? 0;
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
