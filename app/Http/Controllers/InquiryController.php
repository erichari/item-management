<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Carbon\Carbon;

class InquiryController extends Controller
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
     * お知らせ一覧
     */
    public function notice(Request $request){
        // $info = Inquiry::where('reply_id', 0)
        //     ->orderby('created_at', 'desc')
        //     ->paginate(10);

        //運営からのお知らせ(reply_id == 0)と自分宛てのお知らせ取得
        $notices = Inquiry::join('inquiries as replied_inquiry', 'replied_inquiry.id', 'inquiries.reply_id')
            ->join('users', 'users.id', 'replied_inquiry.user_id')
            ->where('inquiries.reply_id', 0)
            ->orWhere('users.id', Auth::user()->id)
            ->select('inquiries.*')
            ->orderby('created_at', 'desc')
            ->paginate(10);


        return view('item.notice', compact('notices'));
    }

    /**
     * 問い合わせ
     */
    public function inquiry(Request $request){
        // GETの場合
        if($request->isMethod("GET")){

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

            return redirect('/inquiry');
        }
    }

    /**
     * お知らせ詳細
     */
    public function notice_show(Request $request)
    {
        $notice = Inquiry::find($request->id);

        if($notice->status == 'unread'){
            $notice->update([
                'status' => 'read'
            ]);
        }

        return view('item.notice_show', compact('notice'));
    }
}
