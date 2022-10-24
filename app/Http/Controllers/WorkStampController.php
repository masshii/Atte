<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class WorkStampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth::user();

        return view('stamp', ['auth' => $auth]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function startTime()
    {
        $user = Auth::user();
        $dateStamp = Carbon::today();
        $timeStamp = Carbon::now();
        $oldDayStamp = "";

        $workStamp = Attendance::where('user_id', $user->id)->where('date', $dateStamp)->first();
        
        $oldWorkStamp = Attendance::where('user_id', $user->id)->latest()->first();
        $nweWorkStamp = Carbon::today();

        if($oldWorkStamp) {
            $oldDayStampDate = new Carbon($oldWorkStamp->start_time);
            $oldDayStamp = $oldDayStampDate->startOfDay();
        }

        if (($oldDayStamp == $nweWorkStamp)) {
            return redirect()->back()->with('result', '勤務を開始されているか、本日の勤務は終了されています');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $dateStamp,
            'start_time' => $timeStamp,
        ]);

        return redirect('/stamp')->with('message', '勤務を開始しました');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endTime()
    {
        $user = Auth::user();
        $dateStamp = Carbon::today();
        $timeStamp = Carbon::now();

        $workStamp = Attendance::where('user_id', $user->id)->whereNull('end_time')->where('date', $dateStamp)->first();
        $workStampId = Attendance::where('user_id', $user->id)->where('date', $dateStamp)->first();

        if (empty($workStamp->id)) {
            return redirect()->back()->with('result', '勤務を開始されていないか、本日の勤務を終了されています');
        } else {
            $workStamp_id = $workStampId->id;
        }

        $break_id = Rest::where('break_id');
        $oldBreak = Rest::where('attendance_id', $workStamp_id)->latest()->first();
        $oldBreakDay = "";
        $newBreak = Carbon::today();
        
        if ($oldBreak) {
            $oldBreakDate = new Carbon($oldBreak->break_start);
            $oldBreakDay = $oldBreakDate->startOfDay();
        }

        if (($oldBreakDay == $newBreak) && (empty($oldBreak->break_end))) {
            return redirect()->back()->with('result', '休憩を終了されていません');
        }

        if (!empty($workStamp)) {
            $workStamp->update(['end_time' => $timeStamp, 'break_id' => $break_id]);
            return redirect('/stamp')->with('message', '勤務を終了しました');
        } else {
            return redirect()->back()->with('result', '本日の勤務を終了されています');
        }
    }

    public function attendance(Request $request, $num)
    {
        $user = Auth::user();
        $date = Carbon::today();
        $day = $num;
        $date = date("Y-m-d", strtotime($date .' '. $day . 'day'));
        $data = Attendance::where('date', $date)->get();
        $array = array();
        $start_time = "";
        $end_time = "";


        foreach($data as $attendance) {
            $breakData = Rest::where('attendance_id', $attendance->id)->get();
            $totalBreak = 0;
            foreach($breakData as $breakAttendance) {
                if ($breakAttendance->break_end) {
                    $totalBreak += strtotime($breakAttendance->break_end) - strtotime($breakAttendance->break_start);
                }
                    $start_time = date('H:m:s', strtotime($attendance->start_time));
            }
            $breakSeconds = $totalBreak % 60;
            $breakMinutes = ($totalBreak - $breakSeconds) / 60;
            $breakHou = $breakMinutes % 60;
            $breakHours = ($breakMinutes - $breakHou) / 60;
            $totalBreak = (sprintf("%02d", $breakHours) . ":" . sprintf("%02d", $breakHou) . ":" . sprintf("%02d", $breakSeconds));

            $workData = Attendance::where('id', $attendance->id)->get();
            $totalWork = 0;
            foreach ($workData as $workAttendance) {
                if ($workAttendance->end_time) {
                $totalWork = strtotime($workAttendance->end_time) - strtotime($workAttendance->start_time);
                }
            }

            $workSeconds = $totalWork % 60;
            $workMinutes = ($totalWork - $workSeconds) / 60;
            $workHou = $workMinutes % 60;
            $workHours = ($workMinutes - $workHou) / 60;
            $totalWorkNoBreak = (sprintf("%02d", $workHours) . ":" . sprintf("%02d", $workHou) . ":" . sprintf("%02d", $workSeconds));
            
            $totalWorkBreak = strtotime($totalWorkNoBreak) - strtotime($totalBreak);
            
            $workSeconds_a = $totalWorkBreak % 60;
            $workMinutes_a = ($totalWorkBreak - $workSeconds_a) / 60;
            $workHou_a = $workMinutes_a % 60;
            $workHours_a = ($workMinutes_a - $workHou_a) / 60;
            if($totalWork == 0) {
                $totalWork = "";
            } else {
                    $totalWork = (sprintf("%02d", $workHours_a) . ":" . sprintf("%02d", $workHou_a) . ":" . sprintf("%02d", $workSeconds_a));
            }

            $user = User::find($attendance->user_id);

            array_push(
                $array,
                array(
                    'name' => $user->name,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'total_break' => $totalBreak,
                    'total_work' => $totalWork,
                    )
            );
        }
        $coll = collect($array);
        $pageData = $this->paginate($coll, 5, null, ['path'=>'/attendance/'. $num]);

        return view('attendance')->with([
            "date" => $date,
            "data" => $data,
            "array" => $array,
            "pageData" => $pageData,
            "num" => $num,
        ]);
    }
    
    private function paginate($items, $perPage = 5, $page = null, $options = []) {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $page, $perPage, $options);
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}

