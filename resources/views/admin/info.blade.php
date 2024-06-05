@php use Carbon\Carbon; @endphp

@extends('adminlte::page')

@section('title', 'お知らせ配信')

@section('content_header')
    <h2>お知らせ配信</h2>
@stop

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                @foreach($inquiries as $inquiry)
                <tr id="inquiry-row" class="@if($inquiry->status == 'unread') table-danger @elseif($inquiry->status == 'read') table-warning @endif">
                    <th scope="row"><a href="/admin/inquiry/{{ $inquiry->id }}">{{ $inquiry->title }}</a></th>
                    <td>@if(Carbon::parse($inquiry->created_at)->isToday())
                            {{ Carbon::parse($inquiry->created_at)->format("H時i分") }}
                        @else
                            {{ Carbon::parse($inquiry->created_at)->format("Y年m月d日") }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="pagination">
        {{ $inquiries->links() }}
    </p>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0-beta3/css/all.min.css">

@stop

@section('js')
    <script src="https://kit.fontawesome.com/9322fd0ab2.js" crossorigin="anonymous"></script>
@stop
