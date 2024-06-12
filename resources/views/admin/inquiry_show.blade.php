@php use Carbon\Carbon; @endphp

@extends('adminlte::page')

@section('title', 'お問い合わせ詳細')

@section('content_header')
    <h1>お問い合わせ詳細</h1>
@stop

@section('content')
    @if (session()->has('success'))
        <div class="success">
            {{ session()->get('success') }}
        </div>
    @endif
    
    <div>
        <p>{{ Carbon::parse($inquiry->created_at)->format("Y年m月d日 H時i分") }}受信</p>
        <p>ID:{{ $inquiry->user_id }} {{ $inquiry->name }}様より</p>
        <h7>タイトル：{{ $inquiry->title }}</h7>
        <p class="inquiry-content">内容：{{ $inquiry->content }}</p>
    </div>
    
    @if($inquiry->status == 'read')
    <span class="status" style="background-color:#f88">未対応</span>
    @elseif($inquiry->status == 'replied')
    <span class="status" style="background-color:#0dcaf0">対応済み</span>
    @endif
    
    <form method="POST" action="/admin/inquiry/{{ $inquiry->id }}">
        @csrf
        <input class="form-control" type="text" name="title" placeholder="タイトル" value="Re:{{ $inquiry->title }}" required maxlength="40">
        <textarea class="form-control" name="content" placeholder="内容" required maxlength="400"> &NewLine;------------------------------------------------------ &NewLine;お問い合わせ内容 ： {{ $inquiry->content }}</textarea>
        <input type="submit" class="btn btn-primary">
    </form>
    @if($inquiry->status !== 'replied')
        <form method="POST" action="/admin/inquiry/{{ $inquiry->id }}">
            @csrf
            @method('PATCH')
            <input type="submit" id="reply-btn" value="対応済みにする">
        </form>
    @endif

    <!-- 返信 -->
    @if($replies)
    @foreach($replies as $reply)
        <div>
            <p>{{ Carbon::parse($reply->created_at)->format("Y年m月d日 H時i分") }}送信</p>
            <h5>タイトル：{{ $reply->title }}</h5>
            <p class="inquiry-content">内容：{{ $reply->content }}</p>
        </div>
        @endforeach
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
@stop

@section('js')
<script>
    $(function(){
        // 問い合わせ対応済みボタン押下時のアラート
        $('#reply-btn').click(function(){
            if(!confirm("未返信ですが対応済みにしてよろしいですか？")){
                /* キャンセルの時の処理 */
                return false;
            }else{
                /*　OKの時の処理 */            
            }
        })
    })
</script>
@stop