<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use App\User;
use App\Project;
use App\DsrRead;
use App\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    { 
        $_SERVER['HTTP_HOST'] = 'talentone.teamtalentelgia.com';
        Schema::defaultStringLength(191);
        LengthAwarePaginator::useBootstrap();
        \URL::forceRootUrl(env('APP_URL'));
        if(env('APP_ENV') != 'dev'){
            \URL::forceScheme('https'); 
        }
        
        if (Schema::hasTable('users')) {
            $dsrUsers = User::whereIn('role_id', [4])->with('dsr.read')->get();
        
            View::share('dsrUsers', $dsrUsers);
        }

        if (Schema::hasTable('projects')) {
            $pro = Project::Where('status','!=',1)->get();

            View::share('pro', $pro);
        }    

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}