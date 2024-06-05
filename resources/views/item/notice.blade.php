@php use Carbon\Carbon; @endphp

@extends('layouts.app')

@section('title', 'お知らせ')

@section('content')
@include('layouts.sidebar')
<div class="row main">
    <h1>お知らせ</h1>
    <div class="col-md-10">
        <button data-bs-toggle="modal" data-bs-target="#js-modal-inquiry">新規送信</button>
        <div class="modal fade" id="js-modal-inquiry" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>新規送信</span>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/inquiry">
                            @csrf
                            <input class="form-control" type="text" name="title" placeholder="タイトル">
                            <textarea class="form-control" name="content" placeholder="内容"></textarea>
                            <button type="submit" class="btn btn-primary">送信</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            @foreach($notices as $notice)
                            <tr>
                                <th scope="row">
                                    <a href="/notice/{{ $notice->id }}">{{$notice->title}}</a>
                                </th>
                                <td style="border-right:none">
                                    @if(Carbon::parse($notice->created_at)->isToday())
                                        {{ Carbon::parse($notice->created_at)->format("H時i分") }}
                                    @else
                                        {{ Carbon::parse($notice->created_at)->format("Y年m月d日") }}
                                    @endif
                                </td>
                                <td style="border-left:none">
                                    @if($notice->reply_id !== 0 && $notice->status == 'unread')
                                        <i class="fa-solid fa-circle-exclamation" style="color:red"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="pagination">
                    {{ $notices->links() }}
                </p>
            </div>
        </div>
    </div>
</div>

@stop