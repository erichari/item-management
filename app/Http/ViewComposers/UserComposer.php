<?php

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use App\Models\Tag;
use App\Models\Inquiry;

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
            'today_main' => Auth::user()->items()->where('draft', null)->whereHas('tags', function ($query) {
                $query->where('tags.id', 4);
            })->inRandomOrder()->first(),
            'today_side' => Auth::user()->items()->where('draft', null)->whereHas('tags', function ($query) {
                $query->where('tags.id', 5);
            })->inRandomOrder()->limit(2)->get(),
            'today_soup' => Auth::user()->items()->where('draft', null)->whereHas('tags', function ($query) {
                $query->where('tags.id', 6);
            })->inRandomOrder()->first(),
            'unread_notice' => Inquiry::leftjoin('inquiries as replied_inquiry', 'replied_inquiry.id', 'inquiries.reply_id')
                ->where('replied_inquiry.user_id', Auth::user()->id)
                ->where('inquiries.status', 'unread')
                ->select('inquiries.*')
                ->get(),
        ]);
    }
}

