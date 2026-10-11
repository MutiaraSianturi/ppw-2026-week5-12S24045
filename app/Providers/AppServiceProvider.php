<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Supaya tombol pagination tampil benar dengan Bootstrap 5
        Paginator::useBootstrapFive();

        // Listener ini jalan SETIAP ada kueri SQL dan mencatatnya ke storage/logs/laravel.log
        DB::listen(function ($query) {
            Log::info(sprintf("[SQL AUDIT] (%.2f ms) %s", $query->time, $query->sql));
        });
    }
}