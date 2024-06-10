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
    public function notice(Request $request)
    {
        // GETの場合 運営からのお知らせ(reply_id == 0)と自分宛てのお知らせ取得
        if($request->isMethod("GET")){
            $notices = Inquiry::leftjoin('inquiries as replied_inquiry', 'replied_inquiry.id', 'inquiries.reply_id')
                ->where('inquiries.reply_id', 0)
                ->orWhere('replied_inquiry.user_id', Auth::user()->id)
                ->select('inquiries.*')
                ->orderby('created_at', 'desc')
                ->paginate(10);

            return view('item.notice', compact('notices'));
        }

        // PATCHの場合 お知らせを既読にする
        if($request->isMethod("PATCH")){
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

    /**
     * 問い合わせ
     */
    public function inquiry(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:40',
            'content' => 'required|max:400',
        ]);

        Inquiry::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect('/notice');
    }

    /**
     * お知らせを既読にする
     */

}
