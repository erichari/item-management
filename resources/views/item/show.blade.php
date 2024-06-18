@extends('layouts.app')

@section('title', '詳細画面')

@section('content')
    <div class="row main g-4">
        <div class="col-12">
            <div class="card card-primary">
                <h1 class="mb-2 p-3">{{$item->title}}</h1>
                <div class="score">{{$item->score}}点</div>
                <div class="show-image mb-2 col-12" data-bs-toggle="modal" data-bs-target="#js-modal-top-image">
                    <img src="{{ $item->image }}" alt="{{ $item->title }}">
                </div>

                <div class="modal fade" id="js-modal-top-image" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <img src="{{ $item->image }}">
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="icon-list mb-4">
                        @foreach($item_tags as $tag)
                            @if($tag->type == 1)
                                <span class="genre-icon icon checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></span>
                            @endif
                            @if($tag->type == 2)
                                <span class="category-icon icon checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></span>
                            @endif
                            @if($tag->type == 3)
                                <span class="tag-icon icon checkbox-label"><span class="view_icon">{{$tag->icon}}</span><span class="tooltip">{{$tag->tag}}</span></span>
                            @endif
                        @endforeach
                    </div>

                    <div class="mb-4">
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

                    <div class="mb-4">
                        <h5>作り方</h5>
                        <div class="ingredients-container col-11">
                            @foreach($processes as $process)
                                <div class="processes-list">
                                    <div class="process col-8">
                                        {{$process->process}}
                                    </div>
                                    <div class="col-3" data-bs-toggle="modal" data-bs-target="#js-modal-image{{ $process->id }}">
                                        @if($process->process_image)
                                            <img src="{{ $process->process_image }}" class="process-image p-1" alt="工程写真">
                                        @endif
                                    </div>
                                    <div class="modal fade" id="js-modal-image{{ $process->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <img src="{{ $process->process_image }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>メモ</h5>
                        <div>{{$item->memo}}</div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="/items/edit/{{$item->id}}" class="show-link">編集する</a>
                </div>
            </div>
        </div>
    </div>
@stop