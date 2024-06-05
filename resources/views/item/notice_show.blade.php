@php use Carbon\Carbon; @endphp

@extends('layouts.app')

@section('title', 'お知らせ')

@section('content')
@include('layouts.sidebar')
<div class="row main">
    <h1>お知らせ</h1>
    <div class="col-md-10">
        <div class="card card-primary">
            <div class="card-body">
                <div>
                    <p>{{ Carbon::parse($notice->created_at)->format("Y年m月d日H時i分") }}受信</p>
                    <h7>タイトル：{{ $notice->title }}</h7>
                    <p class="notice-content">内容：{{ $notice->content }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@stop