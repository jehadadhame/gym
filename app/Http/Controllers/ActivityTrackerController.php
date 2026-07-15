<?php

namespace App\Http\Controllers;

use App\ActivityTracker;
use Illuminate\Http\Request;

class ActivityTrackerController extends Controller
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
     * Display a listing of the activity trackers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ActivityTracker::with('user')->orderBy('created_at', 'desc');

        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('drp_start') && $request->has('drp_end')) {
            // Include end of day for drp_end if it's just a date
            $end_date = $request->drp_end;
            if (strlen($end_date) == 10) {
                $end_date .= ' 23:59:59';
            }
            $query->whereBetween('created_at', [$request->drp_start, $end_date]);
            $drp_placeholder = $request->drp_start . ' - ' . $request->drp_end;
        } else {
            $drp_placeholder = 'تحديد نطاق التاريخ';
        }

        if ($request->has('today') && $request->today == 1) {
            $query->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString());
        }

        $activities = $query->paginate(50);
        $users = \App\User::lists('name', 'id');

        $request->flash();

        return view('activity_trackers.index', compact('activities', 'drp_placeholder', 'users'));
    }
}
