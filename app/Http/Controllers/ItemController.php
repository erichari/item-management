<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Item;
use App\Models\Ingredient;
use App\Models\Process;
use App\Models\Tag;
use App\Models\Item_tag;
use DB;

class ItemController extends Controller
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
     * 商品一覧
     */
    public function index()
    {
        $items = Item::where('draft', null)
            ->select('id', 'title', 'score', 'image', 'draft')
            ->orderby('created_at', 'desc')
            ->paginate(20);

        $ingredients = Item::where('draft', null)
            ->join('ingredients', 'ingredients.item_id', 'items.id')
            ->select('item_id', DB::raw("GROUP_CONCAT(ingredient SEPARATOR '、') AS ingredients_text"))
            ->groupBy('item_id')
            ->get();

        $item_tags = Tag::join('item_tags', 'item_tags.tag_id', 'tags.id')
            ->join('items', 'item_tags.item_id', 'items.id')
            ->select('icon', 'type', 'items.id')
            ->orderBy('tags.id', 'asc')
            ->get();

        return view('item.index', compact('items', 'ingredients', 'item_tags'));
    }

    /**
     * 下書き一覧
     */
    public function drafts()
    {
        $items = Item::where('draft', 'draft')
            ->select('id', 'title', 'score', 'image', 'draft')
            ->orderby('created_at', 'desc')
            ->paginate(20);

        $ingredients = Item::where('draft', 'draft')
            ->join('ingredients', 'ingredients.item_id', 'items.id')
            ->select('item_id', DB::raw("GROUP_CONCAT(ingredient SEPARATOR '、') AS ingredients_text"))
            ->groupBy('item_id')
            ->get();

        $item_tags = Tag::join('item_tags', 'item_tags.tag_id', 'tags.id')
            ->join('items', 'item_tags.item_id', 'items.id')
            ->select('icon', 'type', 'items.id')
            ->orderBy('tags.id', 'asc')
            ->get();

        return view('item.index', compact('items', 'ingredients', 'item_tags'));
    }


    /**
     * 商品登録画面を表示
     */
    public function addView(Request $request){
        $item = new Item();
        $ingredients = $item->ingredients()->get();
        $processes = $item->processes()->get();
        $item_tags = $item->tags()->get();

        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }

    /**
     * 商品登録
     */
    public function add(ItemRequest $request)
    {
        $item = new Item();

        // スクレイピングした画像がある場合
        if($request->scraped_image){
            $item->image = $request->scraped_image;

        // 画像がアップロードされてる場合
        }elseif ($request->hasFile('image')) {
            $base64Image = base64_encode(file_get_contents($request->image->getRealPath()));
            $mimeType = $request->image->getMimeType();
            $item->image = 'data:' . $mimeType . ';base64,' . $base64Image;
        }

        //下書きボタンが押された場合 
        if($request->route()->getName() == 'addDraft'){
            $item->draft = 'draft';
        }

        $item->user_id = Auth::user()->id;
        $item->title = $request->title;
        $item->serving = $request->serving;
        $item->score = $request->score;
        $item->memo = $request->memo;
        $item->save(); 
        
        $item_id = Item::latest('id')->first()->id;
        
        //材料を登録
        $ingredients = [];
        foreach($request->ingredients as $ingredient){
            if($ingredient['name'] == null && $ingredient['quantity'] == null){
                continue;
            }
            
            // ※漢字→カタカナ、カタカナ→漢字は非対応
            // 材料名の漢字を取得
            $text = urlencode($ingredient['name']);
            $kanji_candidates = json_decode(Http::get('http://www.google.com/transliterate?langpair=ja-Hira|ja' . '&text=' . $text));
            $kanji = [];
            foreach($kanji_candidates as $kanji_candidate){
                $kanji[] = implode($kanji_candidate[1]); 
            }

            // 材料名のひらがなを取得
            $furigana = [];
            $furigana_candidates = hurigana($ingredient['name']);
            foreach($furigana_candidates as $furigana_candidate){
                if(array_key_exists('furigana', $furigana_candidate)){
                    $furigana[] = $furigana_candidate['furigana'];
                }else{
                    $furigana[] = $furigana_candidate['surface'];
                }
            }

            $ruby = array_merge($kanji, $furigana);
            $ruby = implode($ruby);


            $ingredients[] = [
                'item_id' => $item_id,
                'ingredient' => $ingredient['name'],
                'ruby' => $ruby,
                'quantity' => $ingredient['quantity'],
            ];
        }
        Ingredient::insert($ingredients);

        //作り方を登録
        $processes = [];
        foreach($request->processes as $process){
            if($process['name'] == null && !array_key_exists('image', $process)){
                continue;
            }

            // アップロードされた画像 または スクレイピングされた画像がある場合
            if (array_key_exists('image', $process) || array_key_exists('scraped_image', $process)){
                //スクレイピングされた画像がある場合
                if(array_key_exists('scraped_image', $process) && $process['scraped_image']){
                    $process_image = $process['scraped_image'];

                //アップロードされた画像がある場合
                }elseif(array_key_exists('image', $process) && $process['image']){
                    $image = $process['image'];
                    $base64Image = base64_encode(file_get_contents($image->getRealPath()));
                    $mimeType = $image->getMimeType();
                    $process_image = 'data:' . $mimeType . ';base64,' . $base64Image;

                //画像がない場合
                }else{
                    $process_image = null;
                }
            }else{
                $process_image = null;
            }

            $processes[] = [
                'item_id' => $item_id,
                'process' => $process['name'],
                'process_image' => $process_image,
            ];
        }
        Process::insert($processes);

        //item_tagsテーブル更新
        if(isset($request->add_tag)){
            foreach($request->add_tag as $tag){
                $tags[] = [
                    'item_id' => $item_id,
                    'tag_id' => $tag,
                ];
            }
            Item_tag::insert($tags);
        }

        return redirect('/items');
    }


    /**
     * 商品一覧
     */
    public function show(Request $request)
    {
        $item = Item::find($request->id);
        $ingredients = $item->ingredients()->get();
        $processes = $item->processes()->get();
        $item_tags = $item->tags()->orderby('id', 'asc')->get();

        return view('item.show', compact('item', 'ingredients', 'processes', 'item_tags'));
    }

    /**
     * 商品編集画面を表示
     */
    public function editView(Request $request)
    {
        $item = Item::find($request->id);
        $ingredients = $item->ingredients()->get();
        $processes = $item->processes()->get();
        $item_tags = $item->tags()->get();

        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }

    /**
     * 商品編集
     */
    public function edit(ItemRequest $request)
    {
        $item = Item::find($request->id);

        //下書きボタンが押された場合 
        if($request->route()->getName() == 'returnDraft'){
            $item->draft = 'draft';
        }else{
            $item->draft = null;
        }

        //画像がアップロードされてる場合
        if ($request->hasFile('image')) {
            $base64Image = base64_encode(file_get_contents($request->image->getRealPath()));
            $mimeType = $request->image->getMimeType();
            $item->image = 'data:' . $mimeType . ';base64,' . $base64Image;
        }

        $item->user_id = Auth::user()->id;
        $item->title = $request->title;
        $item->serving = $request->serving;
        $item->score = $request->score;
        $item->memo = $request->memo;
        $item->save(); 


        //材料を登録
        $ingredients = [];
        foreach($request->ingredients as $ingredient){
            if($ingredient['id'] && $ingredient['name'] == null && $ingredient['quantity'] == null){
                Ingredient::find($ingredient['id'])->delete();
            }
            if($ingredient['name'] == null && $ingredient['quantity'] == null){
                continue;
            }

            // 材料名の漢字を取得
            $text = urlencode($ingredient['name']);
            $kanji_candidates = json_decode(Http::get('http://www.google.com/transliterate?langpair=ja-Hira|ja' . '&text=' . $text));
            $kanji = [];
            foreach($kanji_candidates as $kanji_candidate){
                $kanji[] = implode($kanji_candidate[1]); 
            }

            // 材料名のひらがなを取得
            $furigana = [];
            $furigana_candidates = hurigana($ingredient['name']);
            foreach($furigana_candidates as $furigana_candidate){
                if(array_key_exists('furigana', $furigana_candidate)){
                    $furigana[] = $furigana_candidate['furigana'];
                }else{
                    $furigana[] = $furigana_candidate['surface'];
                }
            }

            $ruby = array_merge($kanji, $furigana);
            $ruby = implode($ruby);


            $ingredients[] = [
                'id' => $ingredient['id'],
                'item_id' => $item->id,
                'ingredient' => $ingredient['name'],
                'ruby' => $ruby,
                'quantity' => $ingredient['quantity'],
            ];
        }
        Ingredient::upsert($ingredients, ['id']);

        //作り方を登録
        $processes = [];
        foreach($request->processes as $process){
            if($process['id'] && Process::find($process['id'])->process_image == null && $process['name'] == null && !array_key_exists('image', $process)){
                Process::find($process['id'])->delete();
            }
            if($process['name'] == null && !array_key_exists('image', $process) && Process::find($process['id'])->process_image == null){
// todo:すでに画像があるが、何も選択されてない＆＆nameが空の場合の処理
            }elseif($process['name'] == null && !array_key_exists('image', $process)){
                continue;
            }

            if (array_key_exists('image', $process)) {
                $image = $process['image'];
                $base64Image = base64_encode(file_get_contents($image->getRealPath()));
                $mimeType = $image->getMimeType();
                $process_image = 'data:' . $mimeType . ';base64,' . $base64Image;
            }else{
                if(Process::where('id', $process['id'])->exists()){
                    $process_image = Process::find($process['id'])->process_image;
                }else{
                    $process_image = null;
                }
            }
            
            $processes[] = [
                'id' => $process['id'],
                'item_id' => $item->id,
                'process' => $process['name'],
                'process_image' => $process_image,
            ];
        }
        Process::upsert($processes, ['id']);

        //item_tagsテーブル更新
        $item->tags()->sync($request->add_tag);


        if($request->route()->getName() == 'returnDraft'){
            return redirect('items/drafts');
        }
        return redirect('items/show/'.$item->id);
    }

    /**
     * 商品削除
     */
    public function destroy($id){
        Item::find($id)->delete();

        return redirect()->route('index');
    }


    /**
     * 検索
     */
    public function search(Request $request){
        $keyword = $request->keyword;
        $escape_keyword = addcslashes($keyword, '\\_%');
        $tags = $request->tag;
        $min_score = $request->min_score;
        $max_score = $request->max_score;
        $sort = $request->sort;

        $query = Item::query()->where('draft', null);

        if(!empty($keyword)) {
            $query->where('title', 'LIKE', '%'.$escape_keyword.'%')
                ->orWhere('memo', 'LIKE', '%'.$escape_keyword.'%')
                ->orwhereHas('ingredients', function ($query) use ($escape_keyword) {
                    $query->where('ingredient', 'LIKE', '%'.$escape_keyword.'%')
                        ->orwhere('ruby', 'LIKE', '%'.$escape_keyword.'%');
                })
                ->orwhereHas('processes', function ($query) use ($escape_keyword) {
                    $query->where('process', 'LIKE', '%'.$escape_keyword.'%');
                });
        }

        
        if(!empty($tags)) {
            foreach($tags as $tag){
                $query->whereHas('tags', function ($query) use ($tag){
                        $query->where('tags.id', $tag);
                });
            }
        }

        if ($min_score) {
            $query->where('score', '>=', $min_score);
        }

        if ($max_score) {
            $query->where('score', '<=', $max_score);
        }

        if ($sort == '新しい順') {
            $query->orderby('created_at', 'desc');
        }elseif($sort == '古い順'){
            $query->orderby('created_at', 'asc');
        }elseif($sort == '点数順'){
            $query->orderby('score', 'desc');
        }
        
        $items = $query->paginate(20);

        $ingredients = Item::where('draft', null)
            ->join('ingredients', 'ingredients.item_id', 'items.id')
            ->select('item_id', DB::raw("GROUP_CONCAT(ingredient SEPARATOR '、') AS ingredients_text"))
            ->groupBy('item_id')
            ->get();

        $item_tags = Tag::join('item_tags', 'item_tags.tag_id', 'tags.id')
        ->join('items', 'item_tags.item_id', 'items.id')
        ->select('icon', 'type', 'items.id')
        ->orderBy('tags.id', 'asc')
        ->get();

        $search_parameters = ['keyword' => $keyword,
                                'tags' => $tags,
                                'min_score' => $min_score,
                                'max_score' => $max_score,
                                'sort' => $sort,
                            ];
        
        return view('item.index', compact('items', 'ingredients', 'item_tags', 'search_parameters'));
    }


}

function hurigana(string $text)    
{
    $url = 'https://jlp.yahooapis.jp/FuriganaService/V2/furigana';
    $appid = 'dj00aiZpPUs1MDQ3dVZ0OUZQUyZzPWNvbnN1bWVyc2VjcmV0Jng9NDE-';

    $param_array = array(
        'id' => '1234-1',
        'jsonrpc' => '2.0',
        'method' => 'jlp.furiganaservice.furigana',
        'params' => array(
            'q' => $text,
            'grade' => 1
        )
    );
    $params = json_encode($param_array); 

    $ch = curl_init($url); //curl_init() cURL セッションを初期化する
    curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_USERAGENT      => "Yahoo AppID: ".$appid,
    )); //CURL 転送用の複数のオプションを設定する

    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $array = json_decode($result ,true);
    return $array['result']['word'];
}