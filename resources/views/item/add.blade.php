@extends('layouts.app')

@if($item->title)
    @section('title', 'レシピ編集')
@else
    @section('title', 'レシピ新規登録')
@endif

@section('content')

    @include('layouts.sidebar')
    <div class="row main">
        @if($item->title)
            <h1>レシピ編集</h1>
        @else
            <h1>レシピ新規登録</h1>
        @endif
        <div class="col-md-10">
            <div class="card card-primary">
                <form method="POST" enctype="multipart/form-data" action="?">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="imageUpload">
                                <div>写真</div>
                                <input type="file" class="imageUpload" id="imageUpload" name="image" value="{{ old('image') }}">
                                <img class="preview @if($errors->has('image')) is-invalid @endif" src="@if($item->title) {{$item->image}} @else {{asset('/img/no_image.jpg')}} @endif">
                            </label>

                            @foreach($errors->get('image') as $message)
                                <p class="alert-message">{{$message}}</p>
                            @endforeach
                        </div>

                        <div class="form-group mb-4">
                            <label for="title">タイトル</label>
                            <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title" name="title" placeholder="タイトル" value="{{ old('title', $item->title) }}">
                            
                            @foreach($errors->get('title') as $message)
                                <p class="alert-message">{{$message}}</p>
                            @endforeach
                        </div>

                        <div class="form-group mb-2">
                            <label>ジャンル</label>
                            <div>
                                @foreach($genre_tags as $tag)
                                    <input type="checkbox" name="add_tag[]" value="{{$tag->id}}" id="add-{{$tag->id}}" class="genre form-control" {{ !empty(old("add_tag")) && in_array((string)$tag->id, old("add_tag"), true) || $item_tags->contains('id', $tag->id) && empty(old("add_tag")) ? 'checked' : ''}}>
                                    <label for="add-{{$tag->id}}" class="genre checkbox-label">
                                        {{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label>カテゴリ</label>
                            <div>
                                @foreach($category_tags as $tag)
                                    <input type="checkbox" name="add_tag[]" value="{{$tag->id}}" id="add-{{$tag->id}}" class="category form-control" {{ !empty(old("add_tag")) && in_array((string)$tag->id, old("add_tag"), true) || $item_tags->contains('id', $tag->id) && empty(old("add_tag")) ? 'checked' : ''}}>
                                    <label for="add-{{$tag->id}}" class="category checkbox-label">
                                        {{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span>
                                    </label>
                                @endforeach
                                </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>タグ</label>
                            <div>
                                @if(count($tags) == 0)
                                    <a href="{{url('items/editTag')}}">タグを追加する</a>
                                @else
                                    @foreach($tags as $tag)
                                        <input type="checkbox" name="add_tag[]" value="{{$tag->id}}" id="add-tag{{$tag->id}}" class="tag" {{ !empty(old("add_tag")) && in_array((string)$tag->id, old("add_tag"), true) || $item_tags->contains('id', $tag->id) && empty(old("add_tag")) ? 'checked' : ''}}>
                                        <label for="add-tag{{$tag->id}}" class="tag checkbox-label">
                                            <i class="view_icon">{{$tag->icon}}</i><span class="tooltip">{{$tag->tag}}</span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-4 input-list">
                            <label>材料</label>
                            <label>分量</label>
                            <div>
                                <input type="text" class="form-control mb-3 @if($errors->has('serving')) is-invalid @endif" id="serving" name="serving" placeholder="〇人分" value="{{ old('serving', $item->serving) }}">
                            </div>

                            @if(count($ingredients) == 0)
                                @for($i=0; $i<=2; $i++)
                                <div class="unit mb-2 d-flex">
                                    <div class="ingredient-forms d-flex">
                                        <input type="text" class="form-control ingredient-id" name="ingredients[{{$i}}][id]" hidden>
                                        <input type="text" class="form-control ingredient @if($errors->has('ingredients.'.$i.'.name')) is-invalid @endif" name="ingredients[{{$i}}][name]" placeholder="材料" value='{{ old("ingredients.$i.name") }}'>：
                                        <input type="text" class="form-control quantity @if($errors->has('ingredients.'.$i.'.quantity')) is-invalid @endif" name="ingredients[{{$i}}][quantity]" placeholder="分量" value='{{ old("ingredients.$i.quantity") }}'>
                                    </div>
                                    <div class="remove-button input-group-append" style="display:none">
                                        <span class="btn btn-danger">-</span>
                                    </div>
                                </div>
                                @endfor
                            @else
                                @for($i=0; $i < count($ingredients); $i++)
                                    <div class="unit mb-2 d-flex">
                                        <div class="ingredient-forms d-flex">
                                            <input type="text" class="form-control ingredient-id" name="ingredients[{{$i}}][id]" value="{{$ingredients[$i]->id}}" hidden>
                                            <input type="text" class="form-control ingredient @if($errors->has('ingredients.'.$i.'.name')) is-invalid @endif" name="ingredients[{{$i}}][name]" placeholder="材料" value="{{ old('ingredients.'.$i.'.name', $ingredients[$i]->ingredient) }}">：
                                            <input type="text" class="form-control quantity @if($errors->has('ingredients.'.$i.'.quantity')) is-invalid @endif" name="ingredients[{{$i}}][quantity]" placeholder="分量" value="{{ old('ingredients.'.$i.'.quantity', $ingredients[$i]->quantity) }}">
                                        </div>
                                            <div class="remove-button input-group-append" style="display:none">
                                            <span class="btn btn-danger">-</span>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                            <p class="add-button" role="button">＋材料を追加する</p>

                            @foreach ($errors->get('ingredients.*') as $messages)
                                @foreach ($messages as $message)
                                <p class="alert-message">{{ $message }}</p>
                                @endforeach
                            @endforeach
                        </div>

                        <div class="form-group mb-4 input-list">
                            <label>作り方</label>

                            @if(count($processes) == 0)
                                @for($i=0; $i<=2; $i++)
                                <div class="unit mb-2 d-flex">
                                    <div class="ingredient-forms d-flex">
                                        <input type="text" class="form-control process-id" name="processes[{{$i}}][id]" hidden>
                                        <textarea class="form-control process @if($errors->has('processes.'.$i.'.name')) is-invalid @endif" name="processes[{{$i}}][name]" placeholder="手順">{{ old("processes.$i.name") }}</textarea>
                                        <label for="imageUpload{{$i}}">
                                            <input type="file" class="imageUpload process_image" id="imageUpload{{$i}}" name="processes[{{$i}}][image]" value="{{ old("processes.$i.image") }}">
                                            <img class="preview process-image @if($errors->has('processes'.$i.'.image')) is-invalid @endif" src="{{ asset('/img/no_image.jpg') }}">
                                        </label>
                                    </div>
                                    <div class="remove-button input-group-append" style="display:none">
                                        <span class="btn btn-danger">-</span>
                                    </div>
                                </div>
                                @endfor
                            @else
                                @for($i=0; $i < count($processes); $i++)
                                <div class="unit mb-2 d-flex">
                                    <div class="ingredient-forms d-flex">
                                        <input type="text" class="form-control process-id" name="processes[{{$i}}][id]" value="{{$processes[$i]->id}}" hidden>
                                        <textarea class="form-control process @if($errors->has('processes.'.$i.'.name')) is-invalid @endif" name="processes[{{$i}}][name]" placeholder="手順">{{ old("processes.$i.name", $processes[$i]->process) }}</textarea>
                                        <label for="imageUpload{{$i}}">
                                            <input type="file" class="imageUpload process_image" id="imageUpload{{$i}}" name="processes[{{$i}}][image]" value="{{ old('processes.$i.image') }}">
                                                <img class="preview process-image @if($errors->has('processes.'.$i.'.image')) is-invalid @endif" src="@if($processes[$i]->process_image == null ) {{ asset('/img/no_image.jpg') }} @else {{$processes[$i]->process_image}} @endif">
                                        </label>
                                    </div>
                                    <div class="remove-button input-group-append" style="display:none">
                                        <span class="btn btn-danger">-</span>
                                    </div>
                                </div>
                                @endfor
                            @endif
                            <p class="add-button" role="button">＋作り方を追加する</p>

                            @foreach ($errors->get('processes.*') as $messages)
                                @foreach ($messages as $message)
                                <p class="alert-message">{{ $message }}</p>
                                @endforeach
                            @endforeach
                        </div>

                        <div class="form-group mb-4">
                            <label for="score">点数</label>
                            <input type="range" class="form-control" id="score" name="score" value="{{ old('score', $item->score) }}">
                            <p><span id="current-value"></span>点</p>
                        </div>

                        <div class="form-group mb-4">
                            <label for="memo">メモ</label>
                            <textarea class="form-control memo @if($errors->has('memo')) is-invalid @endif" id="memo" name="memo" placeholder="メモ">{{ old('memo', $item->memo) }}</textarea>
                            
                            @foreach($errors->get('memo') as $message)
                                <p class="alert-message">{{$message}}</p>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer">
                    @if($item->title)
                        <button type="submit" class="btn btn-secondary" id="draft-button" formaction="{{ route('returnDraft', ['id' => $item->id]) }}">下書きに戻す</button>
                        <button type="submit" class="btn btn-primary" id="register-button" formaction="{{ route('edit', ['id' => $item->id]) }}">登録</button>
                    @else
                        <button type="submit" class="btn btn-secondary" id="draft-button" formaction="{{ route('addDraft') }}">下書き</button>
                        <button type="submit" class="btn btn-primary" id="register-button">登録</button>
                    @endif
                    </div>
                </form>
                @if($item->title)
                    <form action="/items/destroy/{{$item->id}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="delete-btn" data-item-title="{{$item->title}}" data-item-id="{{$item->id}}">削除</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@stop
