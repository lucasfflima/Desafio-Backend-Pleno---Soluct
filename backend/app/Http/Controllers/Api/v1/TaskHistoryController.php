<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskHistoryResource;
use App\Models\TaskHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskHistoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TaskHistory::with(['task', 'user'])
            ->whereHas('task', function ($q) {
                $q->where('user_id', Auth::id());
            });

        if ($request->filled('task_id')) {
            $query->where('task_id', $request->input('task_id'));
        }

        if ($request->filled('field')) {
            $query->where('field_changed', $request->input('field'));
        }

        if ($request->filled('date_start')) {
            $query->whereDate('changed_at', '>=', $request->input('date_start'));
        }

        if ($request->filled('date_end')) {
            $query->whereDate('changed_at', '<=', $request->input('date_end'));
        }

        $sort = $request->input('sort', 'changed_at');
        $direction = $request->input('direction', 'desc');

        $histories = $query->orderBy($sort, $direction)->paginate(10);

        return response()->json(TaskHistoryResource::collection($histories));
    }
}
