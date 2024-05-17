
@extends('layouts.app')

@section('title', 'タグ編集')

@section('content')
@include('layouts.sidebar')
<div class="row main">
    <h1>タグ編集</h1>
    <div class="col-md-10">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-primary">
            <form method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        @for($i=1; $i<=4; $i++)
                            <input type="text" class="form-control mb-2" name="tags[]" placeholder="タグ名{{$i}}" value="{{ old('tags', $tags[$i-1]->tag ?? '') }}">
                            <input type="text" class="form-control mb-2" name="icons[]" placeholder="アイコン{{$i}}" value="{{ old('icons', $tags[$i-1]->icon ?? '') }}">
                            <input type="search" name="icon_input[]" class="form-control" data-bs-original-title="" title="">
                        <div class="col-12">
                            <p class="icon tag-icon btn-secondary" id="select_btn"> <i id="view_icon"></i> </p>
                        </div>

                        @endfor
                        <p>*アイコンを設定しない場合、タグ名の頭文字がアイコンになります</p>

                        @if($errors->has('tags.*'))
                            <p class="alert">{{ $errors->first('tags.*') }}</p>
                        @endif 
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">登録</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', init);
    function init(){
        iconPicker = new Fa6IconPicker({
        });
    }
</script>