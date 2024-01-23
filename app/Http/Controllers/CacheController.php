<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clearCache()
    {
        // Clear the application cache
        Artisan::call('cache:clear');

        // Clear the configuration cache
        Artisan::call('config:clear');

        // Clear the route cache
        Artisan::call('route:clear');

        // Clear the view cache
        Artisan::call('view:clear');


        
        // Run migrations
        // Artisan::call('migrate');

        // Run command for work_mode of users
        // Artisan::call('command:update_work_mode');

        return response()->json(['message' => 'Cache cleared'], 200);
    }
}

