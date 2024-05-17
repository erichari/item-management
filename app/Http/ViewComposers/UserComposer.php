<?php

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use App\Models\Tag;

/**
 * Class UserComposer
 * @package App\Http\ViewComposers
 */
class UserComposer
{    

    /**
     * Bind data to the view.
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'user' => Auth::user(),
            'tags' => Auth::user()->tags()->get(),
            'i' => 0,
            // ... ここに続けて共通で返したいデータを定義
        ]);
    }
}

