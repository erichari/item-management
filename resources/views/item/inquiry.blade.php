@php use Carbon\Carbon; @endphp

@extends('layouts.app')

@section('title', 'ご要望・お問い合わせ')

@section('content')
@include('layouts.sidebar')
<div class="row main">
    <h1>ご要望・お問い合わせ</h1>
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
                        @foreach($inquiries as $inquiry)
                        <tr>
                            <th scope="row">{{$inquiry->title}}</th>
                            <td>@if(Carbon::parse($inquiry->updated_at)->isToday())
                                    {{ Carbon::parse($inquiry->updated_at)->format("H時i分") }}
                                @else
                                    {{ Carbon::parse($inquiry->updated_at)->format("Y年m月d日") }}
                                @endif
                            </td>
                            <td>{{$inquiry->status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="pagination">
                {{ $inquiries->links() }}
            </p>
            </div>
        </div>
    </div>
</div>

@stop