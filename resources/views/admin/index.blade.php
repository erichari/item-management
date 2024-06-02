@php use Carbon\Carbon; @endphp

@extends('adminlte::page')

@section('title', '管理者用ページ')

@section('content_header')
    <h1>管理者用ページ</h1>
@stop

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                @foreach($inquiries as $inquiry)
                <tr>
                    <th scope="row"><a href="/admin/inquiry/{{ $inquiry->id }}">{{ $inquiry->title }}</a></th>
                    <td>@if(Carbon::parse($inquiry->updated_at)->isToday())
                            {{ Carbon::parse($inquiry->updated_at)->format("H時i分") }}
                        @else
                            {{ Carbon::parse($inquiry->updated_at)->format("Y年m月d日") }}
                        @endif
                    </td>
                    <td>{{ $inquiry->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="pagination">
        {{ $inquiries->links() }}
    </p>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="asset('/css/admin_custom.css')"> --}}
@stop

@section('js')
@stop
