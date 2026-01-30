<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }

    public function search(Request $request)
    {
        $query = Task::query();

        $query->where('user_id', auth()->id());

        $query->when($request->search, function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $query->when($request->priority, function ($q, $priority) {
            return $q->where('priority', $priority);
        });

        $query->when($request->status, function ($q, $status) {
            return $q->where('status', $status);
        });

        $query->when($request->expiry_date, function ($q, $expiry_date) {
            return $q->orderBy('expiry_date', $expiry_date);
        });

        $tasks = $query->get();
        
        return response()->json($tasks);
    }
}
