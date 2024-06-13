<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $widget = [
            'users' => User::where('user_role', 'users')->count(),
        ];

        // Fetch upcoming events
        $events = Event::where('tanggal', '>=', Carbon::now())->paginate(10);

        return view('home', compact('widget', 'events'));
    }
}
