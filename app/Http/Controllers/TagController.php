<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * タグ編集
     */
    public function editTag(Request $request)
    {
        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーション
            $this->validate($request, [
                'tags' => 'required|array|min:1',
                'tags.*' => 'max:20|required_with:icons.*',
                'tags.0' => 'required',
            ]);
            // タグ編集
            $i = 1;
            
            foreach(array_map(null, $request->tags, $request->icons) as [$tag, $icon]){
                if($tag == null){
                    continue;
                }
                if($icon == null){
                    $tag_icon = mb_substr($tag, 0, 1);
                }else{
                    $tag_icon = $icon;
                }

                $tags[] = [
                    'user_id' => Auth::user()->id,
                    'number' => $i,
                    'tag' => $tag,
                    'icon' => $tag_icon,
                ];
                $i++;
            }
            Tag::insert($tags);
            return redirect('/items');
        }

        return view('item.editTag');
    }
}
