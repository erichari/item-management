@extends('layouts.app')

@section('title', '詳細画面')

@section('content')
    @include('layouts.sidebar')
    <div class="row main g-4">
        <h1>{{$item->title}}</h1>
        <div class="show-image">
            <img src="{{ $item->image }}" alt="{{ $item->title }}">
        </div>

        <div class="show-header">
            <div class="icon-list">
                @foreach($item_tags as $tag)
                    @if($tag->id == $item->id)
                        @continue
                    @endif
                    @if($tag->type == 1)
                        <span class="genre-icon icon checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></span>
                    @endif
                    @if($tag->type == 2)
                        <span class="category-icon icon checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></span>
                    @endif
                    @if($tag->type == 3)
                        <span class="tag-icon icon checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></span>
                    @endif
                @endforeach
            </div>
            <div class="score">{{$item->score}}点</div>
        </div>

        <div>
            <h5>材料</h5>
            <div class="ingredients-container">
                <div>{{$item->serving}}</div>
                @foreach($ingredients as $ingredient)
                    <div class="ingredients-list">
                        <div class="ingredient">{{$ingredient->ingredient}}</div>
                        <div class="quantity">{{$ingredient->quantity}}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h5>作り方</h5>
            <div class="ingredients-container">
                @foreach($processes as $process)
                    <div class="processes-list">
                        <div class="process">
                            {{$process->process}}
                        </div>
                        <div class="process-image">
                            @if($process->process_image)
                                <img src="{{ $process->process_image }}" class="process-image" alt="工程写真">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h5>メモ</h5>
            <div>{{$item->memo}}</div>
        </div>

        <div>
            <a href="/items/edit/{{$item->id}}" class="show-link">編集する</a>
        </div>
    </div>
@stop