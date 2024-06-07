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
        $inquiries = Inquiry::where('user_id', '!=', Auth::user()->id)
            ->orderby('created_at', 'desc')
            ->paginate(10);
        return view('admin.index', compact('inquiries'));
    }

    /**
     * 問い合わせ
     */
    public function inquiry(Request $request)
    {        
        // GETの場合 問い合わせ詳細画面
        if($request->isMethod("GET")){
            $inquiry = Inquiry::join('users', 'users.id', 'inquiries.user_id')
                ->select('inquiries.*', 'users.name')
                ->find($request->id);

            if($inquiry->status == 'unread'){
                $inquiry->update([
                    'status' => 'read'
                ]);
            }
            
            $replies = Inquiry::where('reply_id', $request->id)->get();

            return view('admin.inquiry_show', compact('inquiry', 'replies'));
        }

        // POSTの場合 問い合わせに返信
        if($request->isMethod("POST")){
            $this->validate($request, [
                'title' => 'required|max:20',
                'content' => 'required|max:200',
            ]);

            Inquiry::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'content' => $request->content,
                'reply_id' => $request->id,
            ]);

            Inquiry::find($request->id)->update([
                'status' => 'replied'
            ]);

            return redirect('/admin');
        }

        // PATCHの場合 返信せず対応済みに変更
        if($request->isMethod("PATCH")){
            Inquiry::find($request->id)->update([
                'status' => 'replied'
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

    /**
     * お知らせ
     */
    public function info(Request $request)
    {        
        // GETの場合 お知らせ一覧画面
        if($request->isMethod("GET")){
            $allInfo = Inquiry::where('reply_id', 0)
                ->orderby('created_at', 'desc')
                ->paginate(10);
            return view('admin.info', compact('allInfo'));
        }

        // POSTの場合 お知らせ配信
        if($request->isMethod("POST")){
            $this->validate($request, [
                'title' => 'required|max:40',
                'content' => 'required|max:400',
            ]);

            Inquiry::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'content' => $request->content,
                'reply_id' => 0,
            ]);
            
            return redirect('/admin/info');
        }
    }

}
