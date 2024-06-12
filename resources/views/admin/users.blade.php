@extends('adminlte::page')

@section('title', '管理者用ページ -ユーザー一覧-')

@section('content_header')
    <h1>管理者用ページ -ユーザー一覧-</h1>
@stop

@section('content')
@if (session()->has('success'))
    <div class="success">
        {{ session()->get('success') }}
    </div>
@endif

<caption>総ユーザー数：{{ $user_count }}</caption>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="table-primary">
                <td scope="col">ID</td>
                <td scope="col">名前</td>
                <td scope="col">メールアドレス</td>
                <td scope="col">レシピ数</td>
                <td scope="col"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <th scope="row">{{$user->id}}</th>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->item_count}}</td>
                <td>
                    @if($user->id !== 1)
                    <div class="btn-destroy">
                        <form method="post" action="{{route('destroy',['id' => $user->id])}}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" data-user-id="{{$user->id}}" data-user-name="{{$user->name}}" onclick="return confirmUserDelete(this)">削除</button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="pagination">
    {{ $users->links() }}
</p>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
@stop

@section('js')
<script>
    function confirmUserDelete(button) {
        var userName = button.getAttribute('data-user-name');
        var userId = button.getAttribute('data-user-id');

        return confirm("会員ID:" + userId + '　' + userName + "さんのデータを削除してよろしいですか？\n" +
            "この操作は元に戻せません。\n");
    }
</script>
@stop