<?php
 
namespace NativeBL\Providers;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use NativeBL\Contracts\Service\CrudBoard\CrudBoardInterface;
use NativeBL\Services\CrudBoard\CrudBoard;
use Illuminate\Pagination\Paginator;
class NativeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CrudBoardInterface::class, CrudBoard::class);
    }
 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::componentNamespace('NativeBL\\Views\\Component', 'native');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'native');
        Blade::anonymousComponentPath(__DIR__.'/../resources/views','native');
        Paginator::useBootstrapFive();
    }
}