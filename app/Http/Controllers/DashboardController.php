<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $tasks = Task::where('user_id', Auth::id())->paginate(6);

        $statsTasks = Task::where('user_id', Auth::id())->get();

        $totalTasks = $statsTasks->count();
        $todoTasks = $statsTasks->where('status' , 'todo')->count();
        $inProgressTasks = $statsTasks->where('status' , 'in_progress')->count();
        $doneTasks = $statsTasks->where('status' , 'done')->count();

        $inDangerTasks = $statsTasks->filter(function ($task) {
        return $task->expiry_date  && $task->status !== 'done' && $task->expiry_date < today() ;
        })->count();

        $completionPercentage = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        $title = 'Dashboard Management';

        return view('dashboard.index' , compact('tasks' , 'totalTasks' , 'todoTasks' , 'inProgressTasks' , 'doneTasks' , 'inDangerTasks' , 'completionPercentage' , 'title'));
    }
}
