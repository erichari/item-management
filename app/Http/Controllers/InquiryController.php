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
     * 問い合わせ一覧
     */
    public function inquiry(Request $request){
        // GETの場合
        if($request->isMethod("GET")){
            $inquiries = Auth::user()->inquiries()->paginate(10);
            return view('item.inquiry', compact('inquiries'));
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
}
