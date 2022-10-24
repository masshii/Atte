<?php

namespace App\Http\Controllers;

use App\Models\Rest;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class BreakStampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function startBreak()
    {
        $user = Auth::user();
        $dateStamp = Carbon::today();
        $timeStamp = Carbon::now();

        $workStamp = Attendance::where('user_id', $user->id)->where('date', $dateStamp)->first();

        if (empty($workStamp->id)) {
            return redirect()->back()->with('result', '勤務を開始されていません');
        } else {
            $workStamp_id = $workStamp->id;
        }
        $noStartTime = Attendance::where('user_id', $user->id)->where('date', $dateStamp)->whereNull('start_time')->first();
        $oldBreak = Rest::where('attendance_id', $workStamp_id)->latest()->first();
        $oldBreakDay = "";

        if (!empty($noStartTime)) {
            return redirect()->back()->with('result', '勤務を開始されていません');
        }

        $newBreak = Carbon::today();
        
        if ($oldBreak) {
            $oldBreakDate = new Carbon($oldBreak->break_start);
            $oldBreakDay = $oldBreakDate->startOfDay();
        }

        if (($oldBreakDay == $newBreak) && (empty($oldBreak->break_end))) {
            return redirect()->back()->with('result', '休憩を開始されています');
        }

        $break = Attendance::where('user_id', $user->id)->whereNull('end_time')->where('date', $dateStamp)->first();

        if (empty($break)) {
            return redirect()->back()->with('result', '本日の勤務を終了されています');
        }

        Rest::create([
            'attendance_id' => $workStamp_id,
            'date' => $dateStamp,
            'break_start' => $timeStamp,
        ]);

        return redirect('/stamp')->with('message', '休憩を開始しました');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endBreak()
    {
        $user = Auth::user();
        $dateStamp = Carbon::today();
        $timeStamp = Carbon::now();

        $workStamp = Attendance::where('user_id', $user->id)->where('date', $dateStamp)->first();

        if (empty($workStamp->id)) {
            return redirect()->back()->with('result', '勤務を開始されていません');
        } else {
            $workStamp_id = $workStamp->id;
        }

        $break =  Rest::where('attendance_id', $workStamp->id)->whereNull('break_end')->where('date', $dateStamp)->first();

        if (!empty($break)) {
            $break->update(['break_end' => $timeStamp]);
            return redirect('stamp')->with('message', '休憩を終了しました');
        } else {
            return redirect()->back()->with('result', '休憩を開始されていないか、本日の勤務を終了されています');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rest  $rest
     * @return \Illuminate\Http\Response
     */
}
