<?php

namespace App\Http\Controllers\API\v1;

use App\Enums\ResourceStatus;
use App\Http\Requests\UserStats\ListUserStatsRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;

class UserStatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListUserStatsRequest $request, User $user)
    {
        $activeResourcesCount = $user->getAuthoredResourcesCount([
            ResourceStatus::UNDER_PREPARATION,
        ]);

        $pendingReviewResourcesCount = $user->getAuthoredResourcesCount([
            ResourceStatus::UNDER_REVIEW
        ]);

        $pendingUploadsCount = $user->getAuthoredResourcesCount([
            ResourceStatus::APPROVED,
        ]);

        return response()->json([
            'data' => [
                'active_tasks' => $activeResourcesCount,
                'pending_review_tasks' => $pendingReviewResourcesCount,
                'pending_upload_tasks' => $pendingUploadsCount,
            ]
        ]);
    }
}
