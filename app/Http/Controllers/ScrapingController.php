<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Item;
use App\Models\Ingredient;
use App\Models\Process;
use Exception;

class ScrapingController extends Controller
{
    // クックパッドのスクレイピング
    public function cookpadScrape(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url|starts_with:https://cookpad.com/jp/recipes/',
        ]);

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', $request->url);
            $crawler = new Crawler($response->getBody()->getContents());
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            return back()->with('error', 'URLが読み込めませんでした。');
        }

        try {
            $header = $crawler->filter('#recipe')->each(function (Crawler $node) {
                return [
                    'image' => $node->filter('.tofu_image img')->attr('src'),
                    'title' => $node->filter('h1')->text(),
                    'serving' => $node->filter('#ingredients .mise-icon-text')->text(),
                ];
            });
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            $error = $e->getMessage();
            dd($error);
            return back()->with('error', '対応していないURLです。');
        }

        try {
            $ingredients = $crawler->filter('#ingredients')->each(function (Crawler $node, $i) {
                return $node->filter('li')->each(function (Crawler $node, $i) {
                        return[
                            'ingredient' => $node->filter('span')->text(),
                            'quantity' => $node->filter('bdi')->text(),
                        ];
                });
            });
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            dd('材料名エラー');
        }

        try {
            $processes = $crawler->filter('#steps')->each(function (Crawler $node, $i) {
                return $node->filter('li')->each(function (Crawler $node, $i) {
                    if($node->filter('img')->count()){
                        return[
                            'process' => $node->filter('p')->text(),
                            'process_image' => $node->filter('img')->attr('src'),
                        ];
                    }else{
                        try {
                            return[
                                'process' => $node->filter('p')->text(),
                                'process_image' => null,
                            ];
                        } catch (Exception $e) {
                            \Log::error($e);  // 例外をログに記録
                            return back()->with('error', 'データの操作中にエラーが発生しました。');
                        }
                    }
                });
            });
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            dd('作り方エラー');
        }


        $item = new Item($header[0]);
        $ingredients = Ingredient::hydrate($ingredients[0]);
        $processes = Process::hydrate($processes[0]);
        $item_tags = $item->tags()->get();
        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }


    // 楽天レシピのスクレイピング
    public function rakutenScrape(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url|starts_with:https://recipe.rakuten.co.jp/recipe/',
        ]);

        $client = new \GuzzleHttp\Client();;
        try {
            $response = $client->request('GET', $request->url);
            $crawler = new Crawler($response->getBody()->getContents());
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            $error = $e->getMessage();
            return back()->with('error', 'URLが読み込めませんでした。');
        }

        try {
            $header = $crawler->filter('.main_contents')->each(function (Crawler $node) {
                return [
                    'image' => $node->filter('.recipe_info_img > img')->attr('src'),
                    'title' => $node->filter('.page_title__text')->text(),
                    'serving' => $node->filter('.contents_title')->text(),
                ];
            });
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            return back()->with('error', '対応していないURLです。');
        }

        try {
            $ingredients = $crawler->filter('.recipe_material')->each(function (Crawler $node, $i) {
                return $node->filter('.recipe_material__item')->each(function (Crawler $node, $i) {
                    try {
                        return [
                            'ingredient' => $node->filter('.recipe_material__item_name')->text(),
                            'quantity' => $node->filter('.recipe_material__item_serving')->text(),
                        ];
                    } catch (Exception $e) {
                        \Log::error($e);  // 例外をログに記録
                    }
                });
            });
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
        }

        try {
            $processes = $crawler->filter('.recipe_howto')->each(function (Crawler $node) {
                return $node->filter('.recipe_howto__item')->each(function (Crawler $node, $i) {
                    if($node->filter('.recipe_howto__img')->first()->matches('.recipe_howto__img')){
                        $image = $node->filter('.recipe_howto__img > img')->attr('src');
                    }else{
                        $image = null;
                    }
                    try {
                        return[
                            'process' => $node->filter('.recipe_howto__text')->text(),
                            'process_image' => $image,
                        ];
                    } catch (Exception $e) {
                        \Log::error($e);  // 例外をログに記録
                    }
                });
            });
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
        }

        try {
            $header[0]['title'] = str_replace(' レシピ・作り方', '', $header[0]['title']);
            $header[0]['serving'] = str_replace('材料（', '', $header[0]['serving']);
            $header[0]['serving'] = str_replace('）', '', $header[0]['serving']);
        } catch (Exception $e) {
            \Log::error($e);  // 例外をログに記録
            return back()->with('error', '対応していないURLです。');
        }

        $item = new Item($header[0]);
        $ingredients = Ingredient::hydrate($ingredients[0]);
        $processes = Process::hydrate($processes[0]);
        $item_tags = $item->tags()->get();
        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }
}