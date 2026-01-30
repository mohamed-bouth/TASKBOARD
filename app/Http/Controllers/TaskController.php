<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{

    public function index(){
        $tasks = Task::where('user_id', Auth::id())->get();

        $totalTasks = $tasks->count();
        return view('task.index' , compact('tasks'));
    }

    public function create(){
        return view('task.add');
    }

    public function store(Request $request){

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,done', 
            'expiry_date' => 'nullable',
        ]);

        Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('task.index')
        ->with('success', 'Task created successfully.');
    }

    public function destroy($id){
        $task = Task::withTrashed()
                ->where('id' , $id)
                ->where('user_id' , auth()->id())
                ->first();

        if($task) {
            $task->forceDelete();
        }

        return redirect()->route('task.index')
        ->with('success', 'Task deleted permanently.');
    }

    public function edit(Task $task){
        if ($task->user_id !== auth()->id()) {
        abort(403);
        }
        return view('task.edit', compact('task'));
    }

    public function update(Task $task , Request $request){
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,done', 
            'expiry_date' => 'nullable',
        ]);

        $task->update([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('task.index')
        ->with('success', 'Task edited successfully.');
    }

    public function updateStatus(Request $request, Task $task){
        $request->validate([
        'status' => 'required|in:todo,in_progress,done'
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['message' => 'Task updated successfully']);
    }
}
