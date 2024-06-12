@php use Carbon\Carbon; @endphp

@extends('layouts.app')

@section('title', 'お知らせ')

@section('content')
<div class="row main g-4">
    <h1>お知らせ</h1>
    <div class="col-11">
        <div class="card card-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            @foreach($notices as $notice)
                            <tr>
                                <th scope="row">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#js-modal-notice-{{ $notice->id }}" class="change-status" data-status="{{$notice->status}}" data-notice-id="{{$notice->id}}">{{$notice->title}}</a>
                                </th>
                                <td style="border-right:none">
                                    @if(Carbon::parse($notice->created_at)->isToday())
                                        {{ Carbon::parse($notice->created_at)->format("H時i分") }}
                                    @else
                                        {{ Carbon::parse($notice->created_at)->format("Y年m月d日") }}
                                    @endif
                                </td>
                                <td style="border-left:none">
                                    <!-- 返信が未読ならアイコン表示 -->
                                    @if($notice->reply_id !== 0 && $notice->status == 'unread')
                                        <i class="fa-solid fa-circle-exclamation exclamation-{{$notice->id}}" style="color:red"></i>
                                    @endif
                                    <!-- お知らせが配信されて６日以内ならアイコン表示 -->
                                    @if($notice->reply_id == 0 && Carbon::parse($notice->created_at)->diffInDays(today()) < 6)
                                        <i style="color:#0dcaf0">new</i>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="js-modal-notice-{{ $notice->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <span>{{ $notice->title }}</span>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="notice-content">{{ $notice->content }}</p>
                                            <p style="color:gray">{{ Carbon::parse($notice->created_at)->format("Y年m月d日 H時i分") }}受信</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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