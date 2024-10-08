@extends('layouts.app')

@section('title', 'タグ編集')

@section('content')
<div class="row main">
    <h1>タグ編集</h1>
    <div class="col-sm-12 col-md-11">
        <div class="card card-primary">
            <form method="POST">
                @csrf
                <div class="card-body">
                    @for($i=0; $i<=3; $i++)
                        <div class="mb-3">
                            <div class="form-group mb-1 d-flex">
                                <input type="text" class="form-control" name="tags[{{$i}}][id]" value="{{$tags[$i]->id ?? ''}}" hidden>
                                <p tabindex="0" class="icon-btn tag-icon icon preview @if($errors->has('tags.'.$i.'.icon')) is-invalid @endif" data-btn-id="{{$i}}" data-toggle="popover" title="タグ名{{$i+1}}のアイコンを選択">
                                    <input type="text" class="form-control icon-input" id="input-id{{$i}}" name="tags[{{$i}}][icon]" value="{{ old('tags.'.$i.'.icon', $tags[$i]->icon ?? '') }}" hidden>
                                    <span id="view_icon{{$i}}" class="view_icon">{{ old('tags.'.$i.'.icon', $tags[$i]->icon ?? "") }}</span>
                                </p>
                                <div class="col-1"></div>
                                <div class="col-sm-10 col-md-7">
                                    <input type="text" class="form-control @if($errors->has('tags.'.$i.'.name')) is-invalid @endif" name="tags[{{$i}}][name]" placeholder="タグ名{{$i+1}}" value="{{ old('tags.'.$i.'.name', $tags[$i]->tag ?? '') }}">
                                </div>
                            </div>
                            <p class="clear-icon" id="clear-icon{{$i}}" @if(!isset($tags[$i]->icon)) style="display:none" @endif>×アイコンをクリア</p>
                            @foreach($errors->get('tags.'.$i.'.*') as $messages)
                                @foreach ($messages as $message)
                                    <p class="alert-message">{{ $message }}</p>
                                @endforeach
                            @endforeach
                        </div>
                    @endfor
                    <p>*アイコンを設定しない場合、タグ名の頭文字がアイコンになります</p>
                </div>

                <div class="card-footer">
                    <button type="submit" class="info-button">登録</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    //選択されたアイコンを<input>に入力
    function iconToInput(btn_id) {
        name = event.currentTarget.getAttribute('data-bs-name');
        targetInput = document.getElementById('input-id'+btn_id);
        targetViewIcon = document.getElementById('view_icon'+btn_id);
        targetInput.value = '<i class="fa-solid '+name+'"></i>';
        targetViewIcon.innerHTML = '<i class="fa-solid '+name+'"></i>';

        // アイコンクリアボタンを表示
        clearBtn = document.getElementById('clear-icon'+btn_id);
        clearBtn.style.display = '';
    }
</script>
@stop