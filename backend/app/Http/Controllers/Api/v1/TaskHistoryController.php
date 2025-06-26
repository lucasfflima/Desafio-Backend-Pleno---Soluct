<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskHistoryResource;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskHistoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TaskHistory::query();

        //  Filtros
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has(['from', 'to'])) {
            $query->whereBetween('changed_at', [$request->from, $request->to]);
        }

        $histories = $query
            ->with(['user:id,name,email', 'task:id,title'])
            ->orderByDesc('changed_at')
            ->paginate($request->get('per_page', 15));

        return response()->json(TaskHistoryResource::collection($histories));
    }
}
