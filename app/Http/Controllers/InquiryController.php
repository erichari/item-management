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
        $notices = Inquiry::leftjoin('inquiries as replied_inquiry', 'replied_inquiry.id', 'inquiries.reply_id')
            ->where('inquiries.reply_id', 0)
            ->orWhere('replied_inquiry.user_id', Auth::user()->id)
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
    public function change_status(Request $request)
    {
        $notice_id = $request->notice_id;

        Inquiry::find($notice_id)->update([
            'status' => 'read',
        ]);

        $param = [
            'notice_id' => $notice_id,
        ];
        
        return response()->json($param);
    }
}
