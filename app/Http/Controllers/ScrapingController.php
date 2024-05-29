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

        $item = new Item($header[0]);
        $ingredients = Ingredient::hydrate($ingredients[0]);
        $processes = Process::hydrate($processes[0]);
        $item_tags = $item->tags()->get();
        return view('item.add', compact('item', 'ingredients', 'processes', 'item_tags'));
    }
}