<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
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
    
    public function editTagView(Request $request)
    {
        return view('item.editTag');
    }

    /**
     * タグ編集
     */
    public function editTag(TagRequest $request)
    {
        // タグ編集
        foreach($request->tags as $tag){
            if($tag['id'] !== null && $tag['name'] == null && $tag['icon'] == null){
                Tag::find($tag['id'])->delete();
                continue;
            }elseif($tag['name'] == null){
                continue;
            }elseif($tag['icon'] == null){
                $tag_icon = mb_substr($tag['name'], 0, 1);
            }else{
                $tag_icon = $tag['icon'];
            }

            $tags[] = [
                'id' => $tag['id'],
                'user_id' => Auth::user()->id,
                'tag' => $tag['name'],
                'icon' => $tag_icon,
            ];
        }

        if(isset($tags)){
            Tag::upsert($tags, ['id']);
        }
        
        session()->flash('success', 'タグを編集しました');
        return redirect('/items');
    
    }
}
