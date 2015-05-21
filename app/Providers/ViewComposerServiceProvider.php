<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeNavigation();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     *  Compose foo navigation var for a view
     */
    public function composeNavigation()
    {
        view()->composer('pages.*', function ($view) {
            $view->with('object', getenv('COUCHDB_OBJECT'));
        });
    }

}
