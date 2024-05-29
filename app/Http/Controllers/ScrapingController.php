<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Item;
use App\Models\Ingredient;
use App\Models\Process;

class ScrapingController extends Controller
{
    // クックパッドのスクレイピング
    public function cookpadScrape(Request $request)
    {
        $client = new \GuzzleHttp\Client();;
        $response = $client->request('GET', $request->url);
        $crawler = new Crawler($response->getBody()->getContents());

        $header = $crawler->filter('.recipe_show_wrapper')->each(function (Crawler $node) {
            return [
                'image' => $node->filter('#main-photo > img')->attr('src'),
                'title' => $node->filter('.recipe-title')->text(),
                'serving' => $node->filter('.servings_for')->text(),
            ];
        });


        $ingredients = $crawler->filter('#ingredients')->each(function (Crawler $node, $i) {
            return $node->filter('.ingredient_row')->each(function (Crawler $node, $i) {
                return[
                    'ingredient' => $node->filter('.name')->text(),
                    'quantity' => $node->filter('.ingredient_quantity')->text(),
                ];
            });
        });

        $processes = $crawler->filter('#steps')->each(function (Crawler $node) {
            return $node->filter('.step')->each(function (Crawler $node, $i) {
                return[
                    'process' => $node->filter('.step_text')->text(),
                    'process_image' => $node->filter('.large_photo_clickable')->attr('src'),
                ];
            });
        });
        
        $header[0]['serving'] = str_replace('（', '', $header[0]['serving']);
        $header[0]['serving'] = str_replace('）', '', $header[0]['serving']);

        $item = new Item($header[0]);
        $ingredients = Ingredient::hydrate($ingredients[0]);
        $processes = Process::hydrate($processes[0]);
        $item_tags = $item->tags()->get();
        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }


    // 楽天レシピのスクレイピング
    public function rakutenScrape(Request $request)
    {
        $client = new \GuzzleHttp\Client();;
        $response = $client->request('GET', $request->url);
        $crawler = new Crawler($response->getBody()->getContents());

        $header = $crawler->filter('.main_contents')->each(function (Crawler $node) {
            return [
                'image' => $node->filter('.recipe_info_img > img')->attr('src'),
                'title' => $node->filter('.page_title__text')->text(),
                'serving' => $node->filter('.contents_title')->text(),
            ];
        });


        $ingredients = $crawler->filter('.recipe_material')->each(function (Crawler $node, $i) {
            return $node->filter('.recipe_material__item')->each(function (Crawler $node, $i) {
                return [
                    'ingredient' => $node->filter('.recipe_material__item_name')->text(),
                    'quantity' => $node->filter('.recipe_material__item_serving')->text(),
                ];
            });
        });

        $processes = $crawler->filter('.recipe_howto')->each(function (Crawler $node) {
            return $node->filter('.recipe_howto__item')->each(function (Crawler $node, $i) {
                if($node->filter('.recipe_howto__img')->first()->matches('.recipe_howto__img')){
                    $image = $node->filter('.recipe_howto__img > img')->attr('src');
                }else{
                    $image = null;
                }
                return[
                    'process' => $node->filter('.recipe_howto__text')->text(),
                    'process_image' => $image,
                ];
            });
        });
        $header[0]['title'] = str_replace(' レシピ・作り方', '', $header[0]['title']);
        $header[0]['serving'] = str_replace('材料（', '', $header[0]['serving']);
        $header[0]['serving'] = str_replace('）', '', $header[0]['serving']);

        $item = new Item($header[0]);
        $ingredients = Ingredient::hydrate($ingredients[0]);
        $processes = Process::hydrate($processes[0]);
        $item_tags = $item->tags()->get();
        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }
}