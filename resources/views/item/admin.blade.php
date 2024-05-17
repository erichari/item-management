@if(auth()->user()->role == 2)
    @extends('adminlte::page')
@endif

@section('title', 'ホーム画面')


@section('content_header')
    <h1>レシピ一覧</h1>
@stop

@section('content')
    <div class="row main g-4">
        @foreach ($items as $item)
            <div class="card-box col-12 col-md-6 col-lg-4">
                <div class="card text-bg-light">
                    <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->title }}">
                    <div class="card-body">
                        @if($item->draft == 'draft')
                            <a href="/items/edit/{{$item->id}}" class="stretched-link"></a>
                        @else
                            <a href="items/show/{{$item->id}}" class="stretched-link"></a>
                        @endif
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <div class="icon-list">
                            @foreach($item_tags as $tag)
                                @if($tag->id !== $item->id)
                                    @continue
                                @endif
                                @if($tag->type == 1)
                                    <span class="genre-icon icon">{{$tag->icon}}</span>
                                @endif
                                @if($tag->type == 2)
                                    <span class="category-icon icon">{{$tag->icon}}</span>
                                @endif
                                @if($tag->type == 3)
                                    <span class="tag-icon icon">{{$tag->icon}}</span>
                                @endif
                            @endforeach
                        </div>
                        <p class="card-text text-nowrap">{{ $item->ingredients_text }}</p>
                        <p class="score">{{ $item->score }}</p>
                        @if($item->draft == 'draft')
                            <p class="draft">下書き</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
