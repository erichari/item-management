<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inquiry;
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
     * トップページ（問い合わせ一覧）
     */
    public function index()
    {
        $inquiries = Inquiry::orderby('updated_at', 'desc')->paginate(10);
        return view('admin.index', compact('inquiries'));
    }
    /**
     *
     */
    public function inquiry(Request $request)
    {        
        // GETの場合
        if($request->isMethod("GET")){
            $inquiry = Inquiry::find($request->id);
            return view('admin.inquiry_show', compact('inquiry'));
        }

        // POSTの場合
        if($request->isMethod("POST")){
            $this->validate($request, [
                'title' => 'required|max:20',
                'content' => 'required|max:200',
            ]);

            Inquiry::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return redirect('/admin');
        }
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
