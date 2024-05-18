
@extends('layouts.app')

@section('title', 'タグ編集')

@section('content')
@include('layouts.sidebar')
<div class="row main">
    <h1>タグ編集</h1>
    <div class="col-md-10">

        <div class="card card-primary">
            <form method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        @for($i=0; $i<=3; $i++)
                            <input type="text" class="form-control" name="tags[{{$i}}][id]" value="{{$tags[$i]->id ?? ''}}" hidden>
                            <input type="text" class="form-control mb-2 @if($errors->has('tags.'.$i.'.name')) is-invalid @endif" name="tags[{{$i}}][name]" placeholder="タグ名{{$i+1}}" value="{{ old('tags.'.$i.'.name', $tags[$i]->tag ?? '') }}">
                            <span tabindex="0" class="icon-btn tag-icon icon @if($errors->has('tags.'.$i.'.icon')) is-invalid @endif" data-btn-id="{{$i}}" data-toggle="popover" title="タグ名{{$i+1}}のアイコンを選択">
                                <input type="text" class="form-control mb-2 icon-input" id="input-id{{$i}}" name="tags[{{$i}}][icon]" value="{{ old('tags.'.$i.'.icon', $tags[$i]->icon ?? '') }}" hidden>
                                <i class="view_icon fa-solid">{{ old('tags.'.$i.'.icon', $tags[$i]->icon ?? "　") }}</i>
                            </span>
                            @foreach($errors->get('tags.'.$i.'.*') as $messages)
                                @foreach ($messages as $message)
                                    <p class="alert-message">{{ $message }}</p>
                                @endforeach
                            @endforeach
                        @endfor
                        <p>*アイコンを設定しない場合、タグ名の頭文字がアイコンになります</p>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">登録</button>
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
        targetViewIcon.textContent = '';
        targetViewIcon.classList.add(name);
    }


</script>




<script>

</script>
@stop


<!-- <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', init);
    function init(){
        iconPicker = new Fa6IconPicker({
        });
    }
</script> -->