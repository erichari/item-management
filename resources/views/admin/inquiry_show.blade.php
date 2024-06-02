@extends('adminlte::page')

@section('title', 'お問い合わせ詳細')

@section('content_header')
    <h1>お問い合わせ詳細</h1>
@stop

@section('content')
    <div>
        <h5>{{ $inquiry->title }}</h5>
        <p>{{ $inquiry->content }}</p>
    </div>
    
    <form method="POST" action="/admin/inquiry">
        @csrf
        <input class="form-control" type="text" name="title" placeholder="タイトル" value="Re:{{ $inquiry->title }}">
        <textarea class="form-control" name="content" placeholder="内容"> &NewLine;------------------------------------------------------ &NewLine;\nお問い合わせ内容 ： {{ $inquiry->content }}</textarea>
        <button type="submit" class="btn btn-primary">送信</button>
    </form>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="asset('/css/admin_custom.css')"> --}}
@stop

@section('js')
@stop