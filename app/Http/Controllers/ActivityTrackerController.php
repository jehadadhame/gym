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
    public function index()
    {
        $activities = ActivityTracker::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('activity_trackers.index', compact('activities'));
    }
}
