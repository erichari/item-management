<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ここに追加
        View::composers([
            // \App\Http\ViewComposers\UserComposer::class => '*', // 全てのviewに共通データを返す
            // \App\Http\ViewComposers\UserComposer::class => 'user.index', // resources\views\user\index.blade.phpに返したい場合の例
            \App\Http\ViewComposers\UserComposer::class => 'item.*', // resources\views\item\配下の全てのviewに返したい場合の例
            // \App\Http\ViewComposers\UserComposer::class => 'layouts.*',
        ]);
    }
}
