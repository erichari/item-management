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
            'genre_tags' => Tag::where('type', 1)->get(),
            'category_tags' => Tag::where('type', 2)->get(),
            'i' => 0,
        ]);
    }
}

