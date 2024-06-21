@php use Carbon\Carbon; @endphp

@extends('adminlte::page')

@section('title', 'お知らせ配信')

@section('content_header')
    <h2>お知らせ配信</h2>
@stop

@section('content')
@if (session()->has('success'))
    <div class="success">
        {{ session()->get('success') }}
    </div>
@endif

<div class="row main">
    <div class="col-md-10">
        <button class="info-button" data-bs-toggle="modal" data-bs-target="#js-modal-info">新規配信</button>
        <div class="modal fade" id="js-modal-info" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>新規配信</span>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/admin/info">
                            @csrf
                            <input class="form-control mb-1" type="text" name="title" placeholder="タイトル" required maxlength="40">
                            <textarea class="form-control mb-2" name="content" placeholder="内容" required maxlength="400"></textarea>
                            <button type="submit" class="btn btn-primary info-btn">送信</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <caption>過去の配信</caption>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            @foreach($allInfo as $info)
                            <tr>
                                <th scope="row">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#js-modal-info-{{ $info->id }}">{{ $info->title }}</a>
                                </th>
                                <td>
                                    @if(Carbon::parse($info->created_at)->isToday())
                                        {{ Carbon::parse($info->created_at)->format("H時i分") }}
                                    @else
                                        {{ Carbon::parse($info->created_at)->format("Y年m月d日") }}
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="js-modal-info-{{ $info->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <span>{{ $info->title }}</span>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="notice-content">{{ $info->content }}</p>
                                            <p style="color:gray">{{ Carbon::parse($info->created_at)->format("Y年m月d日 H時i分") }}配信</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="pagination">
                    {{ $allInfo->links() }}
                </p>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
@stop

@section('js')
    <script src="https://kit.fontawesome.com/9322fd0ab2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="{{asset('js/custom.js')}}"></script>
@stop
