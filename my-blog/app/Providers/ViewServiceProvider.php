<?php


namespace App\Providers;

use \Illuminate\Support\ServiceProvider;
use App\Models\Category;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $categories = (new \App\Models\Category)->getCategoryByNum(5);
        View::share('categories', $categories);
    }
}
