<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 商品一覧
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * ユーザー一覧
     */
    public function users()
    {
        $users = User::
            select('users.*', DB::raw('(SELECT COUNT(*) FROM items WHERE user_id = users.id AND draft is null) AS item_count'))
            ->paginate(10);

        $user_count = count(User::all());
        return view('admin.users', compact('users', 'user_count'));
    }

    //会員情報削除
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('admin/users');
        
    }
}
