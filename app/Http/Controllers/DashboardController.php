<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $tasks = Task::where('user_id', Auth::id())->get();
        return view('dashboard.index' , compact('tasks'));
    }
}
