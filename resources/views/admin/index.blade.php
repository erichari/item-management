@extends('adminlte::page')

@section('title', '管理者用ページ')

@section('content_header')
    <h1>管理者用ページ</h1>
@stop

@section('content')
    <p>ここは管理者専用です。</p>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
