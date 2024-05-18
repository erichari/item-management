@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    <div class="row main g-4 main">
    @if(!isset($items[0]))
        @section('title', 'ホーム画面')
        <h1>レシピがありません</h1>
    @else
        @if($items[0]->draft == 'draft')
            @section('title', '下書き一覧')
            <h1>下書き一覧</h1>
        @else
            @section('title', 'ホーム画面')
            <h1>レシピ一覧</h1>
        @endif
    @endif

        @foreach ($items as $item)
            <div class="card-box col-12 col-md-6 col-lg-4">
                <div class="card text-bg-light">
                    <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->title }}">
                    <div class="card-body">
                        @if($item->draft == 'draft')
                            <a href="/items/edit/{{$item->id}}" class="stretched-link"></a>
                        @else
                            <a href="/items/show/{{$item->id}}" class="stretched-link"></a>
                        @endif
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <div class="icon-list">
                            @foreach($item_tags as $tag)
                                @if($tag->id !== $item->id)
                                    @continue
                                @elseif($tag->type == 1)
                                    <span class="genre-icon icon">{{$tag->icon}}</span>
                                @elseif($tag->type == 2)
                                    <span class="category-icon icon">{{$tag->icon}}</span>
                                @elseif($tag->type == 3)
                                    <span class="tag-icon icon"><i class="view_icon">{{$tag->icon}}</i></span>
                                @endif
                            @endforeach
                        </div>

                        @foreach($ingredients as $ingredient)
                            @if($ingredient->item_id == $item->id)
                                <p class="card-text text-nowrap">{{$ingredient->ingredients_text}}</p>
                            @else
                                @continue
                            @endif
                        @endforeach

                        <p class="score">{{ $item->score }}</p>
                        @if($item->draft == 'draft')
                            <p class="draft">下書き</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        </div>

        <p class="pagination">
            @if(isset($search_parameters))
                {{ $items->appends(request()->query())->links() }}
            @else
                {{ $items->links() }}
            @endif
        </p>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
